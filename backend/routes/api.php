<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\DietController;
use App\Http\Controllers\RoutineController;
use App\Http\Controllers\Api\ReviewController;
use App\Http\Controllers\NutritionController;

/*
|--------------------------------------------------------------------------
| API Routes (Para accesos externos / App Móvil)
|--------------------------------------------------------------------------
*/

// --- PÚBLICO (No requiere login) ---
Route::get('/reviews', [ReviewController::class, 'index']);
Route::get('/ingest-exercises', [RoutineController::class, 'ingestExercises']);
Route::get('/routines/recommendations', [RoutineController::class, 'getRecommendations']);

// --- PROTEGIDO CON TOKEN (Sanctum) ---
Route::middleware(['auth:sanctum'])->group(function () {

    // --- IMPORTANTE ---
    // Esta ruta está comentada para NO chocar con la de web.php
    // La web usa web.php (cookies). Si hicieras una App Android/iOS nativa,
    // descomenta esto o usa otro endpoint.
    /*
    Route::get('/user', function (Request $request) {
        return $request->user()->load('profile');
    });
    */

    // Productos y Dietas
    Route::get('/products/search', [ProductController::class, 'search']);
    Route::apiResource('products', ProductController::class)->except(['update', 'destroy']);
    Route::apiResource('diets', DietController::class);

    // Nutrición (Planes semanales)
    Route::get('/weekly-plans', [NutritionController::class, 'index']);
    Route::put('/weekly-plans/{id}', [NutritionController::class, 'updateStatus']);
    Route::post('/weekly-plans/save-day', [NutritionController::class, 'saveDayPlan']);
});