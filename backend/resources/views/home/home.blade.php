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
                <div class="date-value" id="date-display">
                    <div class="spinner"></div>
                </div>
            </div>
            
            <div class="profile-btn text-avatar" onclick="location.href='{{ url('/perfil') }}'">
                <span id="header-initials">
                    <div class="spinner small"></div>
                </span>
            </div>
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
                            <span><i class="ph-fill ph-clock"></i> 45 min</span>
                            <span><i class="ph-fill ph-fire"></i> Alta Intensidad</span>
                        </div>
                        <button class="action-btn white-btn" onclick="location.href='{{ route('training') }}'">
                            <span>EMPEZAR AHORA</span>
                            <i class="ph-bold ph-arrow-right"></i>
                        </button>
                    </div>
                    <i class="ph-duotone ph-barbell bg-deco"></i>
                </div>
            </section>

            <section class="bento-grid">
                
                <div class="bento-card nutrition-box" onclick="location.href='{{ route('nutrition') }}'">
                    <div class="bento-header">
                        <div class="icon-box orange"><i class="ph-fill ph-fire"></i></div>
                        <span>Objetivo</span>
                    </div>
                    <div class="chart-circular">
                        <svg viewBox="0 0 36 36" class="circular-chart">
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
                        <div class="icon-box green"><i class="ph-fill ph-check-circle"></i></div>
                        <span>Racha</span>
                    </div>
                    <div class="week-check" id="week-days-container">
                        <div class="day">L</div>
                        <div class="day">M</div>
                        <div class="day">X</div>
                        <div class="day">J</div>
                        <div class="day">V</div>
                        <div class="day">S</div>
                        <div class="day">D</div>
                    </div>
                    <div class="mini-label">Constancia Semanal</div>
                </div>

                <div class="bento-card wide-box" onclick="location.href='{{ url('/perfil') }}'">
                    <div class="left-col">
                         <div class="bento-header">
                            <div class="icon-box blue"><i class="ph-fill ph-scales"></i></div>
                            <span>Peso Actual</span>
                        </div>
                        <span class="value">
                            <span id="user-weight">--</span> <small>kg</small>
                        </span>
                    </div>
                    <div class="right-col">
                        <div class="trend up">
                            <i class="ph-bold ph-trend-up"></i> Actualizar
                        </div>
                    </div>
                </div>

            </section>
        </main>

        <nav class="floating-dock">
            <a href="{{ route('home') }}" class="dock-item active"><i class="ph-fill ph-house"></i></a>
            <a href="{{ route('nutrition') }}" class="dock-item"><i class="ph-bold ph-fork-knife"></i></a>
            <div class="dock-fab-container">
                <button class="dock-fab" id="video-trigger-btn"><i class="ph-fill ph-play"></i></button>
            </div>
            <a href="{{ route('training') }}" class="dock-item"><i class="ph-bold ph-barbell"></i></a>
            <a href="{{ url('/perfil') }}" class="dock-item"><i class="ph-bold ph-user"></i></a>
        </nav>
    </div>

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

    <script src="{{ asset('js/dashboard-logic.js') }}"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const triggerBtn = document.getElementById('video-trigger-btn');
            const closeBtn = document.getElementById('close-modal-btn');
            const modal = document.getElementById('video-modal');
            const video = document.getElementById('popup-video');
            if (triggerBtn && modal) {
                triggerBtn.addEventListener('click', () => { modal.classList.add('open'); if (video) video.play(); });
                const closeModal = () => { modal.classList.remove('open'); if (video) { video.pause(); video.currentTime = 0; } };
                closeBtn.addEventListener('click', closeModal);
                modal.addEventListener('click', (e) => { if (e.target === modal) closeModal(); });
            }
        });
    </script>
</body>
</html>