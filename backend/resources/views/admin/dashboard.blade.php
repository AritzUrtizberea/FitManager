@extends('layouts.admin')

@section('content')
<div class="container-fluid p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>👑 Panel de Control</h1>
        <span class="badge bg-primary fs-6">Modo Administrador</span>
    </div>
    
    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="card shadow h-100 border-0">
                <div class="card-body text-center p-5">
                    <i class="fas fa-apple-alt fa-3x text-danger mb-3"></i>
                    <h3>Productos</h3>
                    <p class="text-muted">Gestiona los alimentos y calorías.</p>
                    <a href="{{ route('admin.products.index') }}" class="btn btn-outline-danger btn-lg mt-2">
                        Gestionar Productos
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-6 mb-4">
            <div class="card shadow h-100 border-0">
                <div class="card-body text-center p-5">
                    <i class="fas fa-dumbbell fa-3x text-success mb-3"></i>
                    <h3>Ejercicios</h3>
                    <p class="text-muted">Crea y edita las rutinas.</p>
                    <a href="{{ route('admin.exercises.index') }}" class="btn btn-outline-success btn-lg mt-2">
                        Gestionar Ejercicios
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection