<?php
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
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

// FIXED: removed ->middleware('guest') so logged-in users can also visit '/'
Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin'      => true,
        'canRegister'   => true,
        'laravelVersion' => app()->version(),
        'phpVersion'    => PHP_VERSION,
    ]);
})->name('home');

// FIXED: GET /login just redirects to '/' — modal-only system
Route::get('/login', function () {
    return redirect('/');
})->name('login');

/*
|--------------------------------------------------------------------------
| AUTH POST ROUTES (guest only)
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    Route::post('/register', [AuthController::class, 'register'])->name('register');
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