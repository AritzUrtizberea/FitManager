<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    // 1. Carga la vista principal (el HTML que te di antes)
    public function index()
    {
        return view('reviews.index');
    }

    // 2. Carga la vista obligatoria (si salta el middleware)
    public function create()
    {
        return view('reviews.create');
    }

    // 3. ¡VITAL! Devuelve la lista de reseñas en JSON para que JS las pinte
// En ReviewController.php

public function list()
{
    $userId = Auth::id();
    $hasReview = Review::where('user_id', $userId)->exists();

    // CASO 1: NO HA COMENTADO -> BLOQUEADO
    if (!$hasReview) {
        return response()->json([
            'locked' => true, // <--- ESTO ES LO IMPORTANTE
            'current_user_id' => $userId,
            'reviews' => [
                // Reseñas FALSAS para que se vea algo de fondo borroso
                ['user' => ['name' => 'Usuario'], 'rating' => 5, 'comment' => 'Texto oculto...', 'created_at' => now()],
                ['user' => ['name' => 'Ana'], 'rating' => 4, 'comment' => 'Debes comentar para ver.', 'created_at' => now()],
                ['user' => ['name' => 'FitUser'], 'rating' => 5, 'comment' => 'Bloqueado.', 'created_at' => now()],
            ]
        ]);
    }

    // CASO 2: YA COMENTÓ -> DESBLOQUEADO
    $reviews = Review::with('user')->latest()->get();
    
    return response()->json([
        'locked' => false,
        'current_user_id' => $userId,
        'reviews' => $reviews
    ]);
}

    // 4. Guarda la reseña y devuelve JSON
    public function store(Request $request)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|max:500',
        ]);

        // Verificar si ya tiene reseña (opcional, para evitar duplicados)
        $existing = Review::where('user_id', Auth::id())->first();
        if ($existing) {
             // Si quieres permitir editar, actualiza aquí. Si no, lanza error.
             $existing->update($request->all());
             return response()->json(['message' => 'Reseña actualizada']);
        }

        Review::create([
            'user_id' => Auth::id(),
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        return response()->json(['message' => 'Reseña creada correctamente']);
    }
    
    // 5. Borrar reseña
    public function destroy($id)
    {
        $review = Review::where('id', $id)->where('user_id', Auth::id())->first();
        
        if ($review) {
            $review->delete();
            return response()->json(['message' => 'Eliminado']);
        }
        
        return response()->json(['message' => 'No autorizado'], 403);
    }
}