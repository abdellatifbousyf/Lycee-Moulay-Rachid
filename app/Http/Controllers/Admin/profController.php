<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Enseignant;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Illuminate\Validation\Rule;

class ProfController extends Controller
{
    /**
     * Show the form to add a new professor (4ayab project).
     */
    public function addProf(): View
    {
        return view('admin.prof.addProf');
    }

    /**
     * Display all professors with pagination.
     */
    public function showAllProf(): View
    {
        // ✅ استخدام pagination لأداء أفضل + eager loading
        $profs = Enseignant::with('user') // افترضنا أن العلاقة هي user()
            ->latest()
            ->paginate(15);

        return view('admin.prof.showAll', compact('profs'));
    }

    /**
     * Store a newly created professor and associated user.
     * ✅ استخدام Transaction لضمان سلامة البيانات
     */
    public function save(Request $request): RedirectResponse
    {
        // ✅ 1. التحقق من البيانات بدقة
        $validated = $request->validate([
            'email'     => 'required|email|unique:users,email|max:255',
            'password'  => 'required|min:8|confirmed', // confirmed يعني password_confirmation
            'nom'       => 'required|string|max:255',
            'prenom'    => 'required|string|max:255',
            'adresse'   => 'required|string|max:500',
            'tel'       => 'required|numeric|digits:10',
        ], [
            // ✅ رسائل خطأ مخصصة بالعربية
            'email.unique' => 'هذا البريد الإلكتروني مستخدم مسبقاً',
            'password.min' => 'كلمة المرور يجب أن تكون 8 أحرف على الأقل',
            'tel.digits'   => 'رقم الهاتف يجب أن يتكون من 10 أرقام',
            'password.confirmed' => 'كلمتا المرور غير متطابقتين',
        ]);

        try {
            // ✅ 2. استخدام Transaction: إما كلشي ينجح، أو والو ما يتسجلش
            DB::transaction(function () use ($validated, $request) {

                // 📦 إنشاء حساب المستخدم أولاً
                $user = User::create([
                    'name'      => $validated['prenom'] . ' ' . $validated['nom'], // اسم كامل أفضل
                    'email'     => $validated['email'],
                    'password'  => Hash::make($validated['password']),
                    'id_role'   => 3, // ✅ يفضل استخدام Enum أو Constant: Role::PROFESSOR
                    'created_by'=> auth()->id(), // ✅ تتبع من أنشأ الحساب
                ]);

                // 📦 إنشاء ملف الأستاذ وربطه بالمستخدم
                Enseignant::create([
                    'nom_ens'     => $validated['nom'],
                    'prenom_ens'  => $validated['prenom'],
                    'adresse_ens' => $validated['adresse'],
                    'phone_ens'   => $validated['tel'],
                    'id_user'     => $user->id, // ✅ الربط التلقائي
                    'created_by'  => auth()->id(),
                ]);
            });

            return redirect()
                ->route('show.all.prof')
                ->with('success', '✅ تم إضافة الأستاذ بنجاح');

        } catch (\Illuminate\Database\QueryException $e) {
            // ✅ التعامل مع أخطاء قواعد البيانات (مثل unique constraint)
            if (str_contains($e->getMessage(), 'Duplicate entry')) {
                return back()
                    ->withInput()
                    ->with('error', '❌ البريد الإلكتروني أو البيانات مستخدمة مسبقاً');
            }
            Log::error('4ayab: DB error while adding prof - ' . $e->getMessage());
            return back()->withInput()->with('error', '❌ خطأ في قاعدة البيانات');

        } catch (\Exception $e) {
            // ✅ تسجيل أي خطأ آخر
            Log::error('4ayab: Failed to add professor - ' . $e->getMessage(), [
                'user_id' => auth()->id(),
                'payload' => $request->except('password', 'password_confirmation'),
            ]);

            return back()
                ->withInput()
                ->with('error', '❌ حدث خطأ أثناء الحفظ، حاول مرة أخرى');
        }
    }

    /**
     * Show the form for editing the specified professor.
     */
    public function editProf(int $id): View|RedirectResponse
    {
        $prof = Enseignant::with('user')->find($id);

        if (!$prof) {
            return redirect()
                ->route('show.all.prof')
                ->with('error', '⚠️ الأستاذ غير موجود');
        }

        return view('admin.prof.update', compact('prof'));
    }

    /**
     * Update the specified professor (without changing password by default).
     */
    public function updateprof(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'id'        => 'required|integer|exists:enseignants,id',
            'nom'       => 'required|string|max:255',
            'prenom'    => 'required|string|max:255',
            'adresse'   => 'required|string|max:500',
            'tel'       => 'required|numeric|digits:10',
            // ✅ تحديث كلمة المرور اختياري
            'password'  => 'nullable|min:8|confirmed',
            'email'     => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore(
                    Enseignant::findOrFail($request->id)->user->id
                ),
            ],
        ]);

        try {
            $prof = Enseignant::with('user')->findOrFail($validated['id']);

            DB::transaction(function () use ($validated, $prof) {

                // 🔄 تحديث بيانات المستخدم المرتبط
                $userData = [
                    'name'  => $validated['prenom'] . ' ' . $validated['nom'],
                    'email' => $validated['email'],
                ];

                // 🔐 تحديث كلمة المرور فقط إذا أدخلها المستخدم
                if (!empty($validated['password'])) {
                    $userData['password'] = Hash::make($validated['password']);
                }

                $prof->user->update($userData);

                // 🔄 تحديث بيانات الأستاذ
                $prof->update([
                    'nom_ens'     => $validated['nom'],
                    'prenom_ens'  => $validated['prenom'],
                    'adresse_ens' => $validated['adresse'],
                    'phone_ens'   => $validated['tel'],
                ]);
            });

            return redirect()
                ->route('show.all.prof')
                ->with('success', '✅ تم تعديل بيانات الأستاذ بنجاح');

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()
                ->route('show.all.prof')
                ->with('error', '⚠️ الأستاذ غير موجود');

        } catch (\Exception $e) {
            Log::error('4ayab: Failed to update professor - ' . $e->getMessage());

            return back()
                ->withInput()
                ->with('error', '❌ فشل التعديل، تأكد من البيانات');
        }
    }

    /**
     * Remove the specified professor (Soft Delete recommended).
     */
    public function deleteprof(int $id): RedirectResponse
    {
        try {
            $prof = Enseignant::with('user')->findOrFail($id);

            // ✅ الخيار 1: حذف نهائي (احذر: قد يكسر العلاقات)
            // $prof->user?->delete(); // حذف المستخدم أولاً
            // $prof->delete();

            // ✅ الخيار 2 (موصى به): Soft Delete
            // تأكد أن الـ Models فيها: use SoftDeletes;
            $prof->delete(); // إذا كانت SoftDeletes مفعلة

            return redirect()
                ->route('show.all.prof')
                ->with('success', '🗑️ تم حذف الأستاذ بنجاح');

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()
                ->route('show.all.prof')
                ->with('error', '⚠️ الأستاذ غير موجود');

        } catch (\Exception $e) {
            // ⚠️ التحقق من أخطاء Foreign Key
            if (str_contains($e->getMessage(), 'foreign key constraint fails')) {
                return back()->with('error', '❌ لا يمكن حذف الأستاذ لأنه مرتبط ببيانات أخرى (مواد، غياب، إلخ)');
            }

            Log::error('4ayab: Failed to delete professor - ' . $e->getMessage());

            return back()->with('error', '❌ حدث خطأ أثناء الحذف');
        }
    }
}
