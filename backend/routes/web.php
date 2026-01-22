<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoutineController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Rutas unificadas para tu JavaScript
    Route::get('/api/routines', [RoutineController::class, 'index']);
    Route::post('/api/routines', [RoutineController::class, 'store']);
    Route::get('/api/routines/recommendations', [RoutineController::class, 'getRecommendations']); // Sugerencias
});

require __DIR__.'/auth.php';
