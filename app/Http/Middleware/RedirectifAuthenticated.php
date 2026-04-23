<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * ✅ Laravel 13: إضافة Type Hints لـ PHP 8.3+
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string|null  $guard
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        // ✅ التحقق من المصادقة لكل Guard محدد (أو الافتراضي)
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {

            if (Auth::guard($guard)->check()) {

                // ✅ Laravel 13: RouteServiceProvider::HOME محذوف، استخدم مسار مباشر أو دالة
                $redirectPath = $this->getRedirectPath($request->user());

                return redirect($redirectPath);
            }
        }

        return $next($request);
    }

    /**
     * ✅ (مهم لـ 4ayab) تحديد مسار التوجيه حسب دور المستخدم
     *
     * @param  \App\Models\User|null  $user
     * @return string
     */
    protected function getRedirectPath(?object $user): string
    {
        // ✅ إذا ماكاينش مستخدم، رجع للرئيسية
        if (!$user) {
            return '/';
        }

        // ✅ التوجيه الديناميكي حسب الدور (id_role) فـ مشروع 4ayab
        return match($user->id_role) {
            1 => '/admin',              // 👑 Admin
            2 => '/Administration',     // 👨‍💼 Manager/Supervisor
            3 => '/Prof',               // 👨‍🏫 Professor
            4 => '/Etudiant',           // 🎓 Student
            default => '/dashboard',    // 🔀 Fallback
        };
    }
}
