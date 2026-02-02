<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\DietController;
use App\Http\Controllers\RoutineController;
use App\Http\Controllers\Api\ReviewController;
use App\Http\Controllers\NutritionController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// --- PÚBLICO (No requiere login) ---

// Reseñas (Solo leer)
Route::get('/reviews', [ReviewController::class, 'index']);

// Ingesta y Recomendaciones (Datos generales)
Route::get('/ingest-exercises', [RoutineController::class, 'ingestExercises']);
Route::get('/routines/recommendations', [RoutineController::class, 'getRecommendations']);

// --- PROTEGIDO CON TOKEN (Sanctum) ---
// Estas rutas son para si conectas una App Móvil en el futuro.
// Las rutas de la web ahora van por web.php
Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // Productos y Dietas (Si ves que fallan en la web, muévelas también a web.php)
    Route::get('/products/search', [ProductController::class, 'search']);
    Route::apiResource('products', ProductController::class)->except(['update', 'destroy']);
    Route::apiResource('diets', DietController::class);

    Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    // Aquí está la magia: ->load('profile') carga los datos de la otra tabla
    return $request->user()->load('profile');
});
});

Route::middleware('auth:sanctum')->group(function () {
    // Tu ruta será: http://tu-web/api/weekly-plans
    Route::get('/weekly-plans', [NutritionController::class, 'index']);
    Route::put('/weekly-plans/{id}', [NutritionController::class, 'updateStatus']);
    Route::post('/weekly-plans/save-day', [App\Http\Controllers\NutritionController::class, 'saveDayPlan']);
});

