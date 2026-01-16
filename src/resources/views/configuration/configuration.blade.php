<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FitManager - Perfil</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <link rel="stylesheet" href="{{ asset('css/configuration.css') }}">
</head>
<body>

    <header class="fm-header-p">
        <div class="header-flex">
            <img src="{{ asset('Fotos/Logo_FitManager.png') }}" alt="FitManager" class="fm-logo-p">
            <i class="ph ph-gear-six gear-icon"></i>
        </div>
    </header>

    <main class="container fm-main-p">
        <h1 class="user-name center-align">{{ Auth::user()->name }}</h1>

        <div class="row stats-container">
            <div class="col s4">
                <div class="stat-box box-blue">
                    <span class="val">{{ Auth::user()->profile->weight ?? '0' }} kg</span>
                    <span class="lab">Peso actual</span>
                </div>
            </div>
            <div class="col s4">
                <div class="stat-box box-green">
                    <span class="val">{{ Auth::user()->profile->height ?? '0' }} cm</span>
                    <span class="lab">Altura</span>
                </div>
            </div>
            <div class="col s4">
                <div class="stat-box box-dark">
                    <span class="val">0 dias</span>
                    <span class="lab">Racha de dias</span>
                </div>
            </div>
        </div>

    <div class="settings-section">
        <h6 class="section-title">Usuario</h6>
        <div class="settings-list">
            <a href="{{ route('profile.edit') }}" class="settings-item waves-effect" style="display: flex; justify-content: space-between; align-items: center; color: inherit; text-decoration: none;">
                <span>Editar Perfil</span>
                <i class="ph ph-caret-right"></i>
            </a>

            <form method="POST" action="{{ route('logout') }}" id="logout-form">
                @csrf
                <div class="settings-item waves-effect" onclick="document.getElementById('logout-form').submit();" style="cursor: pointer;">
                    <span>Cerrar Sesión</span>
                    <i class="ph ph-caret-right"></i>
                </div>
            </form>
        </div>
    </div>
    </main>

    <footer class="fm-bottom-nav white">
        <a href="{{ route('home') }}" class="nav-item">
            <i class="ph ph-house"></i>
            <span>Casa</span>
        </a>
        <div class="nav-item">
            <i class="ph ph-unite"></i>
            <span>Nutricion</span>
        </div>
        <div class="nav-item">
            <i class="ph ph-barbell"></i>
            <span>Entrenamiento</span>
        </div>
        <a href="{{ route('perfil') }}" class="nav-item active">
            <i class="ph-fill ph-user"></i>
            <span>Perfil</span>
        </a>
    </footer>

</body>
</html>