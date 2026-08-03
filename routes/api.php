<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UsageController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// ── Public routes (no authentication required) ────────────────────────────
Route::post('/login', [AuthController::class, 'login']);

// ── Authenticated routes ───────────────────────────────────────────────────
Route::middleware('auth.api_token')->group(function () {

    // Session management
    Route::get('/validate-token', [AuthController::class, 'validateToken']);
    Route::post('/logout', [AuthController::class, 'logout']);

    // Usage logging
    Route::post('/usage', [UsageController::class, 'store']);
    Route::get('/usage/summary', [UsageController::class, 'summary']);
    
    // Error logging
    Route::post('/error-logs', [\App\Http\Controllers\ErrorLogController::class, 'store']);

    // Get pool of API keys (from DB settings)
    Route::get('/api-keys', function () {
        $keysString = \App\Models\Setting::getValue('gemini_api_keys', '');
        $keys = array_filter(array_map('trim', explode(',', $keysString)));
        return response()->json([
            'keys' => array_values($keys)
        ]);
    });

    // Authenticated user profile
    Route::get('/me', function (Request $request) {
        $user = $request->user();
        return response()->json([
            'user' => [
                'id'    => $user->id,
                'name'  => $user->name,
                'email' => $user->email,
                'role'  => $user->role,
            ],
        ]);
    });

    // ── Admin-only routes ──────────────────────────────────────────────────
    Route::middleware('admin.only')->prefix('admin')->group(function () {
        Route::get('/users',         [UserController::class, 'index']);
        Route::post('/users',        [UserController::class, 'store']);
        Route::get('/users/{id}',    [UserController::class, 'show']);
        Route::put('/users/{id}',    [UserController::class, 'update']);
        Route::delete('/users/{id}', [UserController::class, 'destroy']);
    });
});
