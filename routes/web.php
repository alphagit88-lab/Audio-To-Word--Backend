<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminWebController;

/*
|--------------------------------------------------------------------------
| Web Routes — Web Admin Portal
|--------------------------------------------------------------------------
*/

// Root redirect to Admin Portal
Route::get('/', function () {
    return redirect()->route('admin.login');
});

// Admin Web Authentication Routes
Route::get('/admin/login', [AdminWebController::class, 'showLogin'])->name('admin.login');
Route::post('/admin/login', [AdminWebController::class, 'login'])->name('admin.login.submit');

// Protected Admin Portal Routes
Route::middleware('admin.web')->prefix('admin')->group(function () {
    Route::get('/', function () {
        return redirect()->route('admin.dashboard');
    });

    Route::get('/dashboard', [AdminWebController::class, 'dashboard'])->name('admin.dashboard');

    // User Management CRUD
    Route::get('/users', [AdminWebController::class, 'usersIndex'])->name('admin.users');
    Route::post('/users', [AdminWebController::class, 'userStore'])->name('admin.users.store');
    Route::put('/users/{id}', [AdminWebController::class, 'userUpdate'])->name('admin.users.update');
    Route::delete('/users/{id}', [AdminWebController::class, 'userDestroy'])->name('admin.users.destroy');

    // Usage Logs
    Route::get('/usage', [AdminWebController::class, 'usageIndex'])->name('admin.usage');

    // Error Logs
    Route::get('/error-logs', [AdminWebController::class, 'errorLogsIndex'])->name('admin.error_logs');

    // Settings
    Route::get('/settings', [AdminWebController::class, 'settingsIndex'])->name('admin.settings');
    Route::put('/settings', [AdminWebController::class, 'settingsUpdate'])->name('admin.settings.update');

    // Logout
    Route::post('/logout', [AdminWebController::class, 'logout'])->name('admin.logout');
});
