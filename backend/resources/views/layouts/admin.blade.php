<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Admin - FitManager</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <link rel="stylesheet" href="{{ asset('css/style.css') }}"> 

    <style>
        body { min-height: 100vh; display: flex; flex-direction: column; }
        .wrapper { display: flex; flex: 1; }
        .sidebar { min-width: 250px; background: #212529; color: white; }
        .sidebar a { color: #adb5bd; text-decoration: none; padding: 15px 20px; display: block; border-bottom: 1px solid #343a40; }
        .sidebar a:hover { background: #0d6efd; color: white; }
        .content { flex: 1; background: #f8f9fa; }
    </style>
</head>
<body>

<div class="wrapper">
    <nav class="sidebar">
        <div class="p-4 text-center bg-dark">
            <h3>FitManager</h3>
            <span class="badge bg-danger">ADMIN</span>
        </div>
        
        <a href="{{ route('admin.dashboard') }}"><i class="fas fa-home me-2"></i> Dashboard</a>
        <a href="{{ route('admin.products.index') }}"><i class="fas fa-apple-alt me-2"></i> Productos</a>
        <a href="{{ route('admin.exercises.index') }}"><i class="fas fa-dumbbell me-2"></i> Ejercicios</a>
        
        <div class="mt-5 px-3">
             <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn btn-outline-light w-100">Cerrar Sesión</button>
            </form>
        </div>
    </nav>

    <main class="content">
        @yield('content') 
    </main>
</div>

</body>
</html>