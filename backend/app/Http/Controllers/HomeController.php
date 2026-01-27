<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        
        // Ejemplo: Si quisieras mandar las calorías diarias recomendadas al Home
        // Supongamos que tienes una lógica simple aquí (o en un Servicio)
        $calories = 2000; // Valor por defecto
        
        if ($user->profile) {
            // Aquí podrías implementar la fórmula de Harris-Benedict más adelante
            if ($user->profile->gender === 'male') {
                $calories = 2500; 
            }
        }

        return response()->json([
            'welcome_message' => 'Hola de nuevo, ' . $user->name,
            'daily_calories' => $calories,
            'last_login' => now(), // Ejemplo de dato extra
        ]);
    }
}