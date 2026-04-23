<?php

namespace App\Http\Middleware;

use Illuminate\Cookie\Middleware\EncryptCookies as Middleware;
use Illuminate\Http\Request;

class EncryptCookies extends Middleware
{
    /**
     * The names of the cookies that should not be encrypted.
     *
     * ✅ Laravel 11+: إضافة نوع البيانات `array` مطلوب لـ PHP 8.3+
     *
     * @var array<int, string>
     */
    protected array $except = [
        // ✅ أمثلة لـ كوكيز ما خاصهاش تتشفر (اختياري):

        // 🔗 كوكيز الـ Analytics (Google, Facebook...)
        // '_ga', '_gid', '_fbp',

        // 🎯 مشروع 4ayab: كوكيز التتبع الخارجي
        // 'analytics_consent', 'cookie_policy',

        // ⚠️ تحذير: ما تحيدش التشفير على كوكيز حساسة مثل:
        // - 'laravel_session' ← جلسة المستخدم (ضروري يتشفر)
        // - 'XSRF-TOKEN' ← حماية CSRF (ضروري يتشفر)
        // - أي كوكي فيه بيانات شخصية أو صلاحيات
    ];

    /**
     * ✅ (اختياري) تخصيص معالجة الكوكيز قبل التشفير
     * مفيدة إذا بغيتي تدير منطق ديناميكي
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Symfony\Component\HttpFoundation\Cookie  $cookie
     * @return \Symfony\Component\HttpFoundation\Cookie|null
     */
    // protected function handleUnencryptedCookie(Request $request, \Symfony\Component\HttpFoundation\Cookie $cookie): ?\Symfony\Component\HttpFoundation\Cookie
    // {
    //     // مثال: منع تشفير كوكي محدد بناءً على شرط
    //     // if ($cookie->getName() === 'analytics_consent') {
    //     //     return $cookie; // ما يتشفرش
    //     // }
    //
    //     // للباقي: خلي للـ Parent يتكلف
    //     return parent::handleUnencryptedCookie($request, $cookie);
    // }
}
