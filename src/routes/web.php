<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ProfileController; // Importante añadir esto

// 1. Raíz: Solo decide a dónde vas.
Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('home');
    }
    return view('welcome');
});

// 2. Rutas Protegidas
// 2. Rutas Protegidas
Route::middleware(['auth', 'verified'])->group(function () {
    
    // Home
    Route::get('/inicio', function () {
        return view('home.home', ['user' => Auth::user()]);
    })->name('home');

    // VISTA DE LOS CUADROS (Stats)
    Route::get('/perfil', function () {
        return view('configuration.configuration', ['user' => Auth::user()]);
    })->name('perfil');

    // RUTAS DE PERFIL (Todas las necesarias)
    Route::get('/perfil/editar', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/perfil/editar', [ProfileController::class, 'update'])->name('profile.update');
    // AÑADE ESTA LÍNEA PARA QUITAR EL ERROR:
    Route::delete('/perfil/editar', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // En routes/web.php añade esta línea
    Route::get('/dashboard', function () {
        return redirect()->route('home');
    })->name('dashboard');
});

require __DIR__.'/auth.php';