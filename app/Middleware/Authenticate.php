<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * ✅ Laravel 11+: إضافة Type Hints مطلوبة لـ Request و ?string
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo(Request $request): ?string
    {
        // ✅ إذا الطلب كيطلب JSON (API)، ماكيوجهش لشي صفحة
        // ✅ إذا طلب عادي (Browser)، كيتوجه لـ login
        return $request->expectsJson() ? null : route('login');
    }
}
