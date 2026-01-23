<?php

namespace App\Http\Controllers;

use App\Models\Routine;
use App\Models\Exercise;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class RoutineController extends Controller
  
    {

// Para ver las rutinas guardadas por el usuario
public function index() {
    // Obtenemos las rutinas del usuario autenticado con sus ejercicios cargados
    $rutinas = Routine::with('exercises')
        ->where('user_id', Auth::id())
        ->latest()
        ->get();

    return response()->json($rutinas);
}

        // 1. LLENAR TU DB (Sincronización)
    public function ingestExercises() {
        \Illuminate\Support\Facades\Schema::disableForeignKeyConstraints();
        \App\Models\Exercise::truncate();
        \Illuminate\Support\Facades\Schema::enableForeignKeyConstraints();

    // Usamos la URL que nos acabas de confirmar que funciona
    $response = \Illuminate\Support\Facades\Http::timeout(30)
        ->withoutVerifying()
        ->get("https://wger.de/api/v2/exerciseinfo/?limit=50&language=2");

    $data = $response->json();
    $items = $data['results'] ?? [];
    $count = 0;

    foreach ($items as $item) {
        $nombreFinal = null;
        $descripcionFinal = 'Sin descripción';

        // Lógica para extraer el nombre de la lista de 'translations'
        if (!empty($item['translations'])) {
            foreach ($item['translations'] as $tra) {
                // Priorizamos el nombre si existe
                if (!empty($tra['name'])) {
                    $nombreFinal = $tra['name'];
                    $descripcionFinal = $tra['description'] ?? 'Sin descripción';
                    
                    // Si encontramos el idioma 4 (Español en Wger), nos quedamos con ese y paramos
                    if (($tra['language'] ?? 0) == 4) break; 
                }
            }
        }

        // Solo guardamos si logramos encontrar un nombre
        if ($nombreFinal) {
            \Illuminate\Support\Facades\DB::table('exercises')->insert([
                'wger_id'     => $item['id'],
                'name'        => substr($nombreFinal, 0, 100),
                'description' => substr(strip_tags($descripcionFinal), 0, 250),
                'created_at'  => now(),
                'updated_at'  => now(),
            ]);
            $count++;
        }
    }

    return response()->json([
        'mensaje' => '¡Sincronización Exitosa!',
        'ejercicios_guardados' => $count,
        'total_en_db' => \App\Models\Exercise::count(),
        'ultimo_ejercicio' => $nombreFinal ?? 'Ninguno'
    ]);
}

    // 2. TU API (Servir datos locales)
    public function getMyExercises() {
        return response()->json([
            'info' => 'FitManager Local API',
            'exercises' => Exercise::all()
        ]);
    }

public function getRecommendations() {
    // 1. Intentamos obtener el usuario de forma segura
    $user = auth('sanctum')->user() ?? auth()->user();
    
    // 2. Pillamos TODOS los ejercicios para el buscador (Vital para que no salga vacío)
    $todosLosEjercicios = \App\Models\Exercise::all();

    // 3. Si no hay usuario o no hay perfil, devolvemos genéricos SIN EXPLOTAR
    if (!$user || !$user->profile) {
        return response()->json([
            'info' => 'Inicia sesión y completa tu perfil para recomendaciones personalizadas.',
            'rutinas' => [
                ['nombre' => 'Rutina 1: Explosiva', 'ejercicios' => \App\Models\Exercise::inRandomOrder()->take(6)->get()],
                ['nombre' => 'Rutina 2: Resistencia', 'ejercicios' => \App\Models\Exercise::inRandomOrder()->take(6)->get()],
                ['nombre' => 'Rutina 3: Fuerza', 'ejercicios' => \App\Models\Exercise::inRandomOrder()->take(6)->get()],
            ],
            'todos' => $todosLosEjercicios
        ]);
    }

    // --- LÓGICA DE CALORÍAS (Solo si hay perfil) ---
    $profile = $user->profile;
    $peso = $profile->weight ?? 70;
    $altura = $profile->height ?? 170;
    $sexo = $profile->sex ?? 'Masculino';
    $actividad = $profile->activity ?? 'Poco o ningún ejercicio';
    $edad = 25; 

    if ($sexo === 'Masculino') {
        $tmb = 88.36 + (13.4 * $peso) + (4.8 * $altura) - (5.7 * $edad);
    } else {
        $tmb = 447.59 + (9.2 * $peso) + (3.1 * $altura) - (4.3 * $edad);
    }

    $factores = [
        'Poco o ningún ejercicio' => 1.2,
        'Ejercicio ligero (1-3 días a la semana)' => 1.375,
        'Ejercicio moderado (3-5 días a la semana)' => 1.55,
        'Ejercicio fuerte (6-7 días a la semana)' => 1.725,
        'Ejercicio muy fuerte (dos veces al día, entrenamientos muy duros)' => 1.9
    ];

    $mantenimiento = $tmb * ($factores[$actividad] ?? 1.2);

    // --- GENERACIÓN DE RUTINAS ---
    $queryBase = \App\Models\Exercise::query();
    if ($mantenimiento > 2500) {
        $msg = "Gasto alto (" . round($mantenimiento) . " kcal).";
        $queryBase->where('name', 'like', '%Press%')->orWhere('name', 'like', '%Squat%');
    } else {
        $msg = "Gasto moderado (" . round($mantenimiento) . " kcal).";
        $queryBase->where('description', 'like', '%bodyweight%')->orWhere('name', 'like', '%Salto%');
    }

    $ejerciciosBase = $queryBase->inRandomOrder()->get();
    
    // Si la búsqueda da pocos resultados, rellenamos con aleatorios
    if ($ejerciciosBase->count() < 18) {
        $ejerciciosBase = \App\Models\Exercise::inRandomOrder()->take(30)->get();
    }

    return response()->json([
        'info' => $msg,
        'rutinas' => [
            ['nombre' => 'Rutina 1: Explosiva', 'ejercicios' => $ejerciciosBase->slice(0, 6)->values()],
            ['nombre' => 'Rutina 2: Resistencia', 'ejercicios' => $ejerciciosBase->slice(6, 6)->values()],
            ['nombre' => 'Rutina 3: Fuerza', 'ejercicios' => $ejerciciosBase->slice(12, 6)->values()],
        ],
        'todos' => $todosLosEjercicios
    ]);
}

    // 3. GUARDAR RUTINA (Relacionando con tus datos)
    // En RoutineController.php
public function store(Request $request) {
    // 1. Verificación manual de seguridad
    if (!auth()->check()) {
        return response()->json(['message' => 'Tu sesión ha expirado. Por favor, inicia sesión de nuevo.'], 401);
    }

    $request->validate([
        'name' => 'required|string|max:255',
        'exercises' => 'required|array',
    ]);

    // 2. Crear la rutina vinculada al usuario logueado
    $routine = Routine::create([
        'name' => $request->name,
        'user_id' => auth()->id() 
    ]);

    // 3. Vincular ejercicios
    foreach ($request->exercises as $item) {
        $exercise = Exercise::where('wger_id', $item['exercise_id'])->first();
        if ($exercise) {
            // Usamos un attach simple para asegurar que no falle por falta de columnas
            $routine->exercises()->attach($exercise->id);
        }
    }

    return response()->json(['message' => '¡Rutina guardada con éxito!'], 201);
}

    public function destroy($id)
    {
        // Buscamos la rutina asegurando que sea del usuario
        $routine = Routine::where('user_id', Auth::id())->find($id);

        if (!$routine) {
            return response()->json(['message' => 'Rutina no encontrada'], 404);
        }

        // Eliminamos la relación en la tabla pivote manualmente por si no tienes el "cascade"
        $routine->exercises()->detach();
        $routine->delete();

        return response()->json(['message' => 'Rutina eliminada correctamente'], 200);
    }
}