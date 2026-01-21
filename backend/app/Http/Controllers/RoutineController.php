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
    $user = auth()->user();
    $profile = $user->profile;

    // 1. Pillamos TODOS los ejercicios para el buscador de "Mi Rutina"
    $todosLosEjercicios = \App\Models\Exercise::all();

    if (!$profile) {
        return response()->json([
            'info' => 'Completa tu perfil para mejores recomendaciones.',
            'rutinas' => [
                ['nombre' => 'Sugerencia A', 'ejercicios' => \App\Models\Exercise::inRandomOrder()->take(6)->get()],
                ['nombre' => 'Sugerencia B', 'ejercicios' => \App\Models\Exercise::inRandomOrder()->take(6)->get()],
            ],
            'todos' => $todosLosEjercicios
        ]);
    }

    // --- CÁLCULO DE CALORÍAS (Tu lógica actual) ---
    $peso = $profile->weight;
    $altura = $profile->height;
    $sexo = $profile->sex;
    $actividad = $profile->activity;
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

    // --- GENERACIÓN DE 3 RUTINAS SEPARADAS ---
    // Filtramos según el objetivo pero creamos 3 sets distintos
    $queryBase = \App\Models\Exercise::query();
    if ($mantenimiento > 2500) {
        $msg = "Gasto alto (" . round($mantenimiento) . " kcal).";
        $queryBase->where('name', 'like', '%Press%')->orWhere('name', 'like', '%Squat%');
    } else {
        $msg = "Gasto moderado (" . round($mantenimiento) . " kcal).";
        $queryBase->where('description', 'like', '%bodyweight%')->orWhere('name', 'like', '%Salto%');
    }

    // Si el filtro es muy seco, pillamos al azar
    $ejerciciosBase = $queryBase->inRandomOrder()->get();
    if ($ejerciciosBase->count() < 18) {
        $ejerciciosBase = \App\Models\Exercise::inRandomOrder()->take(30)->get();
    }

    // Dividimos en 3 grupos de 6 ejercicios cada uno
    return response()->json([
        'info' => $msg,
        'rutinas' => [
            ['nombre' => 'Rutina 1: Explosiva', 'ejercicios' => $ejerciciosBase->slice(0, 6)->values()],
            ['nombre' => 'Rutina 2: Resistencia', 'ejercicios' => $ejerciciosBase->slice(6, 6)->values()],
            ['nombre' => 'Rutina 3: Fuerza', 'ejercicios' => $ejerciciosBase->slice(12, 6)->values()],
        ],
        'todos' => $todosLosEjercicios // Esto es para que en "Mi Rutina" puedas buscar CUALQUIERA
    ]);
}

    // 3. GUARDAR RUTINA (Relacionando con tus datos)
    // En RoutineController.php
    public function store(Request $request) {
        $request->validate([
            'name' => 'required|string|max:255',
            'exercises' => 'required|array',
        ]);

        $routine = Routine::create([
            'name' => $request->name,
            'user_id' => Auth::id() // Asegúrate de que el usuario esté logueado
        ]);

        foreach ($request->exercises as $item) {
            $exercise = Exercise::where('wger_id', $item['exercise_id'])->first();
            if ($exercise) {
                $routine->exercises()->attach($exercise->id, [
                    'sets' => 3,
                    'reps' => 10,
                    'rest_time' => 60 // DEBES enviar esto para evitar error de DB
                ]);
            }
        }

        return response()->json(['message' => 'Rutina guardada con éxito'], 201);
    }
}