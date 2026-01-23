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

    public function store(Request $request)
    {
        // 1. Validar que al menos tenga nombre y categoría
        $request->validate([
            'name' => 'required',
            'category_id' => 'required',
        ]);

        // 2. Crear el producto en la base de datos
        // Usamos $request->all() porque ya protegimos los campos en el Modelo Product.php
        $product = Product::create($request->all());

        // 3. Devolver el producto creado (formato JSON)
        return response()->json($product, 201);
    }
    public function index()
    {
        return response()->json(Product::with('category')->paginate(20));
    }
}