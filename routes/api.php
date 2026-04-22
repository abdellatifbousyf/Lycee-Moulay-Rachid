<?php

declare(strict_types=1);

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded via bootstrap/app.php (Laravel 11+) or RouteServiceProvider.
|
*/

// ✅ Route protégée par Sanctum (recommandé pour Laravel 10/11/12+)
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// ✅ Route publique pour tester l'API
Route::get('/ping', function () {
    return response()->json([
        'status'  => 'ok',
        'message' => 'API 4ayab is running',
        'timestamp' => now()->toISOString()
    ]);
});
