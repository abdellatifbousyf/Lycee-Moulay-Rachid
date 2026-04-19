<?php

namespace App\Http\Controllers\Prof;

use App\Http\Controllers\Controller;
use App\Models\Absence;
use App\Models\Etudiant;
use App\Models\Filiere;
use App\Models\Matiere;
use App\Models\Enseignant;
use App\Models\Seance;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ProfController extends Controller
{
    /**
     * Display the professor dashboard (4ayab project).
     */
    public function index(): View
    {
        // ✅ جلب إحصائيات سريعة للأستاذ (اختياري)
        // $stats = [
        //     'total_seances' => Seance::where('id_ens', $this->getProfId())->count(),
        //     'pending_absences' => Absence::whereHas('seance', fn($q) =>
        //         $q->where('id_ens', $this->getProfId())->where('active', 0)
        //     )->count(),
        // ];

        return view('Enseignant.EspaceProf');
    }

    /**
     * Show the form to create a new seance.
     */
    public function createSeance(): View
    {
        $id_prof = $this->getProfId();

        // ✅ جلب المواد الخاصة بالأستاذ الحالي فقط
        $matieres = Matiere::select('id', 'nom_mat')
            ->where('id_ens', $id_prof)
            ->orderBy('nom_mat')
            ->get();

        return view('Enseignant.createSeance', compact('matieres', 'id_prof'));
    }

    /**
     * Store a newly created seance in database.
     */
    public function saveSeance(Request $request): RedirectResponse
    {
        // ✅ 1. التحقق من البيانات (بدون مسافات + قواعد واضحة)
        $validated = $request->validate([
            'date'          => 'required|date|after_or_equal:today',
            'H_debut'       => 'required|date_format:H:i',
            'H_fin'         => 'required|date_format:H:i|after:H_debut',
            'matiere'       => 'required|exists:matieres,id',
            'type_seance'   => 'required|string|in:TD,TP,Cours,Examen',
            'active'        => 'nullable|boolean',
        ], [
            // ✅ رسائل خطأ مخصصة بالعربية
            'date.after_or_equal' => 'تاريخ الحصة يجب أن يكون اليوم أو مستقبلاً',
            'H_fin.after' => 'وقت النهاية يجب أن يكون بعد وقت البداية',
            'type_seance.in' => 'نوع الحصة غير صالح',
            'matiere.exists' => 'المادة المختارة غير صحيحة',
        ]);

        $id_prof = $this->getProfId();

        // ✅ 2. التأكد أن المادة تابعة لهذا الأستاذ (أمان إضافي)
        $matiere = Matiere::where('id', $validated['matiere'])
            ->where('id_ens', $id_prof)
            ->firstOrFail();

        try {
            // ✅ 3. إنشاء الحصة
            Seance::create([
                'date'         => $validated['date'],
                'heure_debut'  => $validated['H_debut'],
                'heure_fin'    => $validated['H_fin'],
                'id_ens'       => $id_prof,
                'id_mat'       => $matiere->id,
                'type'         => $validated['type_seance'],
                'active'       => $validated['active'] ?? 0,
                'created_by'   => Auth::id(),
            ]);

            return redirect()
                ->route('list.seance')
                ->with('success', '✅ تم إضافة الحصة بنجاح');

        } catch (\Illuminate\Database\QueryException $e) {
            Log::error('4ayab: DB error while adding seance - ' . $e->getMessage(), [
                'prof_id' => $id_prof,
                'payload' => $request->except('password'),
            ]);

            return back()
                ->withInput()
                ->with('error', '❌ خطأ في قاعدة البيانات، حاول مرة أخرى');

        } catch (\Exception $e) {
            Log::error('4ayab: Failed to add seance - ' . $e->getMessage());

            return back()
                ->withInput()
                ->with('error', '❌ حدث خطأ أثناء الحفظ، حاول مرة أخرى');
        }
    }

    /**
     * Display all seances for the current professor (active=0 means pending).
     */
    public function listSeance(): View
    {
        $id_prof = $this->getProfId();

        // ✅ استخدام eager loading + pagination لأداء أفضل
        $seances = Seance::with(['seancematiere.filieremat'])
            ->where('id_ens', $id_prof)
            ->orderBy('date', 'desc')
            ->orderBy('heure_debut', 'desc')
            ->paginate(15);

        return view('Enseignant.listSeance', compact('seances'));
    }

    /**
     * Show the form to record absences for a specific seance.
     */
    public function PageNoteAbsence(int $id): View|RedirectResponse
    {
        $id_prof = $this->getProfId();

        // ✅ التأكد أن الحصة تابعة لهذا الأستاذ
        $seance = Seance::where('id', $id)
            ->where('id_ens', $id_prof)
            ->with(['seancematiere.filieremat'])
            ->firstOrFail();

        // ✅ جلب الشعبة المرتبطة بالمادة
        $filiere = $seance->seancematiere->filieremat;

        if (!$filiere) {
            return back()->with('error', '⚠️ لا توجد شعبة مرتبطة بهذه المادة');
        }

        // ✅ جلب التلاميذ التابعين لهذه الشعبة
        $etudiants = Etudiant::where('id_filiere', $filiere->id)
            ->orderBy('nom_etu')
            ->orderBy('prenom_etu')
            ->get();

        if ($etudiants->isEmpty()) {
            return back()->with('info', 'ℹ️ لا يوجد تلاميذ مسجلين في هذه الشعبة');
        }

        // ✅ التحقق أن الحصة ما زال فيها إمكانية تسجيل الغياب (active=0)
        if ($seance->active) {
            return back()->with('warning', '⚠️ تم بالفعل تسجيل غيابات هذه الحصة');
        }

        return view('Enseignant.NoterAbsence', compact('seance', 'etudiants', 'filiere'));
    }

    /**
     * Store absences for a specific seance.
     * ✅ استخدام Transaction لضمان سلامة البيانات
     */
    public function saveAbsence(Request $request): RedirectResponse
    {
        $id_prof = $this->getProfId();

        // ✅ 1. التحقق من البيانات
        $validated = $request->validate([
            'id_sea' => 'required|integer|exists:seances,id',
            'absence' => 'required|array|min:1',
            'absence.*.id_etu' => 'required|integer|exists:etudiants,id',
            'absence.*.etat' => 'required|integer|in:0,1', // 0=غائب، 1=حاضر
            'absence.*.justification' => 'nullable|string|max:500',
        ], [
            'absence.required' => 'يجب تحديد حالة حضور التلاميذ',
            'absence.*.etat.in' => 'حالة الحضور غير صحيحة',
        ]);

        // ✅ 2. التأكد أن الحصة تابعة لهذا الأستاذ ولم تسجل بعد
        $seance = Seance::where('id', $validated['id_sea'])
            ->where('id_ens', $id_prof)
            ->where('active', 0) // ✅ فقط الحصص غير المسجلة
            ->firstOrFail();

        try {
            // ✅ 3. استخدام Transaction: إما كلشي ينجح، أو والو ما يتسجلش
            DB::transaction(function () use ($validated, $seance) {

                $absencesData = [];

                foreach ($validated['absence'] as $absence) {
                    $absencesData[] = [
                        'id_sea'        => $seance->id,
                        'id_etu'        => $absence['id_etu'],
                        'etat'          => $absence['etat'],
                        'justification' => $absence['justification'] ?? null,
                        'created_at'    => now(),
                        'updated_at'    => now(),
                    ];
                }

                // ✅ 4. الإدراج الجماعي (أسرع من loop فردي)
                if (!empty($absencesData)) {
                    Absence::insert($absencesData);
                }

                // ✅ 5. تحديث حالة الحصة إلى "مسجلة" لمنع التكرار
                $seance->update(['active' => 1]);
            });

            Log::info('4ayab: Absences recorded successfully', [
                'seance_id' => $seance->id,
                'prof_id' => $id_prof,
                'count' => count($validated['absence']),
            ]);

            return redirect()
                ->route('list.seance')
                ->with('success', '✅ تم تسجيل غيابات الحصة بنجاح');

        } catch (ModelNotFoundException $e) {
            return back()->with('error', '⚠️ الحصة غير موجودة أو تم تسجيل غياباتها مسبقاً');

        } catch (\Illuminate\Database\QueryException $e) {
            // ✅ التعامل مع أخطاء التكرار (Duplicate entry)
            if (str_contains($e->getMessage(), 'Duplicate entry')) {
                return back()->with('error', '❌ تم تسجيل غيابات بعض التلاميذ مسبقاً');
            }

            Log::error('4ayab: DB error while saving absences - ' . $e->getMessage(), [
                'seance_id' => $request->id_sea ?? null,
                'prof_id' => $id_prof,
            ]);

            return back()->with('error', '❌ خطأ في قاعدة البيانات، حاول مرة أخرى');

        } catch (\Exception $e) {
            Log::error('4ayab: Failed to save absences - ' . $e->getMessage(), [
                'seance_id' => $request->id_sea ?? null,
                'prof_id' => $id_prof,
            ]);

            return back()->with('error', '❌ حدث خطأ أثناء الحفظ، حاول مرة أخرى');
        }
    }

    /**
     * Display absence history for the current professor.
     */
    public function historiqueAbsence(Request $request): View
    {
        $id_prof = $this->getProfId();

        // ✅ جلب المواد الخاصة بالأستاذ (للفلترة)
        $matieres = Matiere::select('id', 'nom_mat')
            ->where('id_ens', $id_prof)
            ->orderBy('nom_mat')
            ->get();

        // ✅ بناء الاستعلام مع الفلترة الاختيارية
        $query = Seance::with(['seancematiere', 'absences.etudiant'])
            ->where('id_ens', $id_prof)
            ->where('active', 1); // ✅ فقط الحصص التي سجل فيها غياب

        // 🔍 فلترة بالمادة (اختياري)
        if ($request->filled('matiere')) {
            $query->where('id_mat', $request->matiere);
        }

        // 🔍 فلترة بالتاريخ (اختياري)
        if ($request->filled('date_from')) {
            $query->where('date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->where('date', '<=', $request->date_to);
        }

        // ✅ تنفيذ الاستعلام مع pagination
        $seances = $query->orderBy('date', 'desc')
            ->orderBy('heure_debut', 'desc')
            ->paginate(15)
            ->withQueryString(); // ✅ للحفاظ على الفلاتر فـ الروابط

        // ✅ حساب إحصائيات سريعة لكل حصة (اختياري - يمكن نقله للـ View)
        // $seances->getCollection()->transform(fn($s) => $s->setAttribute('stats', [
        //     'total' => $s->absences->count(),
        //     'absents' => $s->absences->where('etat', 0)->count(),
        // ]));

        return view('Enseignant.historiqueAbsence', compact('seances', 'matieres'));
    }

    // ─────────────────────────────────────────────────────────────
    // 🔧 Helper Methods (Private)
    // ─────────────────────────────────────────────────────────────

    /**
     * ✅ Get the current professor's ID safely.
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    private function getProfId(): int
    {
        $user = Auth::user();

        if (!$user) {
            throw new \Illuminate\Auth\Access\AuthorizationException('غير مصادق عليك');
        }

        // ✅ جلب الـ ID الخاص بـ Enseignant المرتبط بالمستخدم
        $prof = Enseignant::select('id')
            ->where('id_user', $user->id)
            ->value('id');

        if (!$prof) {
            Log::warning('4ayab: User tried to access prof features without prof record', [
                'user_id' => $user->id,
            ]);
            throw new \Illuminate\Auth\Access\AuthorizationException('غير مصرح لك كأستاذ');
        }

        return $prof;
    }

    /**
     * ✅ (اختياري) حماية جميع ميثودات هذا الـ Controller
     */
    // public function __construct()
    // {
    //     $this->middleware(['auth', 'role:3']); // 👨‍🏫 Professor role only
    // }
}
