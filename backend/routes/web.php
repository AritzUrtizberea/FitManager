<?php

use Illuminate\Support\Facades\Route;

// --- CONTROLADORES ---
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoutineController;
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

    // ==========================================
    //  ZONA DE RESEÑAS
    // ==========================================

    // 1. PANTALLA OBLIGATORIA (Create) - URL distinta para que no haga bucle
    Route::get('/reviews/create', [ReviewController::class, 'create'])->name('reviews.create');

    // 2. PANTALLA PRINCIPAL (Index + Lista)
    Route::get('/reviews', [ReviewController::class, 'index'])->name('reviews.index');

    // 3. DATOS JSON (Para que el JavaScript pinte la lista)
    Route::get('/reviews/list', [ReviewController::class, 'list'])->name('reviews.list');

    // 4. ACCIONES (Guardar y Borrar)
    Route::post('/reviews', [ReviewController::class, 'store'])->name('reviews.store');
    Route::delete('/reviews/{review}', [ReviewController::class, 'destroy'])->name('reviews.destroy');


    // --- API INTERNA (Otras cosas) ---
    Route::prefix('api')->group(function () {
// Rutinas
        // Y como está en web.php, ¡recordará que estás logueado!
        Route::get('/user', [ProfileController::class, 'getUserData']);

        
 
        Route::get('/routines', [RoutineController::class, 'index']); 
        Route::post('/routines', [RoutineController::class, 'store']); 
        Route::delete('/routines/{id}', [RoutineController::class, 'destroy']); 
    });

});

require __DIR__.'/auth.php';