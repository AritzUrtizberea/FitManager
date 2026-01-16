<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    // GET: /api/profile
    public function show(Request $request)
    {
        // Obtenemos el usuario que está haciendo la petición
        $user = $request->user(); 
        
        // Cargamos la relación 'profile' (edad, peso, etc.)
        $user->load('profile');

        // Devolvemos JSON para que JS lo pinte
        return response()->json([
            'status' => 'success',
            'user' => $user, // Esto incluye nombre, email y el objeto profile dentro
        ]);
    }

    // PUT: /api/profile
    public function update(Request $request)
    {
        $user = $request->user();
        
        // Validamos solo los campos biométricos
        $validated = $request->validate([
            'weight' => 'numeric|min:20|max:300',
            'height' => 'numeric|min:50|max:300',
            'physical_activity' => 'string',
            // ... otros campos
        ]);

        // Actualizamos la tabla profiles
        $user->profile()->update($validated);

        return response()->json([
            'message' => 'Perfil actualizado correctamente',
            'profile' => $user->profile
        ]);
    }
}