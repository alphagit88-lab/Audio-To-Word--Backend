<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;

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

    // Get pool of API keys
    Route::get('/api-keys', function () {
        $keysString = env('GEMINI_API_KEYS', '');
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
