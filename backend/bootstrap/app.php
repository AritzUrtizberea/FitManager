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
        
        // 1. Permite que la API use cookies de sesión (ESTO ARREGLA EL 401)
        $middleware->statefulApi(); 

        // 2. Mantén esto para evitar el error 419 de momento
        $middleware->validateCsrfTokens(except: [
            'api/routines', 
            'api/routines/*',
            'logout',
        ]);

        $middleware->redirectTo(
            guests: '/login',
            users: '/home'
        );
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();