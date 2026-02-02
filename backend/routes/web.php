<?php

use Illuminate\Support\Facades\Route;

// --- CONTROLADORES ---
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoutineController;
// OJO: Asegúrate de que tu ReviewController está en la carpeta 'app/Http/Controllers'.
// Si está en 'app/Http/Controllers/Api', deja la línea como la tenías antes.
use App\Http\Controllers\ReviewController; 
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\ExerciseController as AdminExerciseController;

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
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
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
Route::middleware('auth')->group(function () {
    
    Route::get('/home', function () {
    return view('home'); 
    })->name('home');

    // --- PERFIL ---
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // --- RESEÑAS (Híbrido: Blade para crear, JS para leer) ---
    // 1. GET: Muestra la vista con el formulario y el contenedor vacío para el JS
    // IMPORTANTE: Ahora la URL es '/reviews' a secas.

    
    // 2. POST: Recibe el formulario de Blade y guarda la reseña
    Route::post('/reviews', [ReviewController::class, 'store'])->name('reviews.store');
    Route::get('/reviews', [ReviewController::class, 'create'])->name('reviews.index'); 

    // --- API INTERNA (Usada por el JS de Rutinas) ---
    // Estas rutas no devuelven vistas, devuelven datos JSON o hacen acciones
    Route::prefix('api')->group(function () {
        
        // Rutinas
        Route::get('/routines', [RoutineController::class, 'index']); 
        Route::post('/routines', [RoutineController::class, 'store']); 
        Route::delete('/routines/{id}', [RoutineController::class, 'destroy']); 
        
        // (He quitado el POST de reviews de aquí porque ya lo tenemos arriba para usar con Blade)
    });

});

require __DIR__.'/auth.php';