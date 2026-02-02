<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\Category;

class ProductController extends Controller
{
    // LISTAR PRODUCTOS (Ya tienes la vista index)
    public function index()
    {
        $products = Product::paginate(10); // Paginamos de 10 en 10
        return view('admin.products.index', compact('products'));
    }

    // MOSTRAR FORMULARIO DE CREAR
    public function create()
    {
        // Traemos todas las categorías de la base de datos
        $categories = Category::all();

        // Se las pasamos a la vista
        return view('admin.products.create', compact('categories'));
    }

    // GUARDAR EN BASE DE DATOS
    public function store(Request $request)
{
    // 1. Validamos
    $request->validate([
        'name' => 'required',
        'category_id' => 'required|exists:categories,id',
        'carbohydrates' => 'nullable|numeric',
        'fats' => 'nullable|numeric', // Dejamos esto como nullable para que no falle la validación
    ]);

    // 2. Preparamos los datos
    $data = $request->all();

    // TRUCO: Si no han escrito nada, asignamos 0.
    // (Hacemos lo mismo para carbohidratos, por si acaso)
    if (empty($data['fats'])) {
        $data['fats'] = 0;
    }
    
    if (empty($data['carbohydrates'])) {
        $data['carbohydrates'] = 0;
    }

    // 3. Creamos el producto usando $data (que ya tiene los ceros si hacían falta)
    Product::create($data);

    // 4. Redirigimos
    return redirect()->route('admin.products.index')
        ->with('success', 'Producto creado correctamente.');
}
    // MOSTRAR FORMULARIO DE EDICIÓN
    public function edit(Product $product)
    {
        return view('admin.products.edit', compact('product'));
    }

    // ACTUALIZAR EN BASE DE DATOS
    public function update(Request $request, Product $product)
    {
        // 1. Validamos
        $request->validate([
        'name' => 'required|string|max:255',
        'kcal' => 'required|integer',       // <--- Cambiado de 'calories' a 'kcal'
        'proteins' => 'nullable|numeric',   // <--- Añadido (acepta decimales)
        'carbs' => 'nullable|numeric',      // <--- Añadido (acepta decimales)
        'fats' => 'nullable|numeric',       // <--- Añadido (acepta decimales)
    ]);

        // 2. Actualizamos
        $product->update($request->all());

        // 3. Redirigimos
        return redirect()->route('admin.products.index')
            ->with('success', 'Producto actualizado correctamente.');
    }

    // ELIMINAR
    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()->route('admin.products.index')
            ->with('success', 'Producto eliminado.');
    }
}