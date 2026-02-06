<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request; // Necesario para usar $request en las funciones

// --- CONTROLADORES ---
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoutineController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\ExerciseController as AdminExerciseController;

// --- MIDDLEWARE ---
use App\Http\Middleware\AdminMiddleware; 

/*
|--------------------------------------------------------------------------
| RUTAS PÚBLICAS
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return auth()->check() ? redirect('/home') : redirect('/login');
});

/*
|--------------------------------------------------------------------------
| ZONA ADMIN
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', AdminMiddleware::class])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard'); 
    })->name('dashboard');

    Route::resource('products', AdminProductController::class);
    Route::resource('exercises', AdminExerciseController::class);
});

/*
|--------------------------------------------------------------------------
| RUTAS DE USUARIO (Auth)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'verified'])->group(function () {

    // --- VISTAS PRINCIPALES ---
    Route::get('/home', function () { return view('home.home'); })->name('home');
    Route::get('/training', function () { return view('training.training'); })->name('training');
    Route::get('/routines', function () { return view('routines.routines'); })->name('routines');
    Route::get('/nutrition', function () { return view('nutrition.nutrition'); })->name('nutrition');
    Route::get('/crear-dieta', function () { return view('crear-dieta.crear-dieta'); })->name('crear-dieta');
    Route::get('/info-producto', function () { return view('info-producto.info-producto'); })->name('info-producto');
    Route::get('/privacidad', function () { return view('privacidad.privacidad'); })->name('privacidad');

    // ==========================================
    //  PERFIL (NUEVO)
    // ==========================================
    
    // 1. Vista visual del perfil (La que has maquetado)
    Route::get('/perfil', function () {
        return view('perfil.perfil');
    })->name('perfil');

    // 2. Configuración de cuenta (Editar contraseña, borrar cuenta, etc.)
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // ==========================================
    //  ZONA DE RESEÑAS
    // ==========================================
    Route::get('/reviews/create', [ReviewController::class, 'create'])->name('reviews.create');
    Route::get('/reviews', [ReviewController::class, 'index'])->name('reviews.index');
    Route::get('/reviews/list', [ReviewController::class, 'list'])->name('reviews.list');
    Route::post('/reviews', [ReviewController::class, 'store'])->name('reviews.store');
    Route::delete('/reviews/{review}', [ReviewController::class, 'destroy'])->name('reviews.destroy');

    // ==========================================
    //  API INTERNA (AJAX desde el Navegador)
    // ==========================================
    // Estas rutas usan la sesión del navegador. No requieren tokens Sanctum.
    Route::prefix('api')->group(function () {
        
        // VITAL: Esta ruta devuelve al usuario CON los datos de su perfil (peso, altura)
        // para que perfil.js pueda mostrarlos.
        Route::get('/user', function (Request $request) {
            // Asegúrate de que en App\Models\User tengas la relación public function profile()
            return $request->user()->load('profile'); 
        });

        Route::get('/routines', [RoutineController::class, 'index']); 
        Route::post('/routines', [RoutineController::class, 'store']); 
        Route::delete('/routines/{id}', [RoutineController::class, 'destroy']); 
    });

});

// Carga las rutas de autenticación (login, logout, register)
require __DIR__.'/auth.php';