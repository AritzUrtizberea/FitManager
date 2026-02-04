<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckFirstReview
{
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Verificamos si el usuario está logueado
        if (Auth::check()) {
            
            // 2. Contamos cuántas reseñas tiene ese usuario
            // Asumimos que la relación en el modelo User se llama 'reviews'
            $count = Auth::user()->reviews()->count();

            // 3. Si tiene 0 reseñas, lo expulsamos a la zona de "Crear Reseña"
            if ($count === 0) {
                // Usamos 'reviews.create' como nombre de la ruta (aunque no exista aún)
                // Le pasamos un mensaje flash por si quieres mostrarlo
                return redirect()->route('reviews.create')
                    ->with('warning', '🔒 Para ver las reseñas de los demás, primero debes escribir tu primera reseña.');
            }
        }

        return $next($request);
    }
}