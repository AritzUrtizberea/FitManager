@extends('layouts.admin')

@section('content')
    <div style="max-width: 600px; margin: 0 auto;">
        <h2 style="margin-bottom: 20px;">Nuevo Ejercicio</h2>

        <div class="card"
            style="background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
            <form action="{{ route('admin.exercises.store') }}" method="POST">
                @csrf
                @if ($errors->any())
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
                        <strong class="font-bold">Hay errores en el formulario:</strong>
                        <ul class="list-disc list-inside mt-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: bold;">Nombre del Ejercicio</label>
                    <input type="text" name="name" placeholder="Ej: Press de Banca" required
                        style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;">
                </div>

                <div style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: bold;">Grupo Muscular Principal</label>
                    <select name="muscle_group" required
                        style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; background: white;">
                        <option value="" disabled selected>Selecciona un músculo...</option>
                        <option value="Pecho">Pecho</option>
                        <option value="Espalda">Espalda</option>
                        <option value="Pierna">Pierna</option>
                        <option value="Hombro">Hombro</option>
                        <option value="Bíceps">Bíceps</option>
                        <option value="Tríceps">Tríceps</option>
                        <option value="Abdomen">Abdomen</option>
                        <option value="Cardio">Cardio</option>
                    </select>
                </div>

                <div style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: bold;">Descripción / Notas</label>
                    <textarea name="description" rows="4" placeholder="Breve explicación de la técnica..."
                        style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;"></textarea>
                </div>

                <div style="display: flex; gap: 10px; margin-top: 30px;">
                    <button type="submit"
                        style="background: #4CAF50; color: white; border: none; padding: 12px 24px; border-radius: 4px; cursor: pointer; font-weight: bold;">
                        Guardar Ejercicio
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