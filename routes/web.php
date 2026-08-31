<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminWebController;

/*
|--------------------------------------------------------------------------
| Web Routes — Web Admin Portal
|--------------------------------------------------------------------------
*/

// Landing Page
Route::get('/', function () {
    return view('landing');
})->name('home');

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

    // API Keys Management
    Route::get('/api-keys', [AdminWebController::class, 'apiKeysIndex'])->name('admin.api_keys');
    Route::post('/api-keys', [AdminWebController::class, 'apiKeyStore'])->name('admin.api_keys.store');
    Route::put('/api-keys/{id}', [AdminWebController::class, 'apiKeyUpdate'])->name('admin.api_keys.update');
    Route::delete('/api-keys/{id}', [AdminWebController::class, 'apiKeyDestroy'])->name('admin.api_keys.destroy');

    // Settings
    Route::get('/settings', [AdminWebController::class, 'settingsIndex'])->name('admin.settings');
    Route::put('/settings', [AdminWebController::class, 'settingsUpdate'])->name('admin.settings.update');

    // Logout
    Route::post('/logout', [AdminWebController::class, 'logout'])->name('admin.logout');

    // Live Chat Admin Endpoints
    Route::get('/chat', [\App\Http\Controllers\ChatController::class, 'adminView'])->name('admin.chat');
    Route::get('/api/chat/sessions', [\App\Http\Controllers\ChatController::class, 'adminFetchSessions'])->name('admin.chat.sessions');
    Route::get('/api/chat/{session}/messages', [\App\Http\Controllers\ChatController::class, 'adminFetchMessages'])->name('admin.chat.messages');
    Route::post('/api/chat/{session}/message', [\App\Http\Controllers\ChatController::class, 'adminSendMessage'])->name('admin.chat.send');
    Route::post('/api/chat/{session}/close', [\App\Http\Controllers\ChatController::class, 'adminCloseSession'])->name('admin.chat.close');
});

// ── Public Chat Endpoints (AJAX for Landing Page) ──
Route::prefix('api/chat')->group(function () {
    Route::post('/start', [\App\Http\Controllers\ChatController::class, 'startSession'])->name('chat.start');
    Route::get('/{session}/messages', [\App\Http\Controllers\ChatController::class, 'guestFetchMessages'])->name('chat.messages');
    Route::post('/{session}/message', [\App\Http\Controllers\ChatController::class, 'guestSendMessage'])->name('chat.send');
});

