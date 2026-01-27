<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
{
    // Verificamos si el usuario es administrador
    // Asumo que en tu base de datos tienes un campo 'is_admin' o 'role'
    if (!auth()->check() || !auth()->user()->is_admin) {
        // Si no es admin, lo mandamos al home normal o damos error 403
        return redirect('/home'); 
    }

    return $next($request);
}
}
