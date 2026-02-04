<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Profile; // <--- Importamos el modelo directamente

class UpdateStreak
{
    public function handle(Request $request, Closure $next): Response
    {
        // 1. ¿Hay alguien logueado?
        $user = auth()->user();

        if ($user) {
            // 2. Buscamos el perfil MANUALMENTE (más seguro para probar)
            $profile = Profile::where('user_id', $user->id)->first();

            if ($profile) {
                // 3. FORZAMOS EL CAMBIO
                $profile->streak = 55; 
                $profile->save(); // Guardar cambios
                
                // Si quieres confirmar que funciona, descomenta la siguiente línea para ver pantalla negra:
                // dd("HE ENCONTRADO EL PERFIL Y PUESTO LA RACHA EN 55");
            } else {
                // Si sale esto, es que el usuario 2 no tiene perfil en la tabla 'profiles'
                dd("ERROR CRÍTICO: El usuario con ID " . $user->id . " NO tiene perfil.");
            }
        }

        return $next($request);
    }
}