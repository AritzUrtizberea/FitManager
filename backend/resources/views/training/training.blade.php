<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>FitManager - Entrenamiento</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;700;800&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('css/training.css') }}">
    <link rel="stylesheet" href="{{ asset('css/chatbot.css') }}">
</head>

<body>

    <div class="ambient-light"></div>

    <header class="header-nav">
        <h1 class="header-title">Entrenamiento</h1>
    </header>

    <main class="main-container">

        <a href="{{ route('routines') }}" class="card-premium-dark no-decoration">
            <div class="premium-content">
                <div class="premium-text">
                    <h2>Tu viaje empieza hoy</h2>
                    <p>Define tu plan de entrenamiento para ver tu progreso.</p>
                </div>
                <div class="premium-icon">
                    <i class="ph-fill ph-trophy" aria-hidden="true"></i>
                </div>
            </div>
            <div class="btn-action-glow">
                Configurar Plan <i class="ph-bold ph-gear-six" aria-hidden="true"></i>
            </div>
        </a>

        <h3 class="section-label">Recomendado</h3>

        <a href="{{ route('routines') }}" class="card-immersive no-decoration">
            <img src="https://images.unsplash.com/photo-1517836357463-d25dfeac3438?auto=format&fit=crop&q=80&w=1000"
                class="bg-img" alt="Mujer haciendo ejercicio en gimnasio">
            <div class="immersive-overlay">
                <span class="tag-blur">NUEVO</span>
                <div class="immersive-info">
                    <h4>Full Body Challenge</h4>
                    <div class="meta-row">
                        <span><i class="ph-fill ph-clock" aria-hidden="true"></i> 45 min</span>
                        <span><i class="ph-fill ph-fire" aria-hidden="true"></i> Medio</span>
                    </div>
                </div>
            </div>
        </a>

        <h3 class="section-label">Explorar</h3>

        <div class="categories-scroll">
            <a href="{{ route('routines') }}" class="cat-item active">
                <i class="ph-fill ph-person-simple-run" aria-hidden="true"></i>
                <span>Cardio</span>
            </a>
            <a href="{{ route('routines') }}" class="cat-item">
                <i class="ph-fill ph-barbell" aria-hidden="true"></i>
                <span>Fuerza</span>
            </a>
            <a href="{{ route('routines') }}" class="cat-item">
                <i class="ph-fill ph-flower-lotus" aria-hidden="true"></i>
                <span>Yoga</span>
            </a>
            <a href="{{ route('routines') }}" class="cat-item">
                <i class="ph-fill ph-sneaker-move" aria-hidden="true"></i>
                <span>HIIT</span>
            </a>
        </div>

    </main>

    <div class="floating-dock">
        <a href="{{ route('home') }}" class="dock-item" aria-label="Ir a Inicio"><i class="ph-fill ph-house" aria-hidden="true"></i></a>

        <a href="{{ route('nutrition') }}" class="dock-item" aria-label="Ir a Nutrición"><i class="ph-bold ph-fork-knife" aria-hidden="true"></i></a>

        <div class="dock-fab-container">
            <button class="dock-fab" id="video-trigger-btn" aria-label="Iniciar entrenamiento o video">
                <i class="ph-fill ph-play" aria-hidden="true"></i>
            </button>
        </div>

        <a href="{{ route('training') }}" class="dock-item active" aria-label="Ir a Entrenamiento" aria-current="page"><i class="ph-bold ph-barbell" aria-hidden="true"></i></a>
        
        <a href="/perfil" class="dock-item" aria-label="Ir a Perfil"><i class="ph-bold ph-user" aria-hidden="true"></i></a>
    </div>

    <script src="{{ asset('js/chatbot.js') }}"></script>
    <script src="{{ asset('js/trainingNav.js') }}"></script>
</body>
</html>