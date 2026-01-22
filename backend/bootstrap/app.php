<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        
        // 1. ESTO ES LO QUE QUITA EL ERROR 419 (Sin Tokens)
        $middleware->validateCsrfTokens(except: [
            'api/routines', 
            'api/routines/*', // Por si acaso usas parámetros
        ]);

        // Tu configuración actual de redirección
        $middleware->redirectTo(
            guests: '/login',
            users: '/home.html'
        );
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();