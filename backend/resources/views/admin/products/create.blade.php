@extends('layouts.admin')

@section('content')
<div class="container py-4">
    <div class="card shadow-sm border-0 mx-auto" style="max-width: 600px;">
        <div class="card-body p-4">
            <h2 class="h4 fw-bold mb-4">Añadir Nuevo Producto</h2>

            <form action="{{ route('admin.products.store') }}" method="POST">
                @csrf 
                
                <div class="mb-3">
                    <label class="form-label fw-semibold">Nombre del Alimento</label>
                    <input type="text" name="name" class="form-control" placeholder="Ej: Pechuga de Pollo" required>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold text-gray-700">Categoría</label>
                    <select name="category_id" class="form-select" required>
                        <option value="">-- Selecciona una categoría --</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="row g-3">
                    <div class="col-12 col-sm-6">
                        <label class="form-label fw-semibold">Calorías (kcal)</label>
                        <input type="number" name="kcal" class="form-control" required>
                    </div>

                    <div class="col-12 col-sm-6">
                        <label class="form-label fw-semibold">Proteínas (g)</label>
                        <input type="number" step="0.1" name="proteins" class="form-control">
                    </div>

                    <div class="col-12 col-sm-6">
                        <label class="form-label fw-semibold">Carbohidratos (g)</label>
                        <input type="number" step="0.1" name="carbs" class="form-control">
                    </div>

                    <div class="col-12 col-sm-6">
                        <label class="form-label fw-semibold">Grasas (g)</label>
                        <input type="number" step="0.1" name="fats" class="form-control">
                    </div>
                </div>

                <div class="mt-4 d-flex gap-2">
                    <button type="submit" class="btn btn-success px-4">
                        Guardar Producto
                    </button>
                    <a href="{{ route('admin.products.index') }}" class="btn btn-light border">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection