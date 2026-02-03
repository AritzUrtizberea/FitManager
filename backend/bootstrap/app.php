<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
// 1. IMPORTANTE: Importamos el archivo del Middleware aquí
use App\Http\Middleware\AdminMiddleware; 

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        
        // 2. AQUÍ REGISTRAMOS EL ALIAS 'admin'
        $middleware->alias([
            'admin' => AdminMiddleware::class,
        ]);

        // --- Tus configuraciones anteriores (NO LAS BORRES) ---

        // Permite que la API use cookies de sesión
        $middleware->statefulApi(); 

        // Excepciones CSRF
        $middleware->validateCsrfTokens(except: [
            'api/routines', 
            'api/routines/*',
            'logout',
        ]);

        $middleware->redirectTo(
            guests: '/login',
            users: '/home'
        );

     
            $middleware->alias([
                'check.review' => \App\Http\Middleware\CheckFirstReview::class,
            ]);
        
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();