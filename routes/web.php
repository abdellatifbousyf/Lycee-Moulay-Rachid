<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// ✅ Controllers Imports
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Prof\ProfController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\EtudiantController;

/*
|--------------------------------------------------------------------------
| Web Routes -
|--------------------------------------------------------------------------
*/

// ============================================================================
// 🌐 Routes عامة (بدون مصادقة)
// ============================================================================

// 🏠 الصفحة الرئيسية
Route::get('/', function () {
    return view('welcome');
})->name('welcome');

// 🔐 مسارات المصادقة (Laravel UI)
Auth::routes();

// 🏠 لوحة التحكم الافتراضية (لأي مستخدم)
Route::get('/home', [HomeController::class, 'index'])->name('home');

// 🔍 بحث عام (اختياري)
Route::get('/search', function (\Illuminate\Http\Request $request) {
    return back();
})->name('search');

// 📞 صفحة الاتصال
Route::get('/contact', function () {
    return back()->with('info', 'Page de contact en cours de développement.');
})->name('contact');

// ❌ صفحة 404 مخصصة
Route::fallback(function () {
    return response()->view('errors.404', [], 404);
});

// ============================================================================
// 👨‍💼 مساحة الأدمن (محمية بـ auth + role:admin)
// ============================================================================
Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

    // 📊 لوحة تحكم الأدمن
    Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');

    // 👥 إدارة الطلاب (CRUD كامل)
    Route::prefix('students')->name('students.')->controller(EtudiantController::class)->group(function () {
        Route::get('/', 'showAllStudent')->name('show.all');
        Route::get('/create', 'addStudent')->name('add.form');
        Route::post('/store', 'saveStudent')->name('save');
        Route::get('/{id}/edit', 'editStudent')->name('edit.form');
        Route::put('/{id}/update', 'updateStudent')->name('update');
        Route::delete('/{id}/delete', 'deleteStudent')->name('delete');
        // Route::get('/export', 'exportStudents')->name('export'); // اختياري
    });

    // 📚 إدارة الشعب (مثال - إذا كاين فيليير كونتروور)
    // Route::resource('filieres', \App\Http\Controllers\Admin\FiliereController::class);

    // ⚙️ إعدادات الأدمن
    Route::view('/settings', 'admin.settings')->name('settings');
});

// ============================================================================
// 👨‍🏫 مساحة الأستاذ (محمية بـ auth + role:prof)
// ============================================================================
Route::middleware(['auth', 'role:prof'])
    ->prefix('prof')
    ->name('prof.')
    ->controller(ProfController::class)
    ->group(function () {

    // 🏠 الصفحة الرئيسية للأستاذ
    Route::get('/', 'index')->name('home');
    Route::get('/dashboard', 'index')->name('dashboard');

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

// ============================================================================
// 👨‍🎓 مساحة الطالب (محمية بـ auth + role:etudiant)
// ============================================================================
Route::middleware(['auth', 'role:etudiant'])
    ->prefix('etudiant')
    ->name('etudiant.')
    ->group(function () {

    // 🏠 الصفحة الرئيسية للطالب
    Route::view('/espace', 'etudiant.espace-etudiant')->name('espace');
    Route::view('/dashboard', 'etudiant.dashboard')->name('dashboard');

    // 📋 غياباتي
    Route::get('/absences', [EtudiantController::class, 'absences'])->name('absences');

    // 📊 نقاطي
    Route::get('/notes', [EtudiantController::class, 'notes'])->name('notes');

    // 📅 جدولي
    Route::get('/emploi', [EtudiantController::class, 'emploi'])->name('emploi');

    // 👤 ملفي
    Route::view('/profile', 'etudiant.profile')->name('profile');
});

// ============================================================================
// 🔄 Routes للـ Redirect (اختياري - لتوجيه المستخدمين حسب الدور)
// ============================================================================
Route::middleware(['auth'])->group(function () {
    Route::get('/redirect', function () {
        $role = auth()->user()->role ?? 'etudiant';

        return match($role) {
            'admin' => redirect()->route('admin.dashboard'),
            'prof' => redirect()->route('prof.home'),
            'etudiant' => redirect()->route('etudiant.espace'),
            default => redirect()->route('home'),
        };
    })->name('redirect');
});
