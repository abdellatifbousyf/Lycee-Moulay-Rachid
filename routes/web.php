<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\Prof\ProfController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\EtudiantController;

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


       // dmin
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');
});

// ✅ روتات Prof
Route::middleware(['auth', 'role:prof'])->prefix('prof')->name('prof.')->group(function () {
    Route::get('/dashboard', [ProfController::class, 'index'])->name('dashboard');
});
// ✅ روطات الأدمن
Route::get('/admin/students', [EtudiantController::class, 'showAllStudent'])->name('admin.students.show.all');
Route::get('/admin/students/create', [EtudiantController::class, 'addStudent'])->name('admin.students.add.form');
Route::post('/admin/students/store', [EtudiantController::class, 'saveStudent'])->name('admin.students.save');
Route::get('/admin/students/{id}/edit', [EtudiantController::class, 'editStudent'])->name('admin.students.edit.form');
Route::put('/admin/students/{id}/update', [EtudiantController::class, 'updateStudent'])->name('admin.students.update');
Route::delete('/admin/students/{id}/delete', [EtudiantController::class, 'deleteStudent'])->name('admin.students.delete');

// ✅ روطات الطالب
Route::get('/etudiant/absences', [EtudiantController::class, 'absences'])->name('etudiant.absences');
Route::get('/etudiant/notes', [EtudiantController::class, 'notes'])->name('etudiant.notes');
Route::get('/etudiant/emploi', [EtudiantController::class, 'emploi'])->name('etudiant.emploi');

});
