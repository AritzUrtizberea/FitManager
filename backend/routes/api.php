<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\DietController;
use App\Http\Controllers\RoutineController;

// 1. RUTAS PÚBLICAS (Para que el JS cargue los datos)
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Rutas de datos (Libres para evitar errores 401/500 por sesión)
Route::get('/ingest-exercises', [RoutineController::class, 'ingestExercises']);
Route::get('/routines/recommendations', [RoutineController::class, 'getRecommendations']);
Route::get('/routines', [RoutineController::class, 'index']); 

// 2. RUTAS PROTEGIDAS (Sanctum)
Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    
    // Guardar y Borrar
    Route::post('/routines', [RoutineController::class, 'store']);
    Route::delete('/routines/{id}', [RoutineController::class, 'destroy']);

    // Otros módulos
    Route::get('/profile', [ProfileController::class, 'show']);
    Route::put('/profile', [ProfileController::class, 'update']);
    Route::get('/home', [HomeController::class, 'index']);
    Route::get('/products/search', [ProductController::class, 'search']);
    
    Route::apiResource('products', ProductController::class)->except(['update', 'destroy']);
    Route::apiResource('diets', DietController::class);

    Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    // Aquí está la magia: ->load('profile') carga los datos de la otra tabla
    return $request->user()->load('profile');
});
});