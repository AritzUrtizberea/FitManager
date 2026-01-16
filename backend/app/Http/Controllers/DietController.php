<?php

namespace App\Http\Controllers;

use App\Models\Diet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Importante para sacar el usuario

class DietController extends Controller
{
    /**
     * Listar las dietas del usuario logueado
     */
    public function index(Request $request)
    {
        // Solo devolvemos las dietas que pertenecen al usuario actual
        // Usamos 'with' para traer los productos y ver cuántos tiene cada dieta
        $diets = $request->user()->diets()->with('products')->get();
        
        return response()->json($diets);
    }

    /**
     * Guardar una nueva dieta con sus productos
     */
    public function store(Request $request)
    {
        // 1. Validación de los datos que vienen del Front
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'products' => 'required|array', // Debe ser una lista
            'products.*.product_id' => 'required|exists:products,id',
            'products.*.amount' => 'required|integer|min:1', // Gramos
        ]);

        // 2. Crear la Dieta base (Cabecera)
        // Usamos la relación para que se asigne el user_id automáticamente
        $diet = $request->user()->diets()->create([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        // 3. Asociar los productos (La Magia de la Tabla Pivote)
        // Preparamos un array para el método sync/attach
        $productsData = [];
        
        foreach ($request->products as $product) {
            // La clave es el ID del producto, el valor son los datos extra (amount)
            $productsData[$product['product_id']] = ['amount' => $product['amount']];
        }

        // attach() guarda las relaciones en la tabla 'diet_product'
        $diet->products()->attach($productsData);

        return response()->json([
            'message' => 'Dieta creada con éxito',
            'diet' => $diet->load('products') // Devolvemos la dieta con sus productos
        ], 201);
    }

    /**
     * Mostrar una dieta específica en detalle
     */
    public function show(Diet $diet)
    {
        // Verificamos que la dieta sea del usuario (Seguridad)
        if ($diet->user_id !== Auth::id()) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        // Cargamos los productos y el dato 'amount' de la tabla pivote
        return response()->json($diet->load('products'));
    }

    /**
     * Eliminar una dieta
     */
    public function destroy(Diet $diet)
    {
        if ($diet->user_id !== Auth::id()) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        $diet->delete(); // Laravel borra automáticamente las relaciones en la tabla pivote

        return response()->json(['message' => 'Dieta eliminada']);
    }
}