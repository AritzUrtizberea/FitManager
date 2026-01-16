<?php

namespace App\Http\Controllers;

use App\Models\Routine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoutineController extends Controller
{
    /**
     * Listar rutinas del usuario con sus ejercicios
     */
    public function index(Request $request)
    {
        $routines = $request->user()->routines()->with('exercises')->get();
        return response()->json($routines);
    }

    /**
     * Guardar una nueva rutina (Drag & Drop desde el Front)
     */
    public function store(Request $request)
    {
        // 1. Validamos que nos envíen ejercicios y sus detalles
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'exercises' => 'required|array',
            'exercises.*.exercise_id' => 'required|exists:exercises,id',
            'exercises.*.sets' => 'required|integer|min:1',
            'exercises.*.reps' => 'required|integer|min:1',
            // Puedes añadir 'weight' o 'rest_time' si tu tabla pivote lo tiene
        ]);

        // 2. Crear la Rutina (Cabecera)
        $routine = $request->user()->routines()->create([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        // 3. Preparar los datos para la tabla pivote
        // El Front enviará: [{ "exercise_id": 5, "sets": 4, "reps": 12 }, ...]
        $pivotData = [];
        
        foreach ($request->exercises as $item) {
            $pivotData[$item['exercise_id']] = [
                'sets' => $item['sets'],
                'reps' => $item['reps']
            ];
        }

        // 4. Guardar la relación
        $routine->exercises()->attach($pivotData);

        return response()->json([
            'message' => 'Rutina creada con éxito',
            'routine' => $routine->load('exercises')
        ], 201);
    }

    /**
     * Ver detalle de una rutina
     */
    public function show(Routine $routine)
    {
        if ($routine->user_id !== Auth::id()) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        return response()->json($routine->load('exercises'));
    }

    /**
     * Borrar rutina
     */
    public function destroy(Routine $routine)
    {
        if ($routine->user_id !== Auth::id()) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        $routine->delete();
        return response()->json(['message' => 'Rutina eliminada']);
    }
}