<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Importamos tus Controladores
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\DietController;
use App\Http\Controllers\RoutineController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Aquí registramos las rutas para la API de FitManager.
| Todas estas rutas llevan el prefijo /api automáticamente.
|
*/

// =========================================================================
// 1. RUTAS PÚBLICAS (No requieren iniciar sesión)
// =========================================================================

// Registro de usuario y perfil (Transacción de 3 pasos en 1)
Route::post('/register', [AuthController::class, 'register']);

// Inicio de sesión (Devuelve el Token)
Route::post('/login', [AuthController::class, 'login']);


// =========================================================================
// 2. RUTAS PROTEGIDAS (Requieren Token de Sanctum)
// =========================================================================

Route::middleware(['auth:sanctum'])->group(function () {

    // --- Usuario y Perfil ---
    // Obtener usuario básico
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // Ver datos completos del perfil (Peso, altura, etc.)
    Route::get('/profile', [ProfileController::class, 'show']);
    
    // Actualizar perfil
    Route::put('/profile', [ProfileController::class, 'update']);


    // --- Home / Dashboard ---
    // Datos para la pantalla de bienvenida (Resúmenes)
    Route::get('/home', [HomeController::class, 'index']);


    // --- Nutrición y Productos ---
    // Buscador de productos (Ej: /api/products/search?query=Manzana)
    Route::get('/products/search', [ProductController::class, 'search']);
    
    // CRUD básico de productos (Index y Show)
    // Usamos 'except' porque los usuarios normales no crean productos, solo los leen
    Route::apiResource('products', ProductController::class)->except(['store', 'update', 'destroy']);


    // --- Gestión de Dietas ---
    // Rutas estándar: GET /diets, POST /diets, GET /diets/{id}, etc.
    Route::apiResource('diets', DietController::class);


    // --- Gestión de Entrenamientos (Rutinas) ---
    // Rutas estándar para las rutinas
    Route::apiResource('routines', RoutineController::class);

});