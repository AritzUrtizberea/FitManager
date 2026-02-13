<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>Diseñar Dieta - Añadir Alimentos</title>

    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/crear-dieta.css') }}">
    <link rel="stylesheet" href="{{ asset('css/chatbot.css') }}">

    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/animejs/3.2.1/anime.min.js"></script>
</head>

<body>

    <header class="header-nav">
        <button class="btn-back" onclick="window.location.href='{{ route('nutrition') }}'" aria-label="Volver a nutrición">
            <i class="ph-bold ph-caret-left" aria-hidden="true"></i>
        </button>
        <h1 class="header-title">Añadir Alimentos</h1>
        <div style="width: 44px;"></div> </header>

    <main class="main-container">

        <section class="card select-card" aria-labelledby="label-dia">
            <label id="label-dia" for="select-dia" class="input-label">
                <i class="ph-fill ph-calendar-blank" aria-hidden="true"></i> Día de la semana
            </label>
            <div class="select-wrapper">
                <select id="select-dia" class="input-modern">
                    <option value="lunes">Lunes</option>
                    <option value="martes">Martes</option>
                    <option value="miercoles">Miércoles</option>
                    <option value="jueves">Jueves</option>
                    <option value="viernes">Viernes</option>
                    <option value="sabado">Sábado</option>
                    <option value="domingo">Domingo</option>
                </select>
                <i class="ph-bold ph-caret-down select-arrow" aria-hidden="true"></i>
            </div>
        </section>

        <section class="card search-card">
            <h2 class="card-title">Buscar Alimento</h2>
            <div class="search-wrapper">
                <i class="ph ph-magnifying-glass search-icon" aria-hidden="true"></i>
                
                <label for="food-search-input" class="sr-only">Escribe un alimento</label>

                <input type="text" 
                    id="food-search-input" 
                    name="search-food"
                    class="search-input" 
                    placeholder="Ej: Arroz, Pollo, Manzana..."
                    aria-label="Escribe un alimento para buscar"
                    title="Barra de búsqueda de alimentos"
                    autocomplete="off">
            </div>
            <div id="laravel-results" class="results-container" aria-live="polite"></div>
        </section>

        <section class="card list-card">
            <div class="card-header-row">
                <h2 class="card-title">Tu Lista</h2>
                <span class="badge-count" id="item-count" aria-label="0 elementos en la lista">0</span>
            </div>

            <div id="lista-cesta" class="basket-container" role="list">
                <div class="empty-state">
                    <div class="icon-circle">
                        <i class="ph-fill ph-basket" aria-hidden="true"></i>
                    </div>
                    <p>Tu cesta está vacía</p>
                </div>
            </div>
        </section>

    </main>

    <div id="resumen-fijo" class="floating-summary">
        <div class="summary-content">
            <div class="info-calories">
                <span class="label">Total Calorías</span>
                <div class="value">
                    <span id="kcal-total">0</span> <small>kcal</small>
                </div>
            </div>
            <button id="btn-finalizar" class="btn-finish">
                <span>Guardar</span>
                <i class="ph-bold ph-check-circle" aria-hidden="true"></i>
            </button>
        </div>
    </div>

    <nav class="floating-dock" aria-label="Menú principal">
        <a href="{{ route('home') }}" class="dock-item" aria-label="Inicio">
            <i class="ph-fill ph-house" aria-hidden="true"></i>
        </a>
        <a href="{{ route('nutrition') }}" class="dock-item active" aria-current="page" aria-label="Nutrición">
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
        <a href="/perfil" class="dock-item" aria-label="Perfil">
            <i class="ph-bold ph-user" aria-hidden="true"></i>
        </a>
    </nav>

    <div id="video-modal" class="modal-overlay" aria-hidden="true">
        <div class="modal-content">
            <button class="close-modal" id="close-modal-btn" aria-label="Cerrar vídeo">
                <i class="ph-bold ph-x" aria-hidden="true"></i>
            </button>
            <div class="video-wrapper">
                <video id="popup-video" controls playsinline>
                    <source src="https://videos.pexels.com/video-files/5319759/5319759-hd_720_1280_25fps.mp4" type="video/mp4">
                </video>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/main.js') }}"></script>
    <script src="{{ asset('js/crear-dieta.js') }}"></script>
    <script src="{{ asset('js/chatbot.js') }}"></script>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const triggerBtn = document.getElementById('video-trigger-btn');
            const closeBtn = document.getElementById('close-modal-btn');
            const modal = document.getElementById('video-modal');
            const video = document.getElementById('popup-video');
            
            if (triggerBtn && modal) {
                triggerBtn.addEventListener('click', () => { 
                    modal.classList.add('open'); 
                    modal.setAttribute('aria-hidden', 'false'); // Accesibilidad
                    if (video) video.play(); 
                    closeBtn.focus(); // Mover foco al botón cerrar
                });
                
                const closeModal = () => { 
                    modal.classList.remove('open'); 
                    modal.setAttribute('aria-hidden', 'true'); // Accesibilidad
                    if (video) { video.pause(); video.currentTime = 0; } 
                    triggerBtn.focus(); // Devolver foco al botón que abrió
                };
                
                closeBtn.addEventListener('click', closeModal);
                modal.addEventListener('click', (e) => { if (e.target === modal) closeModal(); });
                // Cerrar con tecla Escape
                document.addEventListener('keydown', (e) => {
                    if (e.key === 'Escape' && modal.classList.contains('open')) closeModal();
                });
            }
        });
    </script>
</body>
</html>