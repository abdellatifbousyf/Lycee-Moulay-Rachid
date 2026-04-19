<?php

use Illuminate\Support\Facades\Schedule;
use Illuminate\Support\Facades\Log;

/*
|--------------------------------------------------------------------------
| Console Routes (Laravel 13)
|--------------------------------------------------------------------------
|
| Here you may define all your scheduled tasks for the application.
| This replaces the old app/Console/Kernel.php schedule() method.
|
*/

// ─────────────────────────────────────────────────────────────
// 🎯 أمثلة أساسية (مستوحاة من مشروع 4ayab)
// ─────────────────────────────────────────────────────────────

// 1️⃣ إرسال تقرير الغياب اليومي كل يوم على 8:00 صباحاً
Schedule::command('absence:send-daily-report')
    ->dailyAt('08:00')
    ->timezone('Africa/Casablanca')
    ->withoutOverlapping()
    ->onSuccess(function () {
        Log::info('✅ 4ayab: Daily absence report sent successfully');
    })
    ->onFailure(function () {
        Log::error('❌ 4ayab: Failed to send daily absence report');
    });

// 2️⃣ تنظيف البيانات المؤقتة كل ساعة
Schedule::command('absence:cleanup-temp-data')
    ->hourly()
    ->environments(['production', 'staging']);

// 3️⃣ مزامنة الحضور مع قاعدة البيانات الخارجية كل 5 دقائق
Schedule::call(function () {
    // ✅ يمكنك وضع أي كود هنا مباشرة
    try {
        // مثال: مزامنة بيانات الغياب
        // \App\Jobs\SyncAbsenceData::dispatch();
        Log::info('🔄 4ayab: Sync job executed at ' . now());
    } catch (\Exception $e) {
        Log::error('🔥 4ayab: Sync failed - ' . $e->getMessage());
    }
})->everyFiveMinutes()->name('absence-sync')->onOneServer();

// 4️⃣ إرسال تذكير للأساتذة كل أحد على 18:00
Schedule::command('absence:notify-teachers')
    ->weeklyOn(7, '18:00')
    ->timezone('Africa/Casablanca');

// ─────────────────────────────────────────────────────────────
// 🧪 أوامر الاختبار (يمكن حذفها في الإنتاج)
// ─────────────────────────────────────────────────────────────

// Schedule::command('inspire')->hourly(); // مثال من Laravel
