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
    // =========================================================================
    // 🔹 قسم الأدمن: إدارة الطلاب (CRUD)
    // =========================================================================

    /**
     * Show the form to add a new student (4ayab project).
     * Route: admin.students.add.form
     */
    public function addStudent(): View
    {
        $filieres = Filiere::select('id', 'nom_filiere')->get();

        return view('admin.etudiant.addStudent', compact('filieres'));
    }

    /**
     * Display all students with their filiere.
     * Route: admin.students.show.all
     */
    public function showAllStudent(): View
    {
        // ✅ استخدام eager loading لتحسين الأداء
        $students = Etudiant::with('filiere')->latest()->paginate(15);

        return view('admin.etudiant.showAll', compact('students'));
    }

    /**
     * Store a newly created student in database.
     * Route: admin.students.save (POST)
     */
    public function saveStudent(Request $request): RedirectResponse
    {
        // ✅ 1. التحقق من البيانات
        $validated = $request->validate([
            'nom'      => 'required|string|max:255',
            'prenom'   => 'required|string|max:255',
            'cne'      => 'required|string|unique:etudiants,cne|max:20',
            'phone'    => 'required|numeric|digits:10',
            'filiere'  => 'required|exists:fileres,id',
        ], [
            'cne.unique' => 'هذا CNE مسجل مسبقاً',
            'phone.digits' => 'رقم الهاتف يجب أن يتكون من 10 أرقام',
        ]);

        try {
            // ✅ 2. إنشاء الطالب
            Etudiant::create([
                'cne'        => $validated['cne'],
                'nom_etu'    => $validated['nom'],
                'prenom_etu' => $validated['prenom'],
                'phone_etu'  => $validated['phone'],
                'id_filiere' => $validated['filiere'],
                'id_user'    => auth()->id(),
            ]);

            // ✅ ✅ ✅ Route مصحح (مهم جداً!)
            return redirect()
                ->route('admin.students.show.all')
                ->with('success', '✅ تم إضافة التلميذ بنجاح');

        } catch (\Exception $e) {
            // ✅ 3. تسجيل الخطأ فـ الـ Log
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
     * Route: admin.students.edit.form
     */
    public function editStudent(int $id): View|RedirectResponse
    {
        $etudiant = Etudiant::find($id);

        if (!$etudiant) {
            // ✅ ✅ ✅ Route مصحح
            return redirect()
                ->route('admin.students.show.all')
                ->with('error', '⚠️ التلميذ غير موجود');
        }

        $filieres = Filiere::select('id', 'nom_filiere')->get();

        return view('admin.etudiant.update', compact('filieres', 'etudiant'));
    }

    /**
     * Update the specified student in database.
     * Route: admin.students.update (PUT)
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
            ]);

            // ✅ ✅ ✅ Route مصحح
            return redirect()
                ->route('admin.students.show.all')
                ->with('update', '✅ تم تعديل بيانات التلميذ بنجاح');

        } catch (\Exception $e) {
            Log::error('4ayab: Failed to update student - ' . $e->getMessage());

            return back()
                ->withInput()
                ->with('error', '❌ فشل التعديل، تأكد من البيانات');
        }
    }

    /**
     * Remove the specified student from database.
     * Route: admin.students.delete (DELETE)
     */
    public function deleteStudent(int $id): RedirectResponse
    {
        try {
            $student = Etudiant::findOrFail($id);

            // ✅ الحذف (يمكن تفعيل SoftDeletes إذا بغيتي)
            $student->delete();

            // ✅ ✅ ✅ Route مصحح
            return redirect()
                ->route('admin.students.show.all')
                ->with('delete', '🗑️ تم حذف التلميذ بنجاح');

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            // ✅ ✅ ✅ Route مصحح
            return redirect()
                ->route('admin.students.show.all')
                ->with('error', '⚠️ التلميذ غير موجود');
        } catch (\Exception $e) {
            Log::error('4ayab: Failed to delete student - ' . $e->getMessage());

            return back()->with('error', '❌ حدث خطأ أثناء الحذف');
        }
    }

    // =========================================================================
    // 🔹 قسم الطالب: واجهة العرض (للتلاميذ)
    // =========================================================================

    /**
     * عرض صفحة الغيابات للطالب الحالي
     * Route: etudiant.absences
     */
    public function absences(): View
    {
        $user = auth()->user();

        // ✅ جلب الغيابات (عدّل حسب الموديل والجداول عندك)
        // إذا ماكاينش موديل Absence بعد، خلي: $absences = collect();
        $absences = class_exists(\App\Models\Absence::class)
            ? \App\Models\Absence::where('etudiant_id', $user->id)
                ->with('seance')
                ->latest()
                ->paginate(10)
            : collect();

        return view('etudiant.absences', compact('absences'));
    }

    /**
     * عرض صفحة النقاط للطالب الحالي
     * Route: etudiant.notes
     */
    public function notes(): View
    {
        $user = auth()->user();

        // ✅ جلب النقاط (عدّل حسب الموديل عندك)
        // إذا ماكاينش موديل Note بعد، خلي: $notes = collect();
        $notes = class_exists(\App\Models\Note::class)
            ? \App\Models\Note::where('etudiant_id', $user->id)
                ->with('matiere', 'evaluation')
                ->latest()
                ->get()
            : collect();

        return view('etudiant.notes', compact('notes'));
    }

    /**
     * عرض جدول الحصص للطالب الحالي
     * Route: etudiant.emploi
     */
    public function emploi(): View
    {
        $user = auth()->user();
        $classe = $user->classe ?? null;

        // ✅ جلب الجدول (عدّل حسب الموديل عندك)
        // إذا ماكاينش موديل Emploi بعد، خلي: $emploi = collect();
        $emploi = ($classe && class_exists(\App\Models\Emploi::class))
            ? \App\Models\Emploi::where('classe_id', $classe->id)
                ->with('matiere', 'professeur', 'salle')
                ->orderBy('jour')
                ->orderBy('heure_debut')
                ->get()
            : collect();

        return view('etudiant.emploi', compact('emploi', 'classe'));
    }
}
