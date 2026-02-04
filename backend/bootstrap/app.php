<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\AdminMiddleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Configuración de Proxies
        $middleware->trustProxies(at: '*');

        // Alias para el admin
        $middleware->alias([
            'admin' => AdminMiddleware::class,
        ]);

        // 👇 CAMBIO IMPORTANTE: Usamos 'append' directo (Global)
        // Esto obliga a que se ejecute SIEMPRE, en cualquier ruta.
        $middleware->append(\App\Http\Middleware\UpdateStreak::class);

        // Configuraciones extra
        $middleware->statefulApi(); 
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