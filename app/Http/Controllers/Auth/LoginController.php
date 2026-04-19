<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Default redirect path (fallback).
     * ✅ Laravel 13: استخدم نوع البيانات string
     */
    protected string $redirectTo = '/dashboard';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // ✅ حماية صفحات تسجيل الدخول من المستخدمين المصادق عليهم
        $this->middleware('guest')->except('logout');
    }

    /**
     * ✅ (مهم جداً) التعامل مع التوجيه بعد تسجيل الدخول حسب الدور (4ayab project)
     *
     * ⚠️ ملاحظة: لا تستخدم `redirectTo()` كدالة، بل استخدم `authenticated()`
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return string
     */
    protected function authenticated(Request $request, $user): string
    {
        // ✅ استخدام match() (PHP 8.0+) - أنظف وأسرع من switch
        return match($user->id_role) {
            1 => '/admin',              // 👑 Admin
            2 => '/Administration',     // 👨‍💼 Manager/Supervisor
            3 => '/Prof',               // 👨‍🏫 Professor
            4 => '/Etudiant',           // 🎓 Student
            default => '/dashboard',    // 🔀 Fallback
        };
    }

    /**
     * ✅ (اختياري) تحديد حقل تسجيل الدخول (إيميل أو اسم مستخدم)
     * إذا كنت كتسجل الدخول بـ الإيميل (موصى به):
     */
    public function username(): string
    {
        return 'email'; // أو 'name' إذا كنت كتخدم بـ اسم المستخدم
    }

    /**
     * ✅ (اختياري) تخصيص التحقق من تسجيل الدخول
     * مثال: إضافة تحقق من حالة الحساب (مفعل/غير مفعل)
     */
    // protected function validateLogin(Request $request): void
    // {
    //     $request->validate([
    //         $this->username() => 'required|string|email',
    //         'password' => 'required|string',
    //     ]);
    // }

    /**
     * ✅ (اختياري) تخصيص رسالة الخطأ عند فشل المصادقة
     */
    // protected function sendFailedLoginResponse(Request $request): never
    // {
    //     throw ValidationException::withMessages([
    //         $this->username() => '❌ البريد الإلكتروني أو كلمة المرور غير صحيحة',
    //     ]);
    // }

    /**
     * ✅ (اختياري) تسجيل محاولة الدخول (للأمان والتتبع)
     */
    // protected function attemptLogin(Request $request): bool
    // {
    //     \Log::info('4ayab: Login attempt for ' . $request->input($this->username()));
    //     return parent::attemptLogin($request);
    // }
}
