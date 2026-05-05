<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\SystemDashboardController;
use App\Http\Controllers\LogController;
use App\Http\Controllers\ProfileController;

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

// Dashboard page (cards default page)
Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// System logs page (Vue page)
Route::get('/system/logs', function () {
    return Inertia::render('SystemLogs');
})->middleware(['auth'])->name('logs.page');
Route::get('/system/usage', function () {
    return Inertia::render('SystemUsage');
})->middleware(['auth'])->name('usage.page');


/*
|--------------------------------------------------------------------------
| API ROUTES (FOR AXIOS)
|--------------------------------------------------------------------------
*/

Route::get('/api/logs', [LogController::class, 'index']);
Route::get('/system/dashboard', [SystemDashboardController::class, 'index']);

/*
|--------------------------------------------------------------------------
| AUTH
|--------------------------------------------------------------------------
*/

require __DIR__.'/auth.php';