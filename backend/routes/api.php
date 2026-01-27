<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\DietController;
use App\Http\Controllers\RoutineController;
use App\Http\Controllers\Api\ReviewController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// --- PÚBLICO (No requiere login) ---

// Reseñas (Solo leer)
Route::get('/reviews', [ReviewController::class, 'index']);

// Autenticación para Apps externas (si usas Token)
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

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
});