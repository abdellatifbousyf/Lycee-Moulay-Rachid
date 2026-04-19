<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Log;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a simple trait to include the behavior. You're free to explore
    | this trait and override any functions that require customization.
    |
    */

    use SendsPasswordResetEmails;

    /**
     * ✅ (اختياري) تخصيص الـ Broker إذا عندك أكثر من نوع مستخدم
     * فـ مشروع 4ayab، ممكن يكون عندك أدمن، أستاذ، تلميذ...
     *
     * @return string
     */
    // public function broker(): string
    // {
    //     return Password::ADMIN_BROKER; // أو 'professors', 'students'...
    // }

    /**
     * ✅ (اختياري) تخصيص الرسالة بعد إرسال الإيميل بنجاح
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $status
     * @return \Illuminate\Http\Response
     */
    // protected function sendResetLinkResponse(Request $request, string $status): \Illuminate\Http\Response
    // {
    //     Log::info('4ayab: Password reset link sent to ' . $request->email);
    //
    //     return back()->with('status', '✅ تم إرسال رابط إعادة تعيين كلمة المرور إلى بريدك الإلكتروني');
    // }

    /**
     * ✅ (اختياري) تخصيص رسالة الخطأ عند فشل الإرسال
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $status
     * @return \Illuminate\Http\Response
     */
    // protected function sendResetLinkFailedResponse(Request $request, string $status): \Illuminate\Http\Response
    // {
    //     Log::warning('4ayab: Failed to send reset link - ' . $request->email);
    //
    //     return back()
    //         ->withInput()
    //         ->withErrors(['email' => '❌ لم نتمكن من العثور على حساب بهذا البريد الإلكتروني']);
    // }

    /**
     * ✅ (اختياري) التحقق الإضافي قبل إرسال الرابط
     * مثال: منع إرسال الرابط أكثر من 3 مرات فـ الساعة
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response|null
     */
    // protected function validateEmail(Request $request): void
    // {
    //     $request->validate([
    //         'email' => [
    //             'required',
    //             'email',
    //             'exists:users,email', // ✅ تأكد أن الإيميل مسجل
    //             // 👇 تحقق من Rate Limit (اختياري)
    //             // 'max:3,60' // 3 محاولات فـ 60 دقيقة
    //         ],
    //     ], [
    //         'email.exists' => 'هذا البريد غير مسجل فـ نظام 4ayab',
    //     ]);
    // }
}
