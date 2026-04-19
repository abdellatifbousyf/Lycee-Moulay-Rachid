<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ConfirmsPasswords;
use Illuminate\Http\Request;

class ConfirmPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Confirm Password Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password confirmations and
    | uses a simple trait to include the behavior. You're free to explore
    | this trait and override any functions that require customization.
    |
    */

    use ConfirmsPasswords;

    /**
     * Where to redirect users after password confirmation.
     * ✅ Laravel 13: استخدم اسم الـ Route أو مسار مباشر
     *
     * @var string
     */
    protected string $redirectTo = '/dashboard';
    // أو: protected string $redirectTo = '/admin'; حسب مشروع 4ayab
    // أو: يمكنك استخدام دالة:
    // protected function authenticated(Request $request, $user): string {
    //     return route('admin.dashboard');
    // }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // ✅ حماية جميع الميثودات بـ auth middleware
        $this->middleware('auth');
    }

    /**
     * ✅ (اختياري) تخصيص رسالة الخطأ عند فشل التأكيد
     * يمكنك إضافة هذه الدالة إذا بغيتي رسائل مخصصة بالعربية
     */
    // protected function failedToConfirmPassword(): \Illuminate\Http\Response
    // {
    //     return back()
    //         ->withInput()
    //         ->withErrors(['password' => 'كلمة المرور غير صحيحة، حاول مرة أخرى']);
    // }
}
