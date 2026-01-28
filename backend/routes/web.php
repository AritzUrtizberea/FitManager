<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoutineController; // <--- IMPORTANTE: Añadido
use App\Http\Controllers\Api\ReviewController; // <--- IMPORTANTE: Añadido
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\ExerciseController as AdminExerciseController;

Route::get('/', function () {
    // Si ya entró, al home; si no, al login
    return auth()->check() ? redirect('/home') : redirect('/login');
});

Route::get('/home', function () {
    return view('home'); 
})->name('home');


// --- ZONA ADMIN ---
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    
    // Ruta del Dashboard
    Route::get('/dashboard', function () {
        return view('admin.dashboard'); 
    })->name('dashboard'); // El nombre completo será 'admin.dashboard'

    Route::resource('products', AdminProductController::class);
    Route::resource('exercises', AdminExerciseController::class);
});
// --- RUTAS QUE REQUIEREN LOGIN ---
Route::middleware('auth')->group(function () {
    
    // Perfil de Usuario
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
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