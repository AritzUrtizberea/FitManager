<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\WeeklyPlan;            // <--- Importante: Importar el Modelo
use Illuminate\Support\Facades\Auth;  // <--- Importante: Para saber quién es el usuario

class NutritionController extends Controller
{
    public function index()
{
    // 1. Verificamos si el usuario realmente está autenticado
    $user = Auth::user();

    if (!$user) {
        return response()->json([
            'error' => 'Usuario no autenticado. Asegúrate de estar logueado.',
            'debug_note' => 'Si estás logueado en Blade, revisa que el fetch envíe las cookies.'
        ], 401);
    }

    // 2. Buscamos los planes
    try {
        $weeklyPlans = WeeklyPlan::where('user_id', $user->id)->get();

        if ($weeklyPlans->isEmpty()) {
            $days = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo'];

            foreach ($days as $day) {
                WeeklyPlan::create([
                    'user_id' => $user->id,
                    'day' => $day,
                    'meals_summary' => 'Planificar comidas...',
                    'calories' => 0,
                    'status' => 'pending'
                ]);
            }
            $weeklyPlans = WeeklyPlan::where('user_id', $user->id)->get();
        }

        return response()->json($weeklyPlans);

    } catch (\Exception $e) {
        // Esto te dirá si el error es de base de datos (columna inexistente, etc.)
        return response()->json(['error' => $e->getMessage()], 500);
    }
}

public function updateStatus(Request $request, $id)
{
    // Buscamos el plan por ID y nos aseguramos que pertenezca al usuario actual
    $plan = WeeklyPlan::where('id', $id)->where('user_id', Auth::id())->first();

    if (!$plan) {
        return response()->json(['error' => 'Plan no encontrado'], 404);
    }

    // Validamos que el estado sea uno de los permitidos
    $request->validate([
        'status' => 'required|in:pending,completed'
    ]);

    // Guardamos
    $plan->status = $request->status;
    $plan->save();

    return response()->json(['message' => 'Estado actualizado', 'status' => $plan->status]);
}

public function saveDayPlan(Request $request)
{
    $request->validate([
        'day' => 'required|string',
        'calories' => 'required|integer',
        'summary' => 'nullable|string'
    ]);

    // Buscamos el plan de ese día para el usuario conectado
    $plan = WeeklyPlan::where('user_id', Auth::id())
                      ->where('day', $request->day)
                      ->first();

    if (!$plan) {
        return response()->json(['error' => 'Plan no encontrado'], 404);
    }

    // Actualizamos los datos
    $plan->calories = $request->calories;
    $plan->meals_summary = $request->summary;
    $plan->save();

    return response()->json(['message' => 'Plan guardado correctamente']);
}
}