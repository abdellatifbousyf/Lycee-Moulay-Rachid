<?php

namespace App\Http\Middleware;

use Illuminate\Http\Middleware\TrustHosts as Middleware;

class TrustHosts extends Middleware
{
    /**
     * Get the host patterns that should be trusted.
     *
     * ✅ Laravel 11+: إضافة Return Type `array` مطلوب لـ PHP 8.3+
     *
     * @return array<int, string>
     */
    public function hosts(): array
    {
        return [
            // ✅ الدومين الرئيسي + جميع السوبدومينات (مثل: admin.4ayab.com, api.4ayab.com)
            $this->allSubdomainsOfApplicationUrl(),

            // ✅ إذا عندك CDN أو دومينات خارجية كتخدم مع المشروع
            // 'cdn.4ayab.com',
            // 'example-cdn.net',

            // ✅ للـ Localhost فـ بيئة التطوير (اختياري)
            // 'localhost',
            // '127.0.0.1',
            // '::1',
        ];
    }
}
