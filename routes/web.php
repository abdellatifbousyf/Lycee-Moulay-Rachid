<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// ✅ استيراد الـ Controllers (الطريقة الحديثة فـ Laravel 11+)
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Prof\ProfController;
//////////////
use App\Http\Controllers\etudiantController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// ✅ الصفحة الرئيسية
Route::get('/', function () {
    return view('welcome');
})->name('welcome');

// ✅ مسارات المصادقة (تتطلب تثبيت laravel/ui)
Auth::routes();

// ✅ لوحة التحكم الافتراضية
Route::get('/home', [HomeController::class, 'index'])->name('home');

// ✅ مسارات الطالب (محمية بـ auth)
Route::middleware(['auth'])->prefix('etudiant')->name('etudiant.')->group(function () {
    Route::view('/espace', 'etudiant.espace-etudiant')->name('espace');
    // 👉 زيد هنا باقي روابط الطالب مستقبلاً
});

// ✅ مسارات الأدمن (محمية بـ auth + middleware مخصص يلا بغيتي)
Route::middleware(['auth'])->prefix('administration')->name('admin.')->group(function () {
    Route::view('/dashboard', 'administration.administration')->name('dashboard');
    // 👉 زيد هنا باقي روابط الأدمن مستقبلاً
});

// ✅ مسارات الأستاذ (Espace Prof)
Route::middleware(['auth'])
    ->prefix('prof')
    ->name('prof.')
    ->controller(ProfController::class)
    ->group(function () {

        // 👨‍🏫 الصفحة الرئيسية للأستاذ
        Route::get('/', 'index')->name('home');

        // 📅 إدارة الحصص (Seances)
        Route::get('/seance/create', 'createSeance')->name('seance.create');
        Route::post('/seance/store', 'saveSeance')->name('seance.store');
        Route::get('/seances', 'listSeance')->name('seance.index');

        // 📝 تسجيل الغياب
        Route::get('/seance/{seance}/absences', 'pageNoteAbsence')->name('seance.absences');
        Route::post('/absences/store', 'saveAbsence')->name('absences.store');

        // 📊 تاريخ الغياب
        Route::get('/absences/history', 'historiqueAbsence')->name('absences.history');
    });

// ✅ Route للـ 404 (اختياري)
Route::fallback(function () {
    return response()->view('errors.404', [], 404);
});
// ✅ Route لصفحة الاتصال (مؤقتة)
Route::get('/contact', function () {
    return back()->with('info', 'Page de contact en cours de développement.');
})->name('contact');
//////////////////////////////////////

// ✅ روطات الطالب
Route::middleware(['auth'])->prefix('etudiant')->name('etudiant.')->group(function () {

    Route::get('/absences', [etudiantController::class, 'absences'])
        ->name('absences');

    Route::get('/notes', [etudiantController::class, 'notes'])
        ->name('notes');

    Route::get('/emploi', [etudiantController::class, 'emploi'])
        ->name('emploi');

});
