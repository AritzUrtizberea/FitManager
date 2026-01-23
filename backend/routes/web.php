<?php
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// Redirección inicial: Si entran a /, que decida según si están logueados
Route::get('/', function () {
    return auth()->check() ? redirect('/home') : redirect('/login');
});

// Ruta de Home (Tu frontend)
Route::get('/home', function () {
    return view('home'); // Asegúrate de que exista resources/views/home.blade.php
})->name('home');

// Rutas de Perfil (Saca la de EDIT fuera del middleware para probar que carga)
Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');

Route::middleware('auth')->group(function () {
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    // Cámbiala a /profile-update para que sea única
    Route::put('/profile-update', [ProfileController::class, 'update'])->name('profile.update');
});

require __DIR__.'/auth.php';