<?php

use App\Http\Controllers\ProfileController;
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

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// --- ZONA ADMIN ---
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    
    // Ruta del Dashboard
    Route::get('/dashboard', function () {
        return view('admin.dashboard'); 
    })->name('dashboard'); // El nombre completo será 'admin.dashboard'

    Route::resource('products', AdminProductController::class);
    Route::resource('exercises', AdminExerciseController::class);
});

require __DIR__.'/auth.php';