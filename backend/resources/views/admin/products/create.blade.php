@extends('layouts.admin')

@section('content')
    <div class="card" style="max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #eee; border-radius: 8px;">
        <h2>Añadir Nuevo Producto</h2>

        <form action="{{ route('admin.products.store') }}" method="POST">
            @csrf <div style="margin-bottom: 15px;">
                <label>Nombre del Alimento</label>
                <input type="text" name="name" class="browser-default" style="width: 100%; padding: 8px; margin-top: 5px;"
                    required>
            </div>

            <div class="mb-4">
                <label for="category_id" class="block text-gray-700 font-bold mb-2">Categoría</label>
                <select name="category_id" id="category_id"
                    class="w-full border rounded px-3 py-2 text-gray-700 focus:outline-none focus:border-blue-500" required>
                    <option value="">-- Selecciona una categoría --</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                <div style="margin-bottom: 15px;">
                    <label>Calorías (kcal)</label>
                    <input type="number" name="calories" class="browser-default" style="width: 100%; padding: 8px;"
                        required>
                </div>
                <div style="margin-bottom: 15px;">
                    <label>Proteínas (g)</label>
                    <input type="number" step="0.1" name="protein" class="browser-default"
                        style="width: 100%; padding: 8px;">
                </div>
                <div style="margin-bottom: 15px;">
                    <label>Carbohidratos (g)</label>
                    <input type="number" step="0.1" name="carbohydrates" class="browser-default"
                        style="width: 100%; padding: 8px;">
                </div>
                <div style="margin-bottom: 15px;">
                    <label>Grasas (g)</label>
                    <input type="number" step="0.1" name="fats" class="browser-default" style="width: 100%; padding: 8px;">
                </div>
            </div>

            <button type="submit"
                style="background: #4CAF50; color: white; border: none; padding: 10px 20px; cursor: pointer; border-radius: 4px;">
                Guardar Producto
            </button>
            <a href="{{ route('admin.products.index') }}"
                style="margin-left: 10px; color: #666; text-decoration: none;">Cancelar</a>
        </form>
    </div>
@endsection