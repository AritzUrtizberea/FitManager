<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    // En bootstrap/app.php cambia el users: '/' por '/inicio'
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->redirectTo(
            guests: '/login',
            users: '/inicio'  // <--- CAMBIA ESTO
        );
        $middleware->append(\App\Http\Middleware\PreventBackHistory::class);
    })

    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();