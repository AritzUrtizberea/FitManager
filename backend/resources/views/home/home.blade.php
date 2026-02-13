<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>FitManager</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;600;800&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    
    <link rel="stylesheet" href="{{ asset('css/home.css') }}">
    <link rel="stylesheet" href="{{ asset('css/chatbot.css') }}">
</head>

<body>
    <div class="app-wrapper">
        
        <header class="top-bar">
            <div class="date-container">
                <span class="date-label">HOY ES</span>
                <div class="date-value" id="date-display" role="text" aria-live="polite">
                    <div class="spinner" aria-label="Cargando fecha"></div>
                </div>
            </div>
            
            <a href="{{ url('/perfil') }}" class="profile-btn text-avatar" aria-label="Ir a mi perfil">
                <span id="header-initials" aria-hidden="true">
                    <div class="spinner small"></div>
                </span>
            </a>
        </header>

        <main>
            <section class="hero-section">
                <div class="glass-card gradient-bg">
                    <div class="card-info">
                        <div class="chip-row">
                            <span class="chip white">Rutina de Hoy</span>
                        </div>
                        <h2 id="workout-title" class="text-loading">Cargando...</h2>
                        <p class="subtitle">Tu cuerpo puede con todo. Es tu mente a la que tienes que convencer.</p>
                        <div class="meta-row">
                            <span><i class="ph-fill ph-clock" aria-hidden="true"></i> 45 min</span>
                            <span><i class="ph-fill ph-fire" aria-hidden="true"></i> Alta Intensidad</span>
                        </div>
                        <button class="action-btn white-btn" onclick="location.href='{{ route('training') }}'">
                            <span>EMPEZAR AHORA</span>
                            <i class="ph-bold ph-arrow-right" aria-hidden="true"></i>
                        </button>
                    </div>
                    <i class="ph-duotone ph-barbell bg-deco" aria-hidden="true"></i>
                </div>
            </section>

            <section class="bento-grid">
                
                <div class="bento-card nutrition-box" onclick="location.href='{{ route('nutrition') }}'" role="button" tabindex="0" aria-label="Ver detalles de nutrición">
                    <div class="bento-header">
                        <div class="icon-box orange" aria-hidden="true"><i class="ph-fill ph-fire"></i></div>
                        <span>Objetivo</span>
                    </div>
                    <div class="chart-circular" role="img" aria-label="Progreso de calorías">
                        <svg viewBox="0 0 36 36" class="circular-chart" aria-hidden="true">
                            <path class="circle-bg" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                            <path class="circle" id="cal-circle" stroke-dasharray="0, 100" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                        </svg>
                        <div class="inner-text">
                            <span class="big" id="cal-goal">--</span>
                            <small>kcal</small>
                        </div>
                    </div>
                    <div class="mini-label">Calculado hoy</div>
                </div>

                <div class="bento-card goals-box">
                    <div class="bento-header">
                        <div class="icon-box green" aria-hidden="true"><i class="ph-fill ph-check-circle"></i></div>
                        <span>Racha</span>
                    </div>
                    <div class="week-check" id="week-days-container" role="list" aria-label="Racha de la semana">
                        <div class="day" role="listitem" aria-label="Lunes">L</div>
                        <div class="day" role="listitem" aria-label="Martes">M</div>
                        <div class="day" role="listitem" aria-label="Miércoles">X</div>
                        <div class="day" role="listitem" aria-label="Jueves">J</div>
                        <div class="day" role="listitem" aria-label="Viernes">V</div>
                        <div class="day" role="listitem" aria-label="Sábado">S</div>
                        <div class="day" role="listitem" aria-label="Domingo">D</div>
                    </div>
                    <div class="mini-label">Constancia Semanal</div>
                </div>

                <div class="bento-card wide-box" onclick="location.href='{{ url('/perfil') }}'" role="button" tabindex="0" aria-label="Ver registro de peso">
                    <div class="left-col">
                         <div class="bento-header">
                            <div class="icon-box blue" aria-hidden="true"><i class="ph-fill ph-scales"></i></div>
                            <span>Peso Actual</span>
                        </div>
                        <span class="value">
                            <span id="user-weight">--</span> <small>kg</small>
                        </span>
                    </div>
                    <div class="right-col">
                        <div class="trend up" aria-label="Tendencia positiva">
                            <i class="ph-bold ph-trend-up" aria-hidden="true"></i> Actualizar
                        </div>
                    </div>
                </div>

            </section>
        </main>

        <nav class="floating-dock" role="navigation" aria-label="Menú principal">
            <a href="{{ route('home') }}" class="dock-item active" aria-label="Inicio">
                <i class="ph-fill ph-house" aria-hidden="true"></i>
            </a>
            <a href="{{ route('nutrition') }}" class="dock-item" aria-label="Nutrición">
                <i class="ph-bold ph-fork-knife" aria-hidden="true"></i>
            </a>
            
            <div class="dock-fab-container">
                <button class="dock-fab" id="video-trigger-btn" aria-label="Ver vídeo motivacional">
                    <i class="ph-fill ph-play" aria-hidden="true"></i>
                </button>
            </div>
            
            <a href="{{ route('training') }}" class="dock-item" aria-label="Entrenamiento">
                <i class="ph-bold ph-barbell" aria-hidden="true"></i>
            </a>
            <a href="{{ url('/perfil') }}" class="dock-item" aria-label="Perfil">
                <i class="ph-bold ph-user" aria-hidden="true"></i>
            </a>
        </nav>
    </div>

    <div id="video-modal" class="modal-overlay" aria-hidden="true">
        <div class="modal-content" role="dialog" aria-modal="true" aria-label="Vídeo motivacional">
            <button class="close-modal" id="close-modal-btn" aria-label="Cerrar vídeo">
                <i class="ph-bold ph-x" aria-hidden="true"></i>
            </button>
            <div class="video-wrapper">
                <video id="popup-video" controls playsinline aria-label="Reproductor de video">
                    <source src="https://videos.pexels.com/video-files/5319759/5319759-hd_720_1280_25fps.mp4" type="video/mp4">
                    Tu navegador no soporta videos.
                </video>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/dashboard-logic.js') }}"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const triggerBtn = document.getElementById('video-trigger-btn');
            const closeBtn = document.getElementById('close-modal-btn');
            const modal = document.getElementById('video-modal');
            const video = document.getElementById('popup-video');

            const openModal = () => {
                modal.classList.add('open');
                modal.setAttribute('aria-hidden', 'false'); // Accesibilidad
                if (video) video.play();
                closeBtn.focus(); // Mover foco al botón de cerrar
            };

            const closeModal = () => {
                modal.classList.remove('open');
                modal.setAttribute('aria-hidden', 'true'); // Accesibilidad
                if (video) { video.pause(); video.currentTime = 0; }
                triggerBtn.focus(); // Devolver foco al botón que lo abrió
            };

            if (triggerBtn && modal) {
                triggerBtn.addEventListener('click', openModal);
                closeBtn.addEventListener('click', closeModal);
                modal.addEventListener('click', (e) => { if (e.target === modal) closeModal(); });
                
                // Cerrar con tecla ESC
                document.addEventListener('keydown', (e) => {
                    if (e.key === 'Escape' && modal.classList.contains('open')) closeModal();
                });
            }
        });
    </script>
</body>
</html>