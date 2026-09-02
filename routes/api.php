<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UsageController;
use App\Http\Controllers\ConversionController;

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

    // Get pool of API keys (from DB api_keys table)
    Route::get('/api-keys', function () {
        $keys = \App\Models\ApiKey::orderBy('id')->pluck('key')->toArray();
        return response()->json([
            'keys' => $keys
        ]);
    });

    // Get quick prompts from DB settings
    Route::get('/quick-prompts', function () {
        $promptsJson = \App\Models\Setting::getValue('quick_prompts', '[]');
        $prompts = json_decode($promptsJson, true);
        return response()->json([
            'prompts' => is_array($prompts) ? $prompts : []
        ]);
    });

    // Get speaker labels from DB settings
    Route::get('/speaker-labels', function () {
        $labelsJson = \App\Models\Setting::getValue('speaker_labels', '["Interviewer", "Interviewee", "Doctor", "Patient", "Host", "Guest"]');
        $labels = json_decode($labelsJson, true);
        return response()->json([
            'labels' => is_array($labels) ? $labels : []
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

    // Projects (saved transcription sessions)
    Route::get('/projects',           [\App\Http\Controllers\ProjectController::class, 'index']);
    Route::post('/projects',          [\App\Http\Controllers\ProjectController::class, 'store']);
    Route::get('/projects/{id}',      [\App\Http\Controllers\ProjectController::class, 'show']);
    Route::put('/projects/{id}',      [\App\Http\Controllers\ProjectController::class, 'update']);
    Route::delete('/projects/{id}',   [\App\Http\Controllers\ProjectController::class, 'destroy']);

    // Conversion
    Route::post('/convert/prepare',    [ConversionController::class, 'prepare']);
    Route::get('/convert/chunk',       [ConversionController::class, 'chunk']);
    Route::delete('/convert/session',  [ConversionController::class, 'cleanup']);

    // ── Admin-only routes ──────────────────────────────────────────────────
    Route::middleware('admin.only')->prefix('admin')->group(function () {
        Route::get('/users',         [UserController::class, 'index']);
        Route::post('/users',        [UserController::class, 'store']);
        Route::get('/users/{id}',    [UserController::class, 'show']);
        Route::put('/users/{id}',    [UserController::class, 'update']);
        Route::delete('/users/{id}', [UserController::class, 'destroy']);
    });
});
