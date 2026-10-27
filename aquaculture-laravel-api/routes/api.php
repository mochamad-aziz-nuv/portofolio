<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CycleController;
use App\Http\Controllers\Api\FeedingLogController;
use App\Http\Controllers\Api\PondController;
use App\Http\Controllers\Api\WaterQualityLogController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes - Smart Aquaculture Management System
|--------------------------------------------------------------------------
| Prefix: /api/v1 (and convenience aliases at /api)
*/

// Public Authentication
Route::post('/login', [AuthController::class, 'login']);
Route::post('/v1/auth/login', [AuthController::class, 'login']);

// Healthcheck & Overview
Route::get('/health', function () {
    return response()->json([
        'status' => 'ok',
        'service' => 'NagroSmart Aquaculture API',
        'version' => '1.0.0',
        'timestamp' => now()->toIso8601String(),
    ]);
});

// Authenticated Routes (Sanctum)
Route::middleware('auth:sanctum')->group(function () {
    // Auth profile & logout
    Route::get('/me', [AuthController::class, 'profile']);
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::prefix('v1')->group(function () {
        Route::get('/auth/me', [AuthController::class, 'profile']);
        Route::post('/auth/logout', [AuthController::class, 'logout']);

        // Ponds Management
        Route::apiResource('ponds', PondController::class);

        // Cycles Management
        Route::get('cycles/{id}/summary', [CycleController::class, 'summary']);
        Route::apiResource('cycles', CycleController::class);

        // Feeding Logs
        Route::get('feeding-logs/summary', [FeedingLogController::class, 'summary']);
        Route::apiResource('feeding-logs', FeedingLogController::class)->only(['index', 'store']);

        // Water Quality Logs
        Route::get('water-quality-logs/latest', [WaterQualityLogController::class, 'latest']);
        Route::apiResource('water-quality-logs', WaterQualityLogController::class)->only(['index', 'store']);
    });

    // Root-level aliases for direct mobile convenience (/api/ponds, /api/cycles, etc.)
    Route::apiResource('ponds', PondController::class);
    Route::get('cycles/{id}/summary', [CycleController::class, 'summary']);
    Route::apiResource('cycles', CycleController::class);
    Route::get('feeding-logs/summary', [FeedingLogController::class, 'summary']);
    Route::apiResource('feeding-logs', FeedingLogController::class)->only(['index', 'store']);
    Route::get('water-quality-logs/latest', [WaterQualityLogController::class, 'latest']);
    Route::apiResource('water-quality-logs', WaterQualityLogController::class)->only(['index', 'store']);
});
