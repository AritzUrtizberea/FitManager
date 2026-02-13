<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>Nutrición</title>

    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;600;700;800&display=swap" rel="stylesheet">

    <script src="https://cdnjs.cloudflare.com/ajax/libs/animejs/3.2.1/anime.min.js"></script>
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <script src="https://unpkg.com/html5-qrcode"></script>

    <link rel="stylesheet" href="{{ asset('css/nutrition.css') }}">
    <link rel="stylesheet" href="{{ asset('css/chatbot.css') }}">
</head>

<body>
    <header class="app-header">
        <h1 class="h1-title">Nutrición</h1>
    </header>

    <main class="main-container">

    <section class="card hero-card">
        <h2 class="h2-subtitle">Escáner Rápido</h2>

        <div id="reader" class="scanner-viewport" style="display:none;" aria-label="Cámara de escaneo"></div>

        <div class="scanner-controls">
            <button id="btn-scan" class="btn-primary-gradient large-btn" aria-label="Escanear código de barras con la cámara">
                <i class="ph-bold ph-camera" aria-hidden="true"></i>
                <span>Escanear Alimento</span>
            </button>

            <div class="divider">
                <span>o escribe el código</span>
            </div>

            <div class="input-modern-group">
                <div class="input-wrapper">
                    <i class="ph ph-barcode input-icon" aria-hidden="true"></i>
                    
                    <label for="barcode-input" class="sr-only">Introduce el código de barras manualmente</label>
                    
                    <input type="text" id="barcode-input" placeholder="Ej: 84100..." inputmode="numeric">
                </div>
                
                <button id="btn-ver" class="btn-ghost" aria-label="Ver detalles del código introducido">Ver</button>
            </div>
        </div>
    </section>

        <button id="btn-crear-dieta" class="btn-secondary-action full-width shadow-soft" onclick="window.location.href='{{ route('crear-dieta') }}'">
            <div class="icon-box">
                <i class="ph-fill ph-pencil-simple" aria-hidden="true"></i>
            </div>
            <div class="btn-text">
                <span class="btn-title">Diseñar / Modificar Dieta</span>
                <span class="btn-subtitle">Planifica tu semana manualmente</span>
            </div>
            <i class="ph-bold ph-caret-right arrow-icon" aria-hidden="true"></i>
        </button>

    <section class="tu-semana">
        <h2 class="h2-subtitle section-header">Tu Semana</h2>
        
        <div id="weekly-plan-container" aria-live="polite" aria-atomic="true">
            
            <div style="text-align:center; padding: 30px; color: var(--text-muted);">
                <i class="ph ph-spinner" style="font-size: 24px; animation: spin 1s linear infinite;" aria-hidden="true"></i>
                <p style="font-size: 13px; margin-top: 10px;">Cargando tu dieta...</p>
            </div>

        </div>
    </section>

    </main>

    <nav class="floating-dock" role="navigation" aria-label="Menú principal">
        <a href="{{ route('home') }}" class="dock-item" aria-label="Inicio">
            <i class="ph-fill ph-house" aria-hidden="true"></i>
        </a>
        
        <a href="{{ route('nutrition') }}" class="dock-item active" aria-label="Nutrición" aria-current="page">
            <i class="ph-bold ph-fork-knife" aria-hidden="true"></i>
        </a>
        
        <div class="dock-fab-container">
            <button class="dock-fab" id="video-trigger-btn" aria-label="Ver vídeo explicativo">
                <i class="ph-fill ph-play" aria-hidden="true"></i>
            </button>
        </div>
        
        <a href="{{ route('training') }}" class="dock-item" aria-label="Entrenamiento">
            <i class="ph-bold ph-barbell" aria-hidden="true"></i>
        </a>
        <a href="{{ route('profile.edit') }}" class="dock-item" aria-label="Perfil">
            <i class="ph-bold ph-user" aria-hidden="true"></i>
        </a>
    </nav>

    <div id="video-modal" class="modal-overlay">
        <div class="modal-content">
            <button class="close-modal" id="close-modal-btn" aria-label="Cerrar modal">
                <i class="ph-bold ph-x" aria-hidden="true"></i>
            </button>
            <div class="video-wrapper">
                <video id="popup-video" controls playsinline>
                    <source src="https://videos.pexels.com/video-files/5319759/5319759-hd_720_1280_25fps.mp4" type="video/mp4">
                    Tu navegador no soporta video.
                </video>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/nutricion.js') }}" defer></script>
    <script src="{{ asset('js/chatbot.js') }}"></script>

</body>
</html>