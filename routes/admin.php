<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\ProfController;
use App\Http\Controllers\Admin\EtudiantController;
use App\Http\Controllers\Admin\MatiereController;

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
| All routes are protected by 'auth' middleware
*/

Route::middleware(['auth'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        // 👨‍💼 Dashboard Admin
        Route::get('/', [AdminController::class, 'index'])->name('dashboard');

        // 👨‍🏫 Gestion des Enseignants (Professeurs)
        Route::prefix('teachers')
            ->name('teachers.')
            ->controller(ProfController::class)
            ->group(function () {
                Route::get('/create', 'addProf')->name('create');
                Route::get('/', 'showAllProf')->name('index');
                Route::post('/', 'save')->name('store');
                Route::get('/{id}/edit', 'editprof')->name('edit');
                Route::put('/{id}', 'updateprof')->name('update');
                Route::delete('/{id}', 'deleteprof')->name('destroy'); // ✅ DELETE method
            });

        // 👨‍🎓 Gestion des Étudiants
        Route::prefix('students')
            ->name('students.')
            ->controller(EtudiantController::class)
            ->group(function () {
                Route::get('/create', 'addStudent')->name('create');
                Route::get('/', 'showAllStudent')->name('index');
                Route::post('/', 'saveStudent')->name('store');
                Route::get('/{id}/edit', 'editStudent')->name('edit');
                Route::put('/{id}', 'updateStudent')->name('update'); // ✅ PUT method
                Route::delete('/{id}', 'deleteStudent')->name('destroy'); // ✅ DELETE method
            });

        // 📚 Gestion des Matières
        Route::prefix('matieres')
            ->name('matieres.')
            ->controller(MatiereController::class)
            ->group(function () {
                Route::get('/create', 'addMatiere')->name('create');
                Route::get('/', 'showAllMatiere')->name('index');
                Route::post('/', 'saveMatiere')->name('store');
                Route::get('/{id}/edit', 'editMatiere')->name('edit');
                Route::put('/{id}', 'updateMatiere')->name('update'); // ✅ PUT method
                Route::delete('/{id}', 'deleteMatiere')->name('destroy'); // ✅ DELETE method
            });
    });
