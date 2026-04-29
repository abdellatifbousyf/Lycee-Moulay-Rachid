<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Etudiant;
use App\Models\Filiere;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Log;

class EtudiantController extends Controller
{
    /**
     * Show the form to add a new student (4ayab project).
     */
    public function addStudent(): View
    {
        $filieres = Filiere::select('id', 'nom_filiere')->get();

        return view('admin.etudiant.addStudent', compact('filieres'));
    }

    /**
     * Display all students with their filiere.
     */
    public function showAllStudent(): View
    {
        // ✅ استخدام lazy loading عشان الأداء (إذا عندك بزاف دالتلاميد)
        $students = Etudiant::with('filiere')->latest()->paginate(15);

        return view('admin.etudiant.showAll', compact('students'));
    }

    /**
     * Store a newly created student in database.
     */
    public function saveStudent(Request $request): RedirectResponse
    {
        // ✅ 1. التحقق من البيانات (بدون مسافات زائدة)
        $validated = $request->validate([
            'nom'      => 'required|string|max:255',
            'prenom'   => 'required|string|max:255',
            'cne'      => 'required|string|unique:etudiants,cne|max:20',
            'phone'    => 'required|numeric|digits:10',
            'filiere'  => 'required|exists:fileres,id',
        ], [
            // ✅ رسائل خطأ مخصصة (اختياري)
            'cne.unique' => 'هذا CNE مسجل مسبقاً',
            'phone.digits' => 'رقم الهاتف يجب أن يتكون من 10 أرقام',
        ]);

        try {
            // ✅ 2. إنشاء الطالب (استخدم $fillable فـ الـ Model)
            Etudiant::create([
                'cne'        => $validated['cne'],
                'nom_etu'    => $validated['nom'],
                'prenom_etu' => $validated['prenom'],
                'phone_etu'  => $validated['phone'],
                'id_filiere' => $validated['filiere'],
                'id_user'    => auth()->id(), // ✅ أفضل من hardcoded 4
            ]);

            return redirect()
                ->route('show.all.student')
                ->with('success', '✅ تم إضافة التلميذ بنجاح');

        } catch (\Exception $e) {
            // ✅ 3. تسجيل الخطأ فـ الـ Log بدلاً من عرضو للمستخدم
            Log::error('4ayab: Failed to add student - ' . $e->getMessage(), [
                'user_id' => auth()->id(),
                'payload' => $request->all(),
            ]);

            return back()
                ->withInput()
                ->with('error', '❌ حدث خطأ أثناء الحفظ، حاول مرة أخرى');
        }
    }

    /**
     * Show the form for editing the specified student.
     */
    public function editStudent(int $id): View|RedirectResponse
    {
        // ✅ استخدام Route Model Binding أو findOrFail
        $etudiant = Etudiant::find($id);

        if (!$etudiant) {
            return redirect()
                ->route('show.all.student')
                ->with('error', '⚠️ التلميذ غير موجود');
        }

        $filieres = Filiere::select('id', 'nom_filiere')->get();

        return view('admin.etudiant.update', compact('filieres', 'etudiant'));
    }

    /**
     * Update the specified student in database.
     */
    public function updateStudent(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'id'       => 'required|integer|exists:etudiants,id',
            'nom'      => 'required|string|max:255',
            'prenom'   => 'required|string|max:255',
            'cne'      => 'required|string|max:20|unique:etudiants,cne,' . $request->id,
            'phone'    => 'nullable|numeric|digits:10',
            'filiere'  => 'required|exists:fileres,id',
        ]);

        try {
            $etudiant = Etudiant::findOrFail($validated['id']);

            $etudiant->update([
                'cne'        => $validated['cne'],
                'nom_etu'    => $validated['nom'],
                'prenom_etu' => $validated['prenom'],
                'phone_etu'  => $validated['phone'] ?? $etudiant->phone_etu,
                'id_filiere' => $validated['filiere'],
                // 'id_user' => auth()->id(), // اختياري: إذا بغيتي تحدث المستخدم المسؤول
            ]);

            return redirect()
                ->route('show.all.student')
                ->with('update', '✅ تم تعديل بيانات التلميذ بنجاح');

        } catch (\Exception $e) {
            Log::error('4ayab: Failed to update student - ' . $e->getMessage());

            return back()
                ->withInput()
                ->with('error', '❌ فشل التعديل، تأكد من البيانات');
        }
    }

    /**
     * Remove the specified student from database (Soft Delete recommended).
     */
    public function deleteStudent(int $id): RedirectResponse
    {
        try {
            $student = Etudiant::findOrFail($id);

            // ✅ الخيار 1: حذف نهائي
            // $student->delete();

            // ✅ الخيار 2 (موصى به): Soft Delete (باش ما تضيعش البيانات)
            // تأكد أن الـ Model فيها: use SoftDeletes;
            $student->delete();

            return redirect()
                ->route('show.all.student')
                ->with('delete', '🗑️ تم حذف التلميذ بنجاح');

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()
                ->route('show.all.student')
                ->with('error', '⚠️ التلميذ غير موجود');
        } catch (\Exception $e) {
            Log::error('4ayab: Failed to delete student - ' . $e->getMessage());

            return back()->with('error', '❌ حدث خطأ أثناء الحذف');
        }
    }
        // =====================================================
    // ✅ دوال الطالب (لصفحات العرض - واجهة الطالب)
    // =====================================================

    /**
     * عرض صفحة الغيابات للطالب الحالي
     * Route: etudiant.absences
     */
    public function absences(): \Illuminate\View\View
    {
        $user = auth()->user();

        // ✅ جلب الغيابات (عدّل حسب الموديل والجداول عندك)
        $absences = \App\Models\Absence::where('etudiant_id', $user->id)
            ->with('seance')
            ->latest()
            ->paginate(10);

        return view('etudiant.absences', compact('absences'));
    }

    /**
     * عرض صفحة النقاط للطالب الحالي
     * Route: etudiant.notes
     */
    public function notes(): \Illuminate\View\View
    {
        $user = auth()->user();

        // ✅ جلب النقاط (عدّل حسب الموديل عندك)
        $notes = \App\Models\Note::where('etudiant_id', $user->id)
            ->with('matiere', 'evaluation')
            ->latest()
            ->get();

        return view('etudiant.notes', compact('notes'));
    }

    /**
     * عرض جدول الحصص للطالب الحالي
     * Route: etudiant.emploi
     */
    public function emploi(): \Illuminate\View\View
    {
        $user = auth()->user();
        $classe = $user->classe ?? null;

        // ✅ جلب الجدول (عدّل حسب الموديل عندك)
        $emploi = $classe ?
            \App\Models\Emploi::where('classe_id', $classe->id)
                ->with('matiere', 'professeur', 'salle')
                ->orderBy('jour')
                ->orderBy('heure_debut')
                ->get() : collect();

        return view('etudiant.emploi', compact('emploi', 'classe'));
    }
}

