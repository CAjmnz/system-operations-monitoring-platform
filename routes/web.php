<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\Pages\DashboardController;
use App\Http\Controllers\Pages\SystemUsageController;
use App\Http\Controllers\Pages\SystemLogsController;
use App\Http\Controllers\SystemDashboardController;
use App\Http\Controllers\LogController;
use App\Http\Controllers\Auth\AuthController;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => true,
        'canRegister' => true,
        'laravelVersion' => app()->version(),
        'phpVersion' => PHP_VERSION,
    ]);
})->name('home');

Route::get('/login', fn () => redirect('/'));

Route::middleware('guest')->group(function () {
    Route::post('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/register', [AuthController::class, 'register'])->name('register');
});

Route::middleware('auth')->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/system/usage', [SystemUsageController::class, 'index'])
        ->middleware('userType:admin')
        ->name('system.usage');

    Route::get('/system/logs', [SystemLogsController::class, 'index'])
        ->middleware('userType:admin')
        ->name('system.logs');

    Route::get('/system/dashboard', [SystemDashboardController::class, 'index']);

    

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});