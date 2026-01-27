@extends('layouts.admin')

@section('content')

<div class="flex justify-between items-center mb-6 p-5 bg-white rounded-xl shadow-sm border border-gray-100">
    
    <div>
        <h1 class="text-3xl font-bold text-gray-900 flex items-center gap-3">
            <span class="text-indigo-600 bg-indigo-50 p-2 rounded-lg">
                <i class="fas fa-dumbbell"></i>
            </span>
            Gestión de Ejercicios
        </h1>
        <p class="text-gray-500 text-sm mt-2 ml-1">
            Administra la biblioteca de entrenamientos.
        </p>
    </div>

    <a href="{{ route('admin.exercises.create') }}" 
       class="group flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold py-3 px-6 rounded-lg shadow-md transition-all duration-200 transform hover:-translate-y-0.5"
       style="background-color: #059669; color: #ffffff; text-decoration: none;">
       
       <div class="bg-emerald-500 p-1 rounded-full group-hover:bg-emerald-600 transition">
           <i class="fas fa-plus text-xs"></i>
       </div>
       <span>Nuevo Ejercicio</span>
    </a>

</div>

<div class="bg-white rounded-lg shadow-md overflow-hidden p-4">
    
    <div class="overflow-x-auto">
        <table class="min-w-full leading-normal">
            <thead>
                <tr>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        Nombre
                    </th>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        Grupo Muscular
                    </th>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        Acciones
                    </th>
                </tr>
            </thead>
            <tbody>
                @forelse($exercises as $exercise)
                <tr>
                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                        <p class="text-gray-900 whitespace-no-wrap font-medium">
                            {{ $exercise->name }}
                        </p>
                    </td>
                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                        <span class="relative inline-block px-3 py-1 font-semibold text-blue-900 leading-tight">
                            <span aria-hidden class="absolute inset-0 bg-blue-200 opacity-50 rounded-full"></span>
                            <span class="relative">{{ $exercise->muscle_group ?? 'General' }}</span>
                        </span>
                    </td>
                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm text-right">
                        <a href="{{ route('admin.exercises.edit', $exercise->id) }}" class="text-blue-600 hover:text-blue-900 mr-4">
                            <i class="fas fa-edit"></i> Editar
                        </a>
                        
                        <form action="{{ route('admin.exercises.destroy', $exercise->id) }}" method="POST" class="inline-block">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('¿Seguro que quieres borrar este ejercicio?')">
                                <i class="fas fa-trash"></i> Borrar
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" class="px-5 py-5 border-b border-gray-200 bg-white text-sm text-center text-gray-500">
                        No hay ejercicios registrados todavía.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4 px-4">
        {{ $exercises->links() }}
    </div>
</div>

@endsection