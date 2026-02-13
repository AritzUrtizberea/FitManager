<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function index()
    {
        return view('reviews.index');
    }

    public function list()
    {
        $userId = Auth::id();
        // El sistema se desbloquea si existe AL MENOS una reseña
        $hasReview = Review::where('user_id', $userId)->exists();

        if (!$hasReview) {
            return response()->json([
                'locked' => true,
                'current_user_id' => $userId,
                'reviews' => [
                    ['user' => ['name' => 'Usuario'], 'rating' => 5, 'comment' => 'Contenido bloqueado...', 'created_at' => now()],
                    ['user' => ['name' => 'Ana'], 'rating' => 4, 'comment' => 'Escribe una reseña para ver más.', 'created_at' => now()],
                ]
            ]);
        }

        // Si ya tiene al menos una, enviamos todas las reseñas reales
        $reviews = Review::with('user')->latest()->get();
        
        return response()->json([
            'locked' => false,
            'current_user_id' => $userId,
            'reviews' => $reviews
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|max:500',
        ]);

        // ELIMINAMOS la verificación de $existing. 
        // Simplemente creamos una nueva cada vez.
        Review::create([
            'user_id' => Auth::id(),
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        return response()->json(['message' => 'Nueva reseña publicada']);
    }

    public function destroy($id)
    {
        // Solo el dueño puede borrar su reseña específica
        $review = Review::where('id', $id)->where('user_id', Auth::id())->first();
        
        if ($review) {
            $review->delete();
            return response()->json(['message' => 'Eliminado']);
        }
        
        return response()->json(['message' => 'No autorizado'], 403);
    }
}