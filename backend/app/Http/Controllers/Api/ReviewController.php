<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Review; // Asegúrate de tener esto arriba

class ReviewController extends Controller
{
    // Función para LEER (Ya la tienes)
    public function index()
    {
        return response()->json(Review::with('user')->latest()->get());
    }

    // --- AÑADE ESTA FUNCIÓN NUEVA ---
    // Función para GUARDAR (La que te falta)
    public function store(Request $request)
    {
        // 1. Validamos que lo que nos envían no esté vacío
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|max:500',
        ]);

        // 2. Creamos la reseña en la Base de Datos
        $review = Review::create([
            // TRUCO: Si no tienes sistema de login aún, pon 'user_id' => 1
            // Si ya tienes login funcionando, usa: $request->user()->id
            'user_id' => $request->user() ? $request->user()->id : 1, 
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        // 3. Devolvemos la reseña creada con éxito
        return response()->json([
            'message' => 'Reseña guardada correctamente',
            'review' => $review->load('user') // Cargamos al usuario para pintarlo al momento
        ], 201);
    }
}