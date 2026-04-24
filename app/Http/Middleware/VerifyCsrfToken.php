<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;
use Illuminate\Http\Request;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * ✅ Laravel 11+: إضافة نوع البيانات `array` مطلوب لـ PHP 8.3+
     * ⚠️ تحذير: ما تحطش '*' هنا إلا إذا كنت فاهم المخاطر!
     *
     * @var array<int, string>
     */
    protected  $except = [
        // ✅ آمنة للإستثناء (اختياري - حسب الحاجة):

        // 🔗 API Routes (إذا كنت كتستعمل توكنات أو JWT)
        'api/*',

        // 🔗 Webhooks الخارجية (خدمات الدفع، المزامنة...)
        'webhook/*',
        'stripe/*',
        'paypal/*',

        // 🎯 مشروع 4ayab: مزامنة الغياب الأوتوماتيكية
        'absence/webhook/sync',
        'api/sync/*',

        // ⚠️ للتطوير فقط (احذفها فـ الإنتاج!)
        // 'debug/*',
    ];

    /**
     * ✅ (اختياري) تخصيص معالجة توكن الـ CSRF
     * مفيدة إذا بغيتي منطق ديناميكي للتحقق
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    // protected function tokensMatch(Request $request): bool
    // {
    //     // مثال: تجاوز التحقق لـ Requests من IP موثوق
    //     // if ($request->ip() === '127.0.0.1' && config('app.env') === 'local') {
    //     //     return true;
    //     // }
    //
    //     return parent::tokensMatch($request);
    // }

    /**
     * ✅ (اختياري) تخصيص رسالة الخطأ عند فشل التحقق
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    // protected function onFailure(Request $request): \Symfony\Component\HttpFoundation\Response
    // {
    //     \Log::warning('4ayab: CSRF token mismatch', [
    //         'ip' => $request->ip(),
    //         'path' => $request->path(),
    //         'method' => $request->method(),
    //     ]);
    //
    //     if ($request->expectsJson()) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'خطأ في التحقق من الأمان (CSRF)',
    //             'code' => 419,
    //         ], 419);
    //     }
    //
    //     return abort(419, 'صفحة منتهية الصلاحية، يرجى إعادة المحاولة');
    // }
}
