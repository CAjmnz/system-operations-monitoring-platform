<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\SystemDashboardController;
use App\Http\Controllers\LogController;

/*
|--------------------------------------------------------------------------
| INERTIA PAGES (FRONTEND ROUTES)
|--------------------------------------------------------------------------
*/

// Welcome page
Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => app()->version(),
        'phpVersion' => PHP_VERSION,
    ]);
});

// Dashboard
Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// System Usage
Route::get('/system/usage', function () {
    return Inertia::render('SystemUsage');
})->middleware(['auth'])->name('usage.page');

// System Logs
Route::get('/system/logs', function () {
    return Inertia::render('SystemLogs');
})->middleware(['auth'])->name('logs.page');


/*
|--------------------------------------------------------------------------
| API ROUTES (FOR AXIOS ONLY)
|--------------------------------------------------------------------------
*/

// Logs
Route::get('/api/logs', [LogController::class, 'index']);
Route::post('/api/logs', [LogController::class, 'store']);

// Dashboard metrics
Route::get('/system/dashboard', [SystemDashboardController::class, 'index']);


/*
|--------------------------------------------------------------------------
| AUTH ROUTES
|--------------------------------------------------------------------------
*/

require __DIR__.'/auth.php';