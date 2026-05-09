<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

use App\Http\Controllers\Pages\DashboardController;
use App\Http\Controllers\Pages\SystemUsageController;
use App\Http\Controllers\Pages\SystemLogsController;
use App\Http\Controllers\SystemDashboardController;
use App\Http\Controllers\LogController;
use App\Http\Controllers\Auth\AuthController;

/*
|--------------------------------------------------------------------------
| GUEST ROUTES
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => true,
        'canRegister' => true,
        'laravelVersion' => app()->version(),
        'phpVersion' => PHP_VERSION,
    ]);
})->middleware('guest');

/*
|--------------------------------------------------------------------------
| AUTH ROUTES (CLEAN — NO CLOSURE LOGIN)
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->group(function () {

    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');

    // ✅ FIXED: direct controller method
    Route::post('/login', [AuthController::class, 'login']);

    Route::post('/register', [AuthController::class, 'register']);
});

/*
|--------------------------------------------------------------------------
| AUTHENTICATED ROUTES
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    Route::get('dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    Route::get('system/usage', [SystemUsageController::class, 'index'])
        ->middleware('userType:admin')
        ->name('system.usage');

    Route::get('system/logs', [SystemLogsController::class, 'index'])
        ->name('system.logs');

    Route::get('system/dashboard', [SystemDashboardController::class, 'index'])
        ->name('api.metrics');

    Route::prefix('api')->group(function () {
        Route::get('logs', [LogController::class, 'index']);
        Route::post('logs', [LogController::class, 'store']);
    });

    Route::post('logout', [AuthController::class, 'logout'])
        ->name('logout');
});