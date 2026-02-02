<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    // --- API (Para que el JavaScript lea las reseñas) ---
    public function index()
    {
        $reviews = Review::with('user')->latest()->get();
        return response()->json($reviews);
    }

    // --- WEB (Para mostrar la página) ---
    public function create()
    {
        // ERROR CORREGIDO: Antes tenías un redirect aquí.
        // Ahora cargamos la vista correctamente.
        return view('reviews.create');
    }

    // --- WEB (Para guardar la reseña) ---
    public function store(Request $request)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|max:500',
        ]);

        Review::create([
            'user_id' => auth()->id(),
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        // ERROR CORREGIDO: Redirigimos a 'reviews.index' que es como llamaste a la ruta en web.php
        return redirect()->route('reviews.index')->with('success', '¡Gracias! Tu reseña se ha publicado.');
    }
}