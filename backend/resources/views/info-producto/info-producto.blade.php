<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>Detalles del Producto</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;600;700;800&display=swap" rel="stylesheet">
    
    <script src="https://unpkg.com/@phosphor-icons/web"></script>

    <link rel="stylesheet" href="{{ asset('css/info-producto.css') }}"> 
</head>

<body>

    <header class="header-nav">
        <button onclick="window.history.back()" class="btn-back" aria-label="Volver">
            <i class="ph-bold ph-caret-left"></i>
        </button>
        <span class="header-title">Detalles</span>
        <div style="width: 40px;"></div>
    </header>

    <main class="main-container">

        <section class="card product-hero">
            <div class="product-image-wrapper">
                <img id="prod-img" src="{{ asset('assets/img/placeholder-product.png') }}" alt="Producto" onerror="this.style.display='none'">
                <i class="ph-duotone ph-image placeholder-icon" id="img-placeholder"></i>
            </div>

            <div class="title-section">
                <h2 id="prod-nombre">Cargando...</h2>
                <p id="prod-marca">...</p>
            </div>

            <div class="badges-row" id="badges-container">
                <span id="nutriscore-badge" class="badge-score bg-gray">--</span>
                <span id="nova-badge" class="badge-score bg-gray">NOVA ?</span>
            </div>
        </section>

        <section class="macro-highlight">
            <div class="macro-info">
                <span class="macro-label">Energía</span>
                <div class="macro-value-group">
                    <span class="macro-number" id="prod-kcal">0</span>
                    <span class="macro-unit">kcal</span>
                </div>
            </div>
            <div class="macro-icon-box">
                <i class="ph-fill ph-fire"></i>
            </div>
        </section>

        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-header">
                    <span class="stat-label">Proteínas</span>
                    <span class="stat-value" id="prod-prot">0<small>g</small></span>
                </div>
                <div class="progress-bg">
                    <div class="progress-bar color-blue" style="width: 0%" id="bar-prot"></div>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-header">
                    <span class="stat-label">Carbos</span>
                    <span class="stat-value" id="prod-carb">0<small>g</small></span>
                </div>
                <div class="progress-bg">
                    <div class="progress-bar color-yellow" style="width: 0%" id="bar-carb"></div>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-header">
                    <span class="stat-label">Grasas</span>
                    <span class="stat-value" id="prod-gras">0<small>g</small></span>
                </div>
                <div class="progress-bg">
                    <div class="progress-bar color-red" style="width: 0%" id="bar-gras"></div>
                </div>
            </div>

            <div class="stat-card simple-card">
                <i class="ph-bold ph-plant icon-simple"></i>
                <div class="text-simple">
                    <span class="stat-label">Fibra</span>
                    <span class="stat-value" id="prod-fibra">0<small>g</small></span>
                </div>
            </div>

            <div class="stat-card simple-card">
                <i class="ph-bold ph-cube icon-simple"></i>
                <div class="text-simple">
                    <span class="stat-label">Azúcar</span>
                    <span class="stat-value" id="prod-azucar">0<small>g</small></span>
                </div>
            </div>

            <div class="stat-card simple-card">
                <i class="ph-bold ph-drop icon-simple"></i>
                <div class="text-simple">
                    <span class="stat-label">Sal</span>
                    <span class="stat-value" id="prod-sal">0<small>g</small></span>
                </div>
            </div>
        </div>

        <button class="btn-add-product" id="btn-add">
            <i class="ph-bold ph-plus"></i>
            <span>Añadir al Diario</span>
        </button>

    </main>

    <nav class="floating-dock">
        <a href="{{ route('home') }}" class="dock-item"><i class="ph-fill ph-house"></i></a>
        <a href="{{ route('nutrition') }}" class="dock-item active"><i class="ph-bold ph-fork-knife"></i></a>
        
        <div class="dock-fab-container">
            <button class="dock-fab" id="video-trigger-btn"><i class="ph-fill ph-play"></i></button>
        </div>
        
        <a href="{{ route('training') }}" class="dock-item"><i class="ph-bold ph-barbell"></i></a>
        <a href="/perfil" class="dock-item"><i class="ph-bold ph-user"></i></a>
    </nav>

    <div id="video-modal" class="modal-overlay">
        <div class="modal-content">
            <button class="close-modal" id="close-modal-btn"><i class="ph-bold ph-x"></i></button>
            <div class="video-wrapper">
                <video id="popup-video" controls playsinline>
                    <source src="https://videos.pexels.com/video-files/5319759/5319759-hd_720_1280_25fps.mp4" type="video/mp4">
                </video>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/info-producto.js') }}" defer></script>
    <script src="{{ asset('assets/js/chatbot.js') }}"></script>
</body>
</html>