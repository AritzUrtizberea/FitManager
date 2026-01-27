<?php

namespace App\Http\Controllers;

use App\Models\Routine;
use App\Models\Exercise;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class RoutineController extends Controller
{
    // --- 1. MOSTRAR RUTINAS GUARDADAS ---
    public function index() {
        $rutinas = Routine::with('exercises')
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        return response()->json($rutinas);
    }

    // --- 2. SINCRONIZACIÓN (INGEST) ---
    // He mejorado esta función para que devuelva datos útiles si la llamamos internamente
    public function ingestExercises() {
        Schema::disableForeignKeyConstraints();
        Exercise::truncate();
        Schema::enableForeignKeyConstraints();

        try {
            $response = Http::timeout(30)
                ->withoutVerifying()
                ->get("https://wger.de/api/v2/exerciseinfo/?limit=50&language=2");

            $data = $response->json();
            $items = $data['results'] ?? [];
            $count = 0;
            $nombreFinal = null;

            foreach ($items as $item) {
                $nombreFinal = null;
                $descripcionFinal = 'Sin descripción';

                if (!empty($item['translations'])) {
                    foreach ($item['translations'] as $tra) {
                        if (!empty($tra['name'])) {
                            $nombreFinal = $tra['name'];
                            $descripcionFinal = $tra['description'] ?? 'Sin descripción';
                            if (($tra['language'] ?? 0) == 4) break; 
                        }
                    }
                }

                if ($nombreFinal) {
                    DB::table('exercises')->insert([
                        'wger_id'     => $item['id'],
                        'name'        => substr($nombreFinal, 0, 100),
                        'description' => substr(strip_tags($descripcionFinal), 0, 250),
                        'created_at'  => now(),
                        'updated_at'  => now(),
                    ]);
                    $count++;
                }
            }

            // Si se llama desde navegador (ruta API), devolvemos JSON
            if (request()->wantsJson() && request()->is('api/*')) {
                 return response()->json([
                    'mensaje' => '¡Sincronización Exitosa!',
                    'ejercicios_guardados' => $count,
                    'total_en_db' => Exercise::count(),
                    'ultimo_ejercicio' => $nombreFinal ?? 'Ninguno'
                ]);
            }
            
            // Si lo llamamos internamente, devolvemos el número
            return $count;

        } catch (\Exception $e) {
            // Si falla la conexión a wger, no rompemos la app
            return 0; 
        }
    }

    // --- 3. TU API LOCAL (Recuperada) ---
    public function getMyExercises() {
        return response()->json([
            'info' => 'FitManager Local API',
            'exercises' => Exercise::all()
        ]);
    }

    // --- 4. RECOMENDACIONES (Con el Auto-Fix incluido) ---
    public function getRecommendations() {
        // [NUEVO] Si la base de datos está vacía, la llenamos automáticamente
        if (Exercise::count() == 0) {
            $this->ingestExercises();
        }

        $user = auth('sanctum')->user() ?? auth()->user();
        $todosLosEjercicios = Exercise::all();

        // Si no hay usuario
        if (!$user || !$user->profile) {
            return response()->json([
                'info' => 'Inicia sesión y completa tu perfil para recomendaciones personalizadas.',
                'rutinas' => [
                    ['nombre' => 'Rutina 1: Explosiva', 'ejercicios' => Exercise::inRandomOrder()->take(6)->get()],
                    ['nombre' => 'Rutina 2: Resistencia', 'ejercicios' => Exercise::inRandomOrder()->take(6)->get()],
                    ['nombre' => 'Rutina 3: Fuerza', 'ejercicios' => Exercise::inRandomOrder()->take(6)->get()],
                ],
                'todos' => $todosLosEjercicios
            ]);
        }

        // Lógica de Calorías original tuya
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

        $queryBase = Exercise::query();
        if ($mantenimiento > 2500) {
            $msg = "Gasto alto (" . round($mantenimiento) . " kcal).";
            $queryBase->where('name', 'like', '%Press%')->orWhere('name', 'like', '%Squat%');
        } else {
            $msg = "Gasto moderado (" . round($mantenimiento) . " kcal).";
            $queryBase->where('description', 'like', '%bodyweight%')->orWhere('name', 'like', '%Salto%');
        }

        $ejerciciosBase = $queryBase->inRandomOrder()->get();
        
        if ($ejerciciosBase->count() < 18) {
            $ejerciciosBase = Exercise::inRandomOrder()->take(30)->get();
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

    // --- 5. GUARDAR RUTINA ---
    public function store(Request $request) {
        if (!auth()->check()) {
            return response()->json(['message' => 'Tu sesión ha expirado. Por favor, inicia sesión de nuevo.'], 401);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'exercises' => 'required|array',
        ]);

        $routine = Routine::create([
            'name' => $request->name,
            'user_id' => auth()->id() 
        ]);

        foreach ($request->exercises as $item) {
            // Buscamos por wger_id O por id normal para que no falle nunca
            $exercise = Exercise::where('wger_id', $item['exercise_id'])
                                ->orWhere('id', $item['exercise_id'])
                                ->first();
            if ($exercise) {
                $routine->exercises()->attach($exercise->id);
            }
        }

        return response()->json(['message' => '¡Rutina guardada con éxito!'], 201);
    }

    // --- 6. ELIMINAR RUTINA ---
    public function destroy($id)
    {
        $routine = Routine::where('user_id', Auth::id())->find($id);

        if (!$routine) {
            return response()->json(['message' => 'Rutina no encontrada'], 404);
        }

        $routine->exercises()->detach();
        $routine->delete();

        return response()->json(['message' => 'Rutina eliminada correctamente'], 200);
    }
}