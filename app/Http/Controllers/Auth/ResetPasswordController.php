<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;

class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    /**
     * Where to redirect users after resetting their password.
     * ✅ Laravel 13: استخدم مسار مباشر بدلاً من RouteServiceProvider::HOME (المحذوف)
     *
     * @var string
     */
    protected string $redirectTo = '/login?reset=success';
    // أو حسب مشروع 4ayab:
    // protected string $redirectTo = '/admin'; // للأدمن
    // أو استخدم دالة redirectPath() أدناه للتوجيه الديناميكي

    /**
     * ✅ (مهم) التوجيه الديناميكي بعد إعادة التعيين حسب دور المستخدم (4ayab project)
     *
     * @return string
     */
    // public function redirectPath(): string
    // {
    //     if (auth()->check()) {
    //         return match(auth()->user()->id_role) {
    //             1 => '/admin',
    //             2 => '/Administration',
    //             3 => '/Prof',
    //             4 => '/Etudiant',
    //             default => '/dashboard',
    //         };
    //     }
    //     return '/login';
    // }

    /**
     * ✅ (اختياري) تخصيص رسالة النجاح بعد إعادة التعيين
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $user
     * @return \Illuminate\Http\RedirectResponse
     */
    // protected function sendResetResponse(Request $request, string $response): \Illuminate\Http\RedirectResponse
    // {
    //     Log::info('4ayab: Password reset successful for ' . $request->email);
    //
    //     return redirect($this->redirectPath())
    //         ->with('status', '✅ تم تغيير كلمة المرور بنجاح، يمكنك تسجيل الدخول الآن');
    // }

    /**
     * ✅ (اختياري) تخصيص رسالة الخطأ عند فشل إعادة التعيين
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $response
     * @return \Illuminate\Http\RedirectResponse
     */
    // protected function sendResetFailedResponse(Request $request, string $response): \Illuminate\Http\RedirectResponse
    // {
    //     Log::warning('4ayab: Password reset failed - ' . $response . ' for ' . $request->email);
    //
    //     return back()
    //         ->withInput()
    //         ->withErrors(['email' => '❌ فشل إعادة تعيين كلمة المرور، حاول مرة أخرى أو تحقق من الرابط']);
    // }

    /**
     * ✅ (اختياري) التحقق الإضافي قبل إعادة التعيين
     * مثال: التأكد أن التوكن غير منتهي الصلاحية
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  array<string, mixed>  $credentials
     * @return void
     */
    // protected function validateReset(Request $request, array $credentials): void
    // {
    //     $request->validate([
    //         'token' => 'required',
    //         'email' => 'required|email',
    //         'password' => 'required|confirmed|min:8',
    //     ], [
    //         'password.confirmed' => 'كلمتا المرور غير متطابقتين',
    //         'password.min' => 'كلمة المرور يجب أن تكون 8 أحرف على الأقل',
    //     ]);
    // }

    /**
     * ✅ (اختياري) تنفيذ كود إضافي بعد نجاح إعادة التعيين
     * مثال: تسجيل الخروج من الأجهزة الأخرى، إرسال إشعار...
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @param  string  $password
     * @return void
     */
    // protected function resetPassword($user, string $password): void
    // {
    //     // تسجيل الحدث لأغراض الأمان
    //     event(new PasswordReset($user));
    //
    //     Log::info('4ayab: Password changed for user ' . $user->email, [
    //         'user_id' => $user->id,
    //         'ip' => request()->ip(),
    //         'user_agent' => request()->userAgent(),
    //     ]);
    //
    //     // ✅ تنفيذ إعادة التعيين الفعلية
    //     $this->guard()->login($user); // تسجيل الدخول تلقائياً (اختياري)
    //     $user->setPassword($password); // أو: $user->password = Hash::make($password);
    //     $user->save();
    //
    //     // ✅ (مهم للأمان) إبطال جلسات المستخدم الأخرى إذا رغبت
    //     // $user->tokens()->delete(); // إذا كنت تستعمل Sanctum
    // }
}
