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
        
        /* Solo aplicamos el flex del wrapper en escritorio */
        @media (min-width: 768px) {
            .wrapper { display: flex; flex: 1; }
            .sidebar { min-width: 250px; background: #212529; color: white; display: block !important; }
        }

        /* Estilos de la sidebar (se mantienen igual para que no cambie en escritorio) */
        .sidebar { background: #212529; color: white; }
        .sidebar a { color: #adb5bd; text-decoration: none; padding: 15px 20px; display: block; border-bottom: 1px solid #343a40; }
        .sidebar a:hover { background: #0d6efd; color: white; }
        .content { flex: 1; background: #f8f9fa; min-height: 100vh; }

        /* Ajuste para el menú offcanvas de móvil */
        .offcanvas { background: #212529 !important; }
        .offcanvas .nav-link { color: #adb5bd; padding: 15px 20px; border-bottom: 1px solid #343a40; display: block; text-decoration: none; }
        .offcanvas .nav-link:hover { background: #0d6efd; color: white; }
    </style>
</head>
<body>

<nav class="navbar navbar-dark bg-dark d-md-none">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">FitManager <span class="badge bg-danger">ADMIN</span></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#mobileMenu">
            <span class="navbar-toggler-icon"></span>
        </button>
    </div>
</nav>

<div class="wrapper">
    <nav class="sidebar d-none d-md-block">
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

    <div class="offcanvas offcanvas-start d-md-none" tabindex="-1" id="mobileMenu">
        <div class="offcanvas-header bg-dark text-white">
            <h5 class="offcanvas-title">FitManager ADMIN</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"></button>
        </div>
        <div class="offcanvas-body p-0">
            <a class="nav-link" href="{{ route('admin.dashboard') }}"><i class="fas fa-home me-2"></i> Dashboard</a>
            <a class="nav-link" href="{{ route('admin.products.index') }}"><i class="fas fa-apple-alt me-2"></i> Productos</a>
            <a class="nav-link" href="{{ route('admin.exercises.index') }}"><i class="fas fa-dumbbell me-2"></i> Ejercicios</a>
            
            <div class="mt-5 px-3">
                <form method="POST" action="{{ route('logout') }}">
                   @csrf
                   <button type="submit" class="btn btn-outline-danger w-100">Cerrar Sesión</button>
               </form>
           </div>
        </div>
    </div>

    <main class="content">
        @yield('content') 
    </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>