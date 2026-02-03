@extends('layouts.admin')

@section('content')
<div class="container py-4">
    <div class="mx-auto" style="max-width: 600px;">
        <h2 class="h3 fw-bold mb-4 text-dark">Nuevo Ejercicio</h2>

        <div class="card shadow-sm border-0 rounded-3">
            <div class="card-body p-3 p-md-4">
                <form action="{{ route('admin.exercises.store') }}" method="POST">
                    @csrf
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">Nombre del Ejercicio</label>
                        <input type="text" name="name" class="form-control p-2" placeholder="Ej: Press de Banca" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Grupo Muscular Principal</label>
                        <select name="muscle_group" class="form-select p-2" required>
                            <option value="" disabled selected>Selecciona un músculo...</option>
                            @foreach(['Pecho', 'Espalda', 'Pierna', 'Hombro', 'Bíceps', 'Tríceps', 'Abdomen', 'Cardio', 'General'] as $m)
                                <option value="{{ $m }}">{{ $m }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold">Descripción / Notas</label>
                        <textarea name="description" rows="4" class="form-control" placeholder="Breve explicación de la técnica..."></textarea>
                    </div>

                    <div class="d-grid d-md-flex gap-2">
                        <button type="submit" class="btn btn-success px-4 fw-bold order-2 order-md-1">
                            Guardar Ejercicio
                        </button>
                        <a href="{{ route('admin.exercises.index') }}" class="btn btn-outline-secondary px-4 order-1 order-md-2">
                            Cancelar
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection