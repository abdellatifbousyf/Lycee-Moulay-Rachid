<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Matiere;
use App\Models\Filiere;
use App\Models\Semestre;
use App\Models\Enseignant;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Log;

class MatiereController extends Controller
{
    /**
     * Show the form to add a new subject/matiere (4ayab project).
     */
    public function addMatiere(): View
    {
        $filieres = Filiere::select('id', 'nom_filiere')->get();
        $semestres = Semestre::select('id', 'nom_sem')->get();
        $profs = Enseignant::select('id', 'nom_ens')->get();

        return view('admin.matiere.addMatiere', compact('filieres', 'semestres', 'profs'));
    }

    /**
     * Display all matieres with their relationships.
     */
    public function showAllMatiere(): View
    {
        // ✅ استخدام pagination لأداء أفضل
        $matieres = Matiere::with(['filieremat', 'semestre', 'enseignantMatiere'])
            ->latest()
            ->paginate(15);

        return view('admin.matiere.showAll', compact('matieres'));
    }

    /**
     * Store a newly created matiere in database.
     */
    public function saveMatiere(Request $request): RedirectResponse
    {
        // ✅ 1. التحقق من البيانات (بدون مسافات زائدة + قواعد واضحة)
        $validated = $request->validate([
            'nom'       => 'required|string|max:255',
            'filiere'   => 'required|exists:fileres,id',
            'semestre'  => 'required|exists:semestres,id',
            'prof'      => 'required|exists:enseignants,id',
        ], [
            // ✅ رسائل خطأ مخصصة
            'nom.required' => 'اسم المادة مطلوب',
            'filiere.exists' => 'الشعبة المختارة غير صحيحة',
            'semestre.exists' => 'الفصل الدراسي المختار غير صحيح',
            'prof.exists' => 'الأستاذ المختار غير موجود',
        ]);

        try {
            // ✅ 2. إنشاء المادة (استخدم $fillable فـ الـ Model)
            Matiere::create([
                'nom_mat'    => $validated['nom'],
                'id_filiere' => $validated['filiere'],
                'id_sem'     => $validated['semestre'],
                'id_ens'     => $validated['prof'],
                'id_user'    => auth()->id(), // ✅ ديناميكي بدلاً من hardcoded
            ]);

            return redirect()
                ->route('show.all.matiere')
                ->with('success', '✅ تم إضافة المادة بنجاح');

        } catch (\Exception $e) {
            // ✅ 3. تسجيل الخطأ فـ الـ Log بدلاً من عرضو للمستخدم
            Log::error('4ayab: Failed to add matiere - ' . $e->getMessage(), [
                'user_id' => auth()->id(),
                'payload' => $request->all(),
            ]);

            return back()
                ->withInput()
                ->with('error', '❌ حدث خطأ أثناء الحفظ، حاول مرة أخرى');
        }
    }

    /**
     * Show the form for editing the specified matiere.
     */
    public function editMatiere(int $id): View|RedirectResponse
    {
        // ✅ التحقق من وجود المادة
        $matiere = Matiere::find($id);

        if (!$matiere) {
            return redirect()
                ->route('show.all.matiere')
                ->with('error', '⚠️ المادة غير موجودة');
        }

        $filieres = Filiere::select('id', 'nom_filiere')->get();
        $semestres = Semestre::select('id', 'nom_sem')->get();
        $profs = Enseignant::select('id', 'nom_ens')->get();

        return view('admin.matiere.update', compact('matiere', 'filieres', 'semestres', 'profs'));
    }

    /**
     * Update the specified matiere in database.
     */
    public function updateMatiere(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'id'        => 'required|integer|exists:matieres,id',
            'nom'       => 'required|string|max:255',
            'filiere'   => 'required|exists:fileres,id',
            'semestre'  => 'required|exists:semestres,id',
            'prof'      => 'required|exists:enseignants,id',
        ]);

        try {
            $matiere = Matiere::findOrFail($validated['id']);

            $matiere->update([
                'nom_mat'    => $validated['nom'],
                'id_filiere' => $validated['filiere'],
                'id_sem'     => $validated['semestre'],
                'id_ens'     => $validated['prof'],
                // 'id_user' => auth()->id(), // اختياري: تحديث المسؤول
            ]);

            return redirect()
                ->route('show.all.matiere')
                ->with('update', '✅ تم تعديل المادة بنجاح');

        } catch (\Exception $e) {
            Log::error('4ayab: Failed to update matiere - ' . $e->getMessage());

            return back()
                ->withInput()
                ->with('error', '❌ فشل التعديل، تأكد من البيانات');
        }
    }

    /**
     * Remove the specified matiere from database.
     */
    public function deleteMatiere(int $id): RedirectResponse
    {
        try {
            $matiere = Matiere::findOrFail($id);

            // ✅ الخيار 1: حذف نهائي
            // $matiere->delete();

            // ✅ الخيار 2 (موصى به): Soft Delete
            // تأكد أن الـ Model فيها: use SoftDeletes;
            $matiere->delete();

            return redirect()
                ->route('show.all.matiere')
                ->with('delete', '🗑️ تم حذف المادة بنجاح');

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()
                ->route('show.all.matiere')
                ->with('error', '⚠️ المادة غير موجودة');
        } catch (\Exception $e) {
            // ⚠️ تحقق من Foreign Key constraints قبل الحذف
            if (str_contains($e->getMessage(), 'foreign key constraint fails')) {
                return back()->with('error', '❌ لا يمكن حذف المادة لأنها مستخدمة في بيانات أخرى');
            }

            Log::error('4ayab: Failed to delete matiere - ' . $e->getMessage());

            return back()->with('error', '❌ حدث خطأ أثناء الحذف');
        }
    }
}
