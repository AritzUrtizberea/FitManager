@extends('layouts.admin')

@section('content')
    <div style="max-width: 600px; margin: 0 auto;">
        <h2 style="margin-bottom: 20px;">Editar Ejercicio</h2>

        <div class="card"
            style="background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
            <form action="{{ route('admin.exercises.update', $exercise->id) }}" method="POST">
                @csrf
                @method('PUT') <div style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: bold;">Nombre del Ejercicio</label>
                    <input type="text" name="name" value="{{ $exercise->name }}" required
                        style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;">
                </div>

                <div style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: bold;">Grupo Muscular Principal</label>
                    <select name="muscle_group" required
                        style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; background: white;">

                        {{-- OPCIÓN 1: Añadimos esto al principio para capturar los NULL --}}
                        <option value="" {{ $exercise->muscle_group == null ? 'selected' : '' }} disabled>
                            -- Selecciona Grupo Muscular --
                        </option>

                        @php $options = ['Pecho', 'Espalda', 'Pierna', 'Hombro', 'Bíceps', 'Tríceps', 'Abdomen', 'Cardio','General']; @endphp

                        @foreach($options as $option)
                            <option value="{{ $option }}" {{ $exercise->muscle_group == $option ? 'selected' : '' }}>
                                {{ $option }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: bold;">Descripción / Notas</label>
                    <textarea name="description" rows="4"
                        style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;">{{ $exercise->description }}</textarea>
                </div>

                <div style="display: flex; gap: 10px; margin-top: 30px;">
                    <button type="submit"
                        style="background: #2196F3; color: white; border: none; padding: 12px 24px; border-radius: 4px; cursor: pointer; font-weight: bold;">
                        Actualizar
                    </button>
                    <a href="{{ route('admin.exercises.index') }}"
                        style="padding: 12px 24px; color: #666; text-decoration: none; border: 1px solid #ddd; border-radius: 4px;">
                        Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection