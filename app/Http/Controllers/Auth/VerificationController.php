<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\VerifiesEmails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class VerificationController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Email Verification Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling email verification for any
    | user that recently registered with the application. Emails may also
    | be re-sent if the user didn't receive the original email message.
    |
    */

    use VerifiesEmails;

    /**
     * Where to redirect users after verification.
     * ✅ Laravel 13: استخدم مسار مباشر بدلاً من RouteServiceProvider::HOME (المحذوف)
     *
     * @var string
     */
    protected string $redirectTo = '/dashboard?verified=1';

    // ✅ حسب مشروع 4ayab، يمكنك التوجيه حسب الدور:
    // protected string $redirectTo = '/login?verified=1'; // يعود للأدمن ليضيف المزيد
    // أو استخدم دالة redirectPath() أدناه للتوجيه الديناميكي

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // ✅ حماية صفحات التحقق من المستخدمين غير المصادق عليهم
        $this->middleware('auth');

        // ✅ التحقق من توقيع الرابط (لمنع التلاعب)
        $this->middleware('signed')->only('verify');

        // ✅ تحديد عدد المحاولات المسموحة (لمنع الـ Abuse)
        $this->middleware('throttle:6,1')->only('verify', 'resend');
    }

    /**
     * ✅ (مهم لـ 4ayab) التوجيه الديناميكي بعد التحقق حسب دور المستخدم
     *
     * @return string
     */
    // public function redirectPath(): string
    // {
    //     if (auth()->check()) {
    //         return match(auth()->user()->id_role) {
    //             1 => '/admin?verified=1',           // 👑 Admin
    //             2 => '/Administration?verified=1',  // 👨‍💼 Manager
    //             3 => '/Prof?verified=1',            // 👨‍🏫 Professor
    //             4 => '/Etudiant?verified=1',        // 🎓 Student
    //             default => '/dashboard?verified=1', // 🔀 Fallback
    //         };
    //     }
    //     return '/login';
    // }

    /**
     * ✅ (اختياري) تخصيص رسالة النجاح بعد التحقق
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    // protected function verified(Request $request): \Illuminate\Http\RedirectResponse
    // {
    //     $user = $request->user();
    //
    //     // 📝 تسجيل الحدث لأغراض الأمان والتتبع
    //     Log::info('4ayab: Email verified successfully', [
    //         'user_id' => $user->id,
    //         'email' => $user->email,
    //         'role' => $user->id_role,
    //         'ip' => $request->ip(),
    //     ]);
    //
    //     // 🎉 إطلاق حدث التحقق (مهم لـ الـ Listeners)
    //     event(new Verified($user));
    //
    //     return redirect($this->redirectPath())
    //         ->with('success', '✅ تم تأكيد بريدك الإلكتروني بنجاح، يمكنك الآن استخدام جميع ميزات 4ayab');
    // }

    /**
     * ✅ (اختياري) تخصيص رسالة إعادة إرسال رابط التحقق
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    // protected function resend(Request $request)
    // {
    //     if ($request->user()->hasVerifiedEmail()) {
    //         return redirect($this->redirectPath())
    //             ->with('info', 'ℹ️ بريدك الإلكتروني مؤكد مسبقاً');
    //     }
    //
    //     // 📤 إعادة إرسال رابط التحقق
    //     $request->user()->sendEmailVerificationNotification();
    //
    //     Log::info('4ayab: Verification email resent', [
    //         'user_id' => $request->user()->id,
    //         'email' => $request->user()->email,
    //     ]);
    //
    //     return back()->with('status', '✅ تم إرسال رابط التأكيد الجديد إلى بريدك الإلكتروني');
    // }
}
