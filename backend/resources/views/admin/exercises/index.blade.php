@extends('layouts.admin')

@section('content')

<div class="container-fluid p-5">

    <div class="mb-4">
        <h2 class="fw-bold text-dark">
            <i class="fas fa-dumbbell me-2"></i>Gestión de Ejercicios
        </h2>
        <p class="text-muted">Administra la biblioteca de entrenamientos.</p>
    </div>

    <div class="mb-4">
        <a href="{{ route('admin.exercises.create') }}" class="btn btn-success px-4 py-2">
            <i class="fas fa-plus me-2"></i>Nuevo Ejercicio
        </a>
    </div>

    <div class="card border-0 shadow-sm rounded-3">
        <div class="card-body p-0">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-white border-bottom">
                    <tr>
                        <th class="ps-4 py-3 text-secondary text-uppercase" style="font-size: 0.85rem;">ID</th>
                        <th class="py-3 text-secondary text-uppercase" style="font-size: 0.85rem;">Nombre</th>
                        <th class="py-3 text-secondary text-uppercase" style="font-size: 0.85rem;">Grupo Muscular</th>
                        <th class="pe-4 py-3 text-secondary text-uppercase text-end" style="font-size: 0.85rem;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($exercises as $exercise)
                    <tr>
                        <td class="ps-4 fw-bold text-muted">
                            #{{ $exercise->id }}
                        </td>

                        <td class="fw-500">
                            {{ $exercise->name }}
                        </td>

                        <td>
                            <span class="badge bg-info bg-opacity-10 text-primary border border-info border-opacity-25 rounded-pill px-3 py-2">
                                {{ $exercise->muscle_group ?? 'General' }}
                            </span>
                        </td>

                        <td class="pe-4 text-end">
                            <div class="d-inline-flex gap-2">
                                <a href="{{ route('admin.exercises.edit', $exercise->id) }}" class="btn btn-outline-primary btn-sm d-flex align-items-center justify-content-center" style="width: 35px; height: 35px;" title="Editar">
                                    <i class="fas fa-pen-to-square"></i>
                                </a>

                                <form action="{{ route('admin.exercises.destroy', $exercise->id) }}" method="POST" onsubmit="return confirm('¿Estás seguro de eliminar este ejercicio?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-dark btn-sm d-flex align-items-center justify-content-center" style="width: 35px; height: 35px;" title="Eliminar">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center py-5 text-muted">
                            <i class="fas fa-dumbbell fa-3x mb-3 text-secondary opacity-50"></i>
                            <p class="mb-0">No hay ejercicios registrados.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4 d-flex justify-content-center">
        {{ $exercises->links() }}
    </div>

</div>

@endsection