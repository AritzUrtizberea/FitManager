<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>FitManager - Perfil</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;700;800&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('css/configuration.css') }}">
    <link rel="stylesheet" href="{{ asset('css/chatbot.css') }}">
    <link rel="stylesheet" href="{{ asset('css/perfil.css') }}"> 
</head>

<body>

    <main>
        <section class="profile-header">
            <div class="nav-header">
                <a href="{{ route('profile.edit') }}">
                    <i class="ph ph-gear-six" style="font-size: 24px; color: #333;"></i>
                </a>
            </div>

            <div style="width: 100px; height: 100px; margin: 0 auto 15px; position: relative;">
                
                <div id="avatar-placeholder" class="avatar-placeholder" style="margin: 0;">
                    <i class="ph-fill ph-user"></i>
                </div>

                <img id="avatar-image" 
                     src="" 
                     alt="Avatar"
                     style="
                        display: none;
                        width: 100%;
                        height: 100%;
                        border-radius: 50%;
                        object-fit: cover;
                        border: 4px solid white;
                        box-shadow: 0 8px 25px rgba(26, 115, 232, 0.15);
                        background-color: #f0f0f0;
                     ">
            </div>

            <h1 id="profile-name" class="user-name">Cargando...</h1>
            <span class="user-badge">Miembro FitManager</span>
        </section>

        <div class="row stats-container">
            <div class="col s4">
                <div class="stat-box">
                    <i class="ph-duotone ph-scales stat-icon c-blue"></i>
                    <span class="stat-val" id="profile-weight">--</span>
                    <span class="stat-lab">Peso (kg)</span>
                </div>
            </div>
            <div class="col s4">
                <div class="stat-box">
                    <i class="ph-duotone ph-ruler stat-icon c-green"></i>
                    <span class="stat-val" id="profile-height">--</span>
                    <span class="stat-lab">Altura (cm)</span>
                </div>
            </div>
            <div class="col s4">
                <div class="stat-box">
                    <i class="ph-duotone ph-fire stat-icon c-orange"></i>
                    <span class="stat-val" id="profile-streak">0</span>
                    <span class="stat-lab">Racha</span>
                </div>
            </div>
        </div>

        <h6 class="menu-section-title">Cuenta</h6>

        <ul class="menu-list">
            <li>
                <a href="{{ route('profile.edit') }}" class="menu-item">
                    <div class="menu-left">
                        <i class="ph ph-pencil-simple menu-icon"></i>
                        <span class="menu-text">Editar Perfil</span>
                    </div>
                    <i class="ph ph-caret-right menu-chevron"></i>
                </a>
            </li>

            <li>
                <a href="{{ route('reviews.index') }}" class="menu-item">
                    <div class="menu-left">
                        <i class="ph ph-star menu-icon" style="color: #fbc02d;"></i>
                        <span class="menu-text">Mis Reseñas</span>
                    </div>
                    <i class="ph ph-caret-right menu-chevron"></i>
                </a>
            </li>

            <li>
                <a href="{{ route('privacidad') }}" class="menu-item">
                    <div class="menu-left">
                        <i class="ph ph-shield-check menu-icon"></i>
                        <span class="menu-text">Privacidad</span>
                    </div>
                    <i class="ph ph-caret-right menu-chevron"></i>
                </a>
            </li>

            <li>
                <button type="button" id="triggerLogout" class="menu-item logout">
                    <div class="menu-left">
                        <i class="ph ph-sign-out menu-icon"></i>
                        <span class="menu-text">Cerrar Sesión</span>
                    </div>
                </button>
            </li>
        </ul>

        <div id="modalLogout" class="modal-overlay">
            <div class="modal-box">
                <div class="modal-icon-container">
                    <i class="ph-fill ph-sign-out" style="font-size: 24px;"></i>
                </div>
                <h3 class="modal-title">¿Cerrar Sesión?</h3>
                <p class="modal-text">¿Estás seguro de que quieres salir de tu cuenta?</p>

                <div class="modal-actions">
                    <button id="cancelarLogout" class="btn-modal-cancel">Cancelar</button>
                    <button id="confirmarLogout" class="btn-modal-confirm">Salir</button>
                </div>
            </div>
        </div>

    </main>

    <div class="floating-dock">
        <a href="{{ route('home') }}" class="dock-item"><i class="ph-fill ph-house"></i></a>
        <a href="{{ route('nutrition') }}" class="dock-item"><i class="ph-bold ph-fork-knife"></i></a>
        <div class="dock-fab-container">
            <button class="dock-fab" id="video-trigger-btn"><i class="ph-fill ph-play"></i></button>
        </div>
        <a href="{{ route('training') }}" class="dock-item"><i class="ph-bold ph-barbell"></i></a>
        <a href="{{ route('perfil') }}" class="dock-item active"><i class="ph-bold ph-user"></i></a>
    </div>

    <script src="{{ asset('js/chatbot.js') }}"></script>
    <script src="{{ asset('js/perfil.js') }}"></script>
</body>
</html>