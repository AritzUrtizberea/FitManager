<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Perfil - FitManager</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">

    <style>
        :root {
            /* Paleta de Colores Vibrante */
            --primary: #3b82f6;
            --primary-dark: #2563eb;
            --teal: #10b981;
            --amber: #f59e0b;
            --purple: #8b5cf6;

            --bg-body: #F8FAFC;
            --bg-card: #FFFFFF;
            --input-bg: #F1F5F9;
            --text-main: #1e293b;
            --text-muted: #64748b;

            --radius-card: 28px;
            --radius-input: 18px;
        }

        body {
            background-color: var(--bg-body);
            font-family: 'Manrope', sans-serif;
            color: var(--text-main);
            padding-bottom: 40px;
            -webkit-font-smoothing: antialiased;
            overflow-x: hidden;
        }

        body::before {
            content: '';
            position: fixed;
            top: -30%;
            left: -10%;
            width: 120%;
            height: 50%;
            background: radial-gradient(circle at 50% 0%, rgba(59, 130, 246, 0.12), transparent 70%);
            z-index: -1;
            pointer-events: none;
        }

        /* --- HEADER --- */
        .header-clean {
            padding: 20px 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .btn-back {
            width: 42px;
            height: 42px;
            border-radius: 14px;
            background: var(--bg-card);
            color: var(--text-main);
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            transition: all 0.2s ease;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.03);
            font-size: 20px;
        }

        .btn-back:active {
            transform: scale(0.95);
            background: #f1f5f9;
        }

        .header-title {
            font-weight: 800;
            font-size: 17px;
            letter-spacing: -0.3px;
        }

        /* --- CARD --- */
        .content-card {
            background: var(--bg-card);
            margin: 10px 20px;
            border-radius: var(--radius-card);
            padding: 35px 24px;
            box-shadow: 0 20px 40px -10px rgba(59, 130, 246, 0.08);
            position: relative;
            overflow: hidden;
        }

        /* --- AVATAR --- */
        .profile-header {
            text-align: center;
            margin-bottom: 35px;
        }

        .avatar-container {
            position: relative;
            width: 105px;
            height: 105px;
            margin: 0 auto 15px;
        }

        /* Modificado para soportar background-image dinámico */
        .avatar-img {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            object-fit: cover;
            background-color: #e2e8f0;
            background-position: center;
            background-size: cover;
            box-shadow: 0 8px 20px rgba(59, 130, 246, 0.15);
            border: 4px solid white;
        }

        .btn-edit-photo {
            position: absolute;
            bottom: 2px;
            right: 2px;
            background: linear-gradient(135deg, var(--primary), var(--purple));
            color: white;
            width: 34px;
            height: 34px;
            border-radius: 50%;
            border: 3px solid white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 15px;
            cursor: pointer;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
            transition: transform 0.2s;
        }

        .btn-edit-photo:active {
            transform: scale(0.9);
        }

        /* --- TITULOS --- */
        .divider-label {
            font-size: 12px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 1.2px;
            margin: 30px 0 15px 5px;
            display: flex;
            align-items: center;
        }

        .divider-label::after {
            content: '';
            flex: 1;
            height: 2px;
            background: currentColor;
            opacity: 0.1;
            margin-left: 12px;
            border-radius: 2px;
        }

        /* --- INPUTS --- */
        .smart-input {
            background: var(--input-bg);
            border-radius: var(--radius-input);
            padding: 10px;
            display: flex;
            align-items: center;
            transition: all 0.3s ease;
            border: 1px solid transparent;
        }

        .smart-input:focus-within {
            background: #fff;
            border-color: var(--primary);
            box-shadow: 0 8px 25px -5px rgba(59, 130, 246, 0.15);
            transform: translateY(-2px);
        }

        .icon-box {
            width: 44px;
            height: 44px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
            margin-right: 12px;
            flex-shrink: 0;
        }

        .icon-blue {
            background: rgba(59, 130, 246, 0.1);
            color: var(--primary);
        }

        .icon-teal {
            background: rgba(16, 185, 129, 0.1);
            color: var(--teal);
        }

        .icon-amber {
            background: rgba(245, 158, 11, 0.1);
            color: var(--amber);
        }

        .input-wrapper {
            flex-grow: 1;
            min-width: 0;
        }

        .input-label-small {
            font-size: 11px;
            font-weight: 700;
            color: var(--text-muted);
            margin-bottom: 2px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            white-space: nowrap;
        }

        .field-clean {
            background: transparent;
            border: none;
            width: 100%;
            font-size: 16px;
            font-weight: 700;
            color: var(--text-main);
            outline: none;
            padding: 0;
        }

        .measurement-row {
            display: flex;
            align-items: baseline;
        }

        .measurement-input {
            background: transparent;
            border: none;
            font-size: 18px;
            font-weight: 700;
            color: var(--text-main);
            outline: none;
            padding: 0;
            width: 100%;
            min-width: 0;
            flex: 1;
        }

        .unit-text {
            font-size: 14px;
            font-weight: 800;
            color: var(--text-main);
            opacity: 0.6;
            margin-left: 4px;
            flex-shrink: 0;
        }

        /* --- GÉNERO --- */
        .gender-switch {
            background: var(--input-bg);
            padding: 5px;
            border-radius: 16px;
            display: flex;
            gap: 5px;
        }

        .gender-opt {
            flex: 1;
            text-align: center;
            padding: 12px;
            font-size: 14px;
            font-weight: 700;
            color: var(--text-muted);
            border-radius: 14px;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
        }

        .gender-opt.active {
            background: white;
            color: var(--primary);
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.15);
        }

        /* --- BOTÓN FINAL --- */
        .btn-save-main {
            margin-top: 35px;
            width: 100%;
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: white;
            border: none;
            padding: 18px;
            border-radius: 20px;
            font-size: 17px;
            font-weight: 800;
            cursor: pointer;
            transition: all 0.2s;
            box-shadow: 0 10px 30px -5px rgba(59, 130, 246, 0.5);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .btn-save-main:active {
            transform: scale(0.97);
            box-shadow: 0 5px 15px -5px rgba(59, 130, 246, 0.4);
        }

        /* Grid para poner las cajas lado a lado, pero que se adapten */
        .medidas-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            /* Dos columnas iguales */
            gap: 15px;
            /* Espacio entre ellas */
            margin-bottom: 20px;
        }

        /* En móviles muy muy pequeños, poner uno debajo de otro */
        @media (max-width: 380px) {
            .medidas-grid {
                grid-template-columns: 1fr;
            }
        }

        /* Diseño de la tarjeta gris */
        .measure-card {
            background-color: #F3F4F6;
            /* Gris clarito */
            border-radius: 15px;
            padding: 15px;
            display: flex;
            align-items: center;
            /* Centrar verticalmente */
            gap: 12px;
            position: relative;
            /* Para el borde rojo de error */
            transition: all 0.3s ease;
        }

        /* Si hay error, borde rojo */
        .measure-card.has-error {
            border: 2px solid #ef4444;
            background-color: #fef2f2;
        }

        /* El círculo del icono */
        .icon-circle {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            /* Que el icono no se aplaste nunca */
        }

        .icon-bg-green {
            background-color: #d1fae5;
            color: #059669;
        }

        .icon-bg-blue {
            background-color: #dbeafe;
            color: #2563eb;
        }

        /* Contenedor del texto e input */
        .measure-content {
            display: flex;
            flex-direction: column;
            width: 100%;
        }

        .measure-label {
            font-size: 0.75rem;
            font-weight: 700;
            color: #6B7280;
            text-transform: uppercase;
            margin-bottom: 2px;
        }

        .input-wrapper {
            display: flex;
            align-items: baseline;
            /* Alinear el número con el "kg" */
        }

        /* El input en sí: sin bordes, transparente y grande */
        .clean-input {
            width: 100%;
            background: transparent;
            border: none;
            font-size: 1.2rem;
            font-weight: 800;
            color: #111827;
            padding: 0;
            outline: none;
            /* Quitar borde azul al clicar */
            -moz-appearance: textfield;
            /* Firefox */
        }

        /* Truco para quitar las flechas de subir/bajar número en Chrome/Safari */
        .clean-input::-webkit-outer-spin-button,
        .clean-input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        .unit-text {
            font-size: 0.9rem;
            color: #6B7280;
            font-weight: 600;
            margin-left: 4px;
        }

        /* Mensaje de error pequeñito debajo */
        .error-msg-mini {
            color: #ef4444;
            font-size: 0.7rem;
            font-weight: bold;
            position: absolute;
            bottom: -18px;
            left: 0;
        }

        /* Estilo específico para fondo azul (como en tu diseño) */
        .card-blue {
            background-color: #EFF6FF;
            /* Azul muy clarito */
        }

        .icon-bg-dark-blue {
            background-color: #DBEAFE;
            color: #1D4ED8;
        }

        /* Para poner el prefijo y el teléfono juntos */
        .phone-row {
            display: flex;
            align-items: center;
            gap: 10px;
            width: 100%;
        }

        /* Selector limpio para el +34 */
        .clean-select {
            background: transparent;
            border: none;
            font-weight: 700;
            color: #2563eb;
            /* Azul fuerte */
            font-size: 1.1rem;
            outline: none;
            cursor: pointer;
            padding-right: 5px;
            /* Espacio extra */
        }
    </style>
</head>

<body>

    <header class="header-clean">
        <a href="/perfil" class="btn-back">
            <i class="ph-bold ph-caret-left"></i>
        </a>
        <span class="header-title">Editar Perfil</span>
        <div style="width: 42px;"></div>
    </header>

    <form method="post" action="{{ route('profile.update') }}" enctype="multipart/form-data">
        @csrf
        @method('put')

        <div class="content-card">

            <input type="file" id="avatar-input" name="avatar" accept="image/*" style="display: none;"
                onchange="previewImage(event)">

            <div class="profile-header text-center mb-4">
                <div class="avatar-container"
                    style="width: 105px; height: 105px; margin: 0 auto 15px; position: relative;">

                    @if(auth()->user()->profile_photo_url)
                        <div id="avatar-preview" style="
                                width: 100%;
                                height: 100%;
                                border-radius: 50%;
                                background-image: url('{{ auth()->user()->profile_photo_url }}');
                                background-size: cover;
                                background-position: center;
                                box-shadow: 0 8px 20px rgba(59, 130, 246, 0.15);
                                border: 4px solid white;
                            "></div>
                    @else
                        <div id="avatar-preview"
                            class="d-flex align-items-center justify-content-center bg-light text-primary fw-bold" style="
                                width: 100%;
                                height: 100%;
                                border-radius: 50%;
                                font-size: 40px; 
                                border: 4px solid white;
                                box-shadow: 0 8px 20px rgba(59, 130, 246, 0.15);
                             ">
                            {{ substr(auth()->user()->name, 0, 1) }}
                        </div>
                    @endif

                    <div class="btn-edit-photo" onclick="document.getElementById('avatar-input').click()"
                        style="cursor: pointer;">
                        <i class="ph-fill ph-camera"></i>
                    </div>
                </div>

                <h5 class="fw-bold m-0">{{ auth()->user()->name }}</h5>
                <p class="text-muted fw-medium small m-0">{{ auth()->user()->email }}</p>
            </div>

            <h4 style="color: #3B82F6; font-weight: 700; font-size: 0.9rem; letter-spacing: 1px; margin-top: 20px; margin-bottom: 10px;">
    CONTACTO
</h4>

<div class="medidas-grid" style="grid-template-columns: 1fr;"> <div class="measure-card card-blue @error('phone') has-error @enderror">
        <div class="icon-circle icon-bg-dark-blue">
            <i class="fas fa-phone-alt"></i> 
            📞
        </div>
        
        <div class="measure-content">
            <label for="phone" class="measure-label" style="color: #60A5FA;">Teléfono Móvil</label>
            
            <div class="phone-row">
                <select class="clean-select">
                    <option value="+34">+34 🇪🇸</option>
                    <option value="+1">+1 🇺🇸</option>
                    <option value="+52">+52 🇲🇽</option>
                    </select>

                <input 
                    type="tel" 
                    id="phone"
                    name="phone" 
                    value="{{ old('phone', $user->profile->phone ?? '') }}"
                    class="clean-input"
                    placeholder="600 00 00 00"
                    style="color: #1E40AF;" 
                >
            </div>
        </div>

        @error('phone')
            <span class="error-msg-mini">{{ $message }}</span>
        @enderror
    </div>

            <h4
                style="color: #10B981; font-weight: 700; font-size: 0.9rem; letter-spacing: 1px; margin-top: 20px; margin-bottom: 10px;">
                MEDIDAS CORPORALES
            </h4>

            <div class="medidas-grid">

                <div class="measure-card @error('weight') has-error @enderror">
                    <div class="icon-circle icon-bg-green">
                        <i class="fas fa-weight"></i> ⚖️
                    </div>

                    <div class="measure-content">
                        <label for="weight" class="measure-label">Peso</label>
                        <div class="input-wrapper">
                            <input type="number" id="weight" name="weight"
                                value="{{ old('weight', $user->profile->weight ?? '') }}" class="clean-input"
                                placeholder="0" step="0.1">
                            <span class="unit-text">kg</span>
                        </div>
                    </div>

                    @error('weight')
                        <span class="error-msg-mini">{{ $message }}</span>
                    @enderror
                </div>

                <div class="measure-card @error('height') has-error @enderror">
                    <div class="icon-circle icon-bg-blue">
                        <i class="fas fa-ruler-vertical"></i> 📏
                    </div>

                    <div class="measure-content">
                        <label for="height" class="measure-label">Altura</label>
                        <div class="input-wrapper">
                            <input type="number" id="height" name="height"
                                value="{{ old('height', $user->profile->height ?? '') }}" class="clean-input"
                                placeholder="0">
                            <span class="unit-text">cm</span>
                        </div>
                    </div>

                    @error('height')
                        <span class="error-msg-mini">{{ $message }}</span>
                    @enderror
                </div>

            </div>

            <div>
                <span class="input-label-small ms-2 mb-2">Sexo Biológico</span>
                <input type="hidden" name="sex" id="sex-input" value="{{ auth()->user()->profile->sex ?? 'Hombre' }}">
                <div class="gender-switch">
                    <div class="gender-opt {{ (auth()->user()->profile->sex ?? 'Hombre') == 'Hombre' ? 'active' : '' }}"
                        onclick="updateSex('Hombre', this)">
                        Hombre
                    </div>
                    <div class="gender-opt {{ (auth()->user()->profile->sex ?? '') == 'Mujer' ? 'active' : '' }}"
                        onclick="updateSex('Mujer', this)">
                        Mujer
                    </div>
                </div>
            </div>

<div class="medidas-grid" style="grid-template-columns: 1fr;">
    
<h4 style="color: #D97706; font-weight: 700; font-size: 0.9rem; letter-spacing: 1px; margin-top: 20px; margin-bottom: 10px;">
                OBJETIVOS
            </h4>

            <div class="medidas-grid" style="grid-template-columns: 1fr;">
                
                <div class="measure-card @error('activity') has-error @enderror" style="background-color: #FFFBEB;"> 
                    <div class="icon-circle" style="background-color: #FEF3C7; color: #D97706;">
                        <i class="fas fa-bolt"></i>
                        ⚡
                    </div>
                    
                    <div class="measure-content">
    <label for="activity" class="measure-label" style="color: #92400E;">Nivel de Actividad</label>
    
    <div style="position: relative; width: 100%;">
        <select 
            name="activity" 
            id="activity" 
            class="clean-input" 
            style="width: 100%; cursor: pointer; color: #4B5563; background: transparent;"
        >
            <option value="" disabled selected>Selecciona una opción</option>
            
            <option value="sedentaria" 
                {{ (old('activity', auth()->user()->profile->activity ?? '') == 'sedentaria') ? 'selected' : '' }}>
                Sedentario (Poco ejercicio)
            </option>
            
            <option value="ligera" 
                {{ (old('activity', auth()->user()->profile->activity ?? '') == 'ligera') ? 'selected' : '' }}>
                Ligero (1-3 días/semana)
            </option>
            
            <option value="moderada" 
                {{ (old('activity', auth()->user()->profile->activity ?? '') == 'moderada') ? 'selected' : '' }}>
                Moderado (3-5 días/semana)
            </option>
            
            <option value="alta" 
                {{ (old('activity', auth()->user()->profile->activity ?? '') == 'alta') ? 'selected' : '' }}>
                Activo (6-7 días/semana)
            </option>
            
            <option value="muy_alta" 
                {{ (old('activity', auth()->user()->profile->activity ?? '') == 'muy_alta') ? 'selected' : '' }}>
                Muy Activo (Doble sesión)
            </option>
        </select>
    </div>
</div>
                
                    @error('activity')
                        <span class="error-msg-mini">{{ $message }}</span>
                    @enderror
                </div>

            </div>
            </div>

        <div style="margin: 25px 20px;">
            <button type="submit" class="btn-save-main">
                💾 Guardar Cambios
            </button>
        </div>

    </form>

    @if(session('success'))
        <div class="position-fixed top-0 start-50 translate-middle-x mt-4"
            style="z-index: 9999; width: 90%; max-width: 350px;">
            <div class="d-flex align-items-center text-white rounded-pill px-4 py-3 shadow-lg justify-content-center"
                style="background: linear-gradient(135deg, #10b981, #059669); box-shadow: 0 10px 25px -5px rgba(16, 185, 129, 0.5);">
                <i class="ph-fill ph-check-circle text-white me-2 fs-5"></i>
                <span class="fw-bold" style="font-size: 14px;">{{ session('success') }}</span>
            </div>
        </div>
    @endif

    <script>
        function updateSex(sexo, el) {
            document.getElementById('sex-input').value = sexo;
            document.querySelectorAll('.gender-opt').forEach(btn => btn.classList.remove('active'));
            el.classList.add('active');
        }

        // ========================================================
        // 🛑 AQUÍ ESTÁ LA SOLUCIÓN DEFINITIVA INTEGRADA 🛑
        // ========================================================
        function previewImage(event) {
            const file = event.target.files[0];

            // 1. COMPROBACIÓN INMEDIATA DE TAMAÑO
            // Si supera 1MB (1048576 bytes)
            if (file && file.size > 1048576) {
                // A) Mostramos alerta
                alert("⚠️ ¡IMAGEN MUY GRANDE!\n\nLa imagen supera el límite de 1MB.\nEl servidor la rechazará. Por favor, elige una más pequeña.");

                // B) Borramos el archivo del input para que NO se envíe
                event.target.value = '';

                // C) Paramos la ejecución (no mostramos preview)
                return false;
            }

            // 2. Si el tamaño es correcto, mostramos la preview normal
            if (file) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    const avatarDiv = document.getElementById('avatar-preview');

                    // Limpiamos clases de texto si las hubiera
                    avatarDiv.classList.remove('bg-light', 'text-primary', 'd-flex', 'align-items-center', 'justify-content-center');
                    avatarDiv.innerHTML = ''; // Borramos las iniciales

                    // Ponemos la imagen como fondo
                    avatarDiv.style.backgroundImage = `url(${e.target.result})`;
                    avatarDiv.style.backgroundColor = 'white'; // Asegurar fondo limpio
                }
                reader.readAsDataURL(file);
            }
        }

        const toast = document.querySelector('.position-fixed');
        if (toast) {
            setTimeout(() => {
                toast.style.transition = 'all 0.6s cubic-bezier(0.22, 1, 0.36, 1)';
                toast.style.opacity = '0';
                toast.style.transform = 'translate(-50%, -30px) scale(0.9)';
                setTimeout(() => toast.remove(), 600);
            }, 3000);
        }
    </script>
</body>

</html>