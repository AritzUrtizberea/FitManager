@extends('layouts.admin')

@section('content')
<div class="card" style="max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #eee; border-radius: 8px;">
    <h2>Editar Producto</h2>

    <form action="{{ route('admin.products.update', $product->id) }}" method="POST">
        @csrf 
        @method('PUT') 
        
        <div style="margin-bottom: 15px;">
            <label>Nombre del Alimento</label>
            <input type="text" name="name" value="{{ $product->name }}" class="browser-default" style="width: 100%; padding: 8px;" required>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
            
            <div style="margin-bottom: 15px;">
                <label>Calorías (kcal)</label>
                <input type="number" name="kcal" value="{{ $product->kcal }}" class="browser-default" style="width: 100%; padding: 8px;" required>
            </div>

            <div style="margin-bottom: 15px;">
                <label>Proteínas (g)</label>
                <input type="number" step="0.1" name="proteins" value="{{ $product->proteins }}" class="browser-default" style="width: 100%; padding: 8px;">
            </div>

            <div style="margin-bottom: 15px;">
                <label>Carbohidratos (g)</label>
                <input type="number" step="0.1" name="carbs" value="{{ $product->carbs }}" class="browser-default" style="width: 100%; padding: 8px;">
            </div>

            <div style="margin-bottom: 15px;">
                <label>Grasas (g)</label>
                <input type="number" step="0.1" name="fats" value="{{ $product->fats }}" class="browser-default" style="width: 100%; padding: 8px;">
            </div>
        </div>

        <button type="submit" style="background: #2196F3; color: white; border: none; padding: 10px 20px; cursor: pointer; border-radius: 4px;">
            Actualizar Producto
        </button>
        <a href="{{ route('admin.products.index') }}" style="margin-left: 10px; color: #666; text-decoration: none;">Cancelar</a>
    </form>
</div>
@endsection