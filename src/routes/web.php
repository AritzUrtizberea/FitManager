<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

// 1. Raíz: Solo decide a dónde vas.
Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('home'); // Si está logueado, va al Home
    }
    return view('welcome'); // Si no, a la bienvenida/login
});

// 2. Rutas Protegidas (Donde puedes dar F5 sin problemas)
Route::middleware(['auth', 'verified'])->group(function () {
    
    Route::get('/inicio', function () {
        return view('home.home', ['user' => Auth::user()]);
    })->name('home');

    Route::get('/perfil', function () {
        // El punto indica que 'configuration' es una carpeta
        return view('configuration.configuration', ['user' => Auth::user()]);
    })->name('perfil');
});

require __DIR__.'/auth.php';