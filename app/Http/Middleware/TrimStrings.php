<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\TrimStrings as Middleware;
use Illuminate\Http\Request;

class TrimStrings extends Middleware
{
    /**
     * The names of the attributes that should not be trimmed.
     *
     * ✅ Laravel 11+: إضافة نوع البيانات `array` مطلوب لـ PHP 8.3+
     *
     * @var array<int, string>
     */
    protected array $except = [
        // ✅ كلمات المرور (ما خاصهاش تتقلم)
        'password',
        'password_confirmation',
        'current_password',     // ✅ لـ تغيير الباسوورد

        // ✅ توكنات الـ API (إذا كتخدم بـ Sanctum/Passport)
        'api_token',
        'access_token',
        'refresh_token',

        // ✅ مشروع 4ayab: حقول خاصة ما خاصهاش تتقلم
        'cne',                  // ✅ رقم التسجيل الوطني (ممكن يبدا بـ 0)
        'code_massar',          // ✅ كود مسار (ممكن يحتوي رموز)
        'justification',        // ✅ مبرر الغياب (ممكن يحتوي مسافات مقصودة)

        // ✅ أي حقل فيه تنسيق خاص (JSON, Base64, إلخ)
        'signature',
        'encrypted_data',
    ];

    /**
     * ✅ (اختياري) تخصيص معالجة القيم قبل التقليم
     * مفيدة إذا بغيتي تدير منطق ديناميكي
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $key
     * @param  mixed  $value
     * @return mixed
     */
    // protected function transformValue(Request $request, string $key, mixed $value): mixed
    // {
    //     // مثال: ما تقلمش القيم إذا كانت من نوع معين
    //     // if ($key === 'justification' && is_string($value)) {
    //     //     return trim($value); // تقليم عادي
    //     // }
    //
    //     // للباقي: خلي للـ Parent يتكلف
    //     return parent::transformValue($request, $key, $value);
    // }
}
