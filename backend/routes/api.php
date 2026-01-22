<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\DietController;
use App\Http\Controllers\RoutineController;

// 1. RUTAS PÚBLICAS
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Estas son administrativas o generales, pueden estar fuera
Route::get('/ingest-exercises', [RoutineController::class, 'ingestExercises']);
Route::get('/my-exercises', [RoutineController::class, 'getMyExercises']);

// 2. RUTAS PROTEGIDAS
Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    
    // Rutinas y Recomendaciones AQUÍ (dentro del login)
   // Route::get('/routines', [RoutineController::class, 'index']); // <--- ESTA ES LA QUE FALTA
    //Route::post('/routines', [RoutineController::class, 'store']);
    //Route::get('/routines/recommendations', [RoutineController::class, 'getRecommendations']);
    //Route::post('/routines/auto', [RoutineController::class, 'generateAutoRoutine']);

    Route::get('/profile', [ProfileController::class, 'show']);
    Route::put('/profile', [ProfileController::class, 'update']);
    Route::get('/home', [HomeController::class, 'index']);

    Route::get('/products/search', [ProductController::class, 'search']);
    Route::apiResource('products', ProductController::class)->except(['store', 'update', 'destroy']);
    Route::apiResource('diets', DietController::class);

    // Guardar rutinas SI debe ser protegido, porque requiere un user_id
    Route::post('/routines', [RoutineController::class, 'store']);
});