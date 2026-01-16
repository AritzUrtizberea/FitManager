<?php
namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Buscar productos por nombre para el buscador de la dieta
     */
    public function search(Request $request)
    {
        // Validamos que venga un texto de búsqueda
        $query = $request->input('query');

        if (!$query) {
            return response()->json([]);
        }

        // Buscamos productos que contengan ese texto en el nombre
        // 'limit(10)' es importante para no saturar la lista si hay miles
        $products = Product::where('name', 'like', "%{$query}%")
                            ->with('category') // Traemos la categoría también
                            ->limit(20)
                            ->get();

        return response()->json($products);
    }

    /**
     * Listar todos (opcional, por si quieres ver el catálogo entero)
     */
    public function index()
    {
        return response()->json(Product::with('category')->paginate(20));
    }
}