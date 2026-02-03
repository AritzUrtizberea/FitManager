@extends('layouts.admin')

@section('content')
<div class="container-fluid p-2 p-md-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 p-3 p-md-4 bg-white rounded-3 shadow-sm border">
        <div class="mb-3 mb-md-0">
            <h1 class="h4 fw-bold text-dark d-flex align-items-center gap-2 mb-0">
                <span class="bg-success bg-opacity-10 text-success p-2 rounded">
                    <i class="fas fa-box-open"></i>
                </span>
                Gestión de Productos
            </h1>
            <p class="text-muted small mb-0 mt-1 ms-1">
                Administra el catálogo y las calorías.
            </p>
        </div>

        <a href="{{ route('admin.products.create') }}" class="btn btn-success d-flex align-items-center gap-2 px-3 py-2 fw-semibold w-100 w-md-auto justify-content-center">
            <div class="bg-white bg-opacity-25 rounded-circle d-flex justify-content-center align-items-center" style="width: 20px; height: 20px;">
                <i class="fas fa-plus" style="font-size: 10px;"></i>
            </div>
            <span>Nuevo Producto</span>
        </a>
    </div>

    <div class="card border-0 shadow-sm rounded-3 overflow-hidden">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 text-nowrap">
                    <thead class="bg-light border-bottom">
                        <tr>
                            <th class="ps-4 py-3 text-secondary text-uppercase fw-bold" style="font-size: 0.75rem;">ID</th>
                            <th class="py-3 text-secondary text-uppercase fw-bold" style="font-size: 0.75rem;">Nombre</th>
                            <th class="py-3 text-secondary text-uppercase fw-bold" style="font-size: 0.75rem;">Calorías</th>
                            <th class="pe-4 py-3 text-end text-secondary text-uppercase fw-bold" style="font-size: 0.75rem;">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($products as $product)
                        <tr>
                            <td class="ps-4 fw-bold text-secondary">#{{ $product->id }}</td>
                            <td class="fw-bold text-dark">{{ $product->name }}</td>
                            <td>
                                <span class="badge bg-warning bg-opacity-10 text-dark border border-warning border-opacity-25 rounded-pill px-3 py-2 fw-normal">
                                    <i class="fas fa-fire-alt me-1 text-danger"></i>
                                    {{ $product->kcal ?? 0 }} kcal
                                </span>
                            </td>
                            <td class="pe-4 text-end">
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn-sm bg-primary bg-opacity-10 text-primary border-0">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" class="d-inline">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm bg-danger bg-opacity-10 text-danger border-0">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection