<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SystemDashboardController;
use App\Http\Controllers\LogController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome');
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/system/dashboard', [SystemDashboardController::class, 'index']);
Route::get('/system/logs', [LogController::class, 'index']);

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit']);
    Route::patch('/profile', [ProfileController::class, 'update']);
    Route::delete('/profile', [ProfileController::class, 'destroy']);
});

require __DIR__.'/auth.php';