<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SystemDashboardController;
use App\Http\Controllers\LogController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Welcome Page
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

/*
|--------------------------------------------------------------------------
| DASHBOARD (INERTIA VUE FRONTEND)
|--------------------------------------------------------------------------
*/
Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

/*
|--------------------------------------------------------------------------
| SYSTEM DASHBOARD API (FOR VUE AXIOS)
|--------------------------------------------------------------------------
| NOTE: Removed auth middleware for development stability
| You can secure this later in Stage 3
*/
Route::get('/system/dashboard', [SystemDashboardController::class, 'index']);

/*
|--------------------------------------------------------------------------
| SYSTEM LOGS API (POLLING MODE)
|--------------------------------------------------------------------------
*/
Route::get('/system/logs', [LogController::class, 'index']);

/*
|--------------------------------------------------------------------------
| PROFILE ROUTES (AUTH ONLY)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

/*
|--------------------------------------------------------------------------
| AUTH ROUTES
|--------------------------------------------------------------------------
*/
require __DIR__.'/auth.php';