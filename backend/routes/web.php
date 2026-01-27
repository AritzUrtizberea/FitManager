<?php
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticatedSessionController;

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
    // Para ver la página
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    // Para guardar los datos (Cámbiala a /profile para que coincida con Nginx)
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
});

// Esta es la ruta que llama el formulario {{ route('logout') }}
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
    ->name('logout');

require __DIR__.'/auth.php';