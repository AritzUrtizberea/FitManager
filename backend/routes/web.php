<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoutineController; // <--- IMPORTANTE: Añadido
use App\Http\Controllers\Api\ReviewController; // <--- IMPORTANTE: Añadido
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Redirección inicial
Route::get('/', function () {
    return auth()->check() ? redirect('/home') : redirect('/login');
});

// Ruta de Home (Tu frontend principal)
Route::get('/home', function () {
    return view('home'); 
})->name('home');

// --- RUTAS QUE REQUIEREN LOGIN ---
Route::middleware('auth')->group(function () {
    
    // Perfil de Usuario
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // =================================================================
    //  RUTAS "API" INTERNAS (Movidias aquí para usar la sesión)
    // =================================================================
    Route::prefix('api')->group(function () {
        
        // 1. RUTINAS (Ahora Auth::id() funcionará correctamente)
        Route::get('/routines', [RoutineController::class, 'index']); // Leer mis rutinas
        Route::post('/routines', [RoutineController::class, 'store']); // Guardar rutina
        Route::delete('/routines/{id}', [RoutineController::class, 'destroy']); // Borrar rutina

        // 2. RESEÑAS (Guardar)
        Route::post('/reviews', [ReviewController::class, 'store']);
    });

});

require __DIR__.'/auth.php';