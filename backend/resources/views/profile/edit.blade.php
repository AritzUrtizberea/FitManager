<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Perfil - FitManager</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">

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
            box-shadow: 0 4px 10px rgba(0,0,0,0.03);
            font-size: 20px;
        }
        .btn-back:active { transform: scale(0.95); background: #f1f5f9; }

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
            box-shadow: 0 4px 8px rgba(0,0,0,0.15);
            transition: transform 0.2s;
        }
        .btn-edit-photo:active { transform: scale(0.9); }

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
             content: ''; flex: 1; height: 2px; background: currentColor; opacity: 0.1; margin-left: 12px; border-radius: 2px;
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
        
        .icon-blue { background: rgba(59, 130, 246, 0.1); color: var(--primary); }
        .icon-teal { background: rgba(16, 185, 129, 0.1); color: var(--teal); }
        .icon-amber { background: rgba(245, 158, 11, 0.1); color: var(--amber); }

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
        .btn-save-main:active { transform: scale(0.97); box-shadow: 0 5px 15px -5px rgba(59, 130, 246, 0.4); }

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
        
        <input type="file" id="avatar-input" name="avatar" accept="image/*" style="display: none;" onchange="previewImage(event)">

        <div class="profile-header text-center mb-4">
    <div class="avatar-container" style="width: 105px; height: 105px; margin: 0 auto 15px; position: relative;">
        
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
            <div id="avatar-preview" class="d-flex align-items-center justify-content-center bg-light text-primary fw-bold" 
                 style="
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

        <div class="btn-edit-photo" onclick="document.getElementById('avatar-input').click()" style="cursor: pointer;">
            <i class="ph-fill ph-camera"></i>
        </div>
    </div>

    <h5 class="fw-bold m-0">{{ auth()->user()->name }}</h5>
    <p class="text-muted fw-medium small m-0">{{ auth()->user()->email }}</p>
</div>

            <div class="divider-label" style="color: var(--primary);">Contacto</div>

            <div class="smart-input">
                <div class="icon-box icon-blue">
                    <i class="ph-fill ph-phone"></i>
                </div>
                <div class="input-wrapper">
                    <span class="input-label-small">Teléfono Móvil</span>
                    <div class="d-flex">
                         <select class="field-clean fw-bold" style="width: auto; margin-right: 8px; color: var(--primary);">
                            <option>+34</option>
                            <option>+1</option>
                        </select>
                        <input type="tel" name="phone" value="{{ auth()->user()->profile->phone ?? '' }}" class="field-clean" placeholder="000 000 000">
                    </div>
                </div>
            </div>

            <div class="divider-label" style="color: var(--teal);">Medidas Corporales</div>

            <div class="row g-3 mb-3">
                <div class="col-6">
                    <div class="smart-input mb-0">
                        <div class="icon-box icon-teal" style="width: 36px; height: 36px; font-size: 18px; margin-right: 10px;">
                            <i class="ph-fill ph-scales"></i>
                        </div>
                        <div class="input-wrapper">
                            <span class="input-label-small">Peso</span>
                            <div class="measurement-row">
                                <input type="number" name="weight" value="{{ auth()->user()->profile->weight ?? '' }}" class="measurement-input" placeholder="0">
                                <span class="unit-text">kg</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="smart-input mb-0">
                        <div class="icon-box icon-teal" style="width: 36px; height: 36px; font-size: 18px; margin-right: 10px;">
                            <i class="ph-fill ph-ruler"></i>
                        </div>
                        <div class="input-wrapper">
                            <span class="input-label-small">Altura</span>
                            <div class="measurement-row">
                                <input type="number" name="height" value="{{ auth()->user()->profile->height ?? '' }}" class="measurement-input" placeholder="0">
                                <span class="unit-text">cm</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div>
                <span class="input-label-small ms-2 mb-2">Sexo Biológico</span>
                <input type="hidden" name="sex" id="sex-input" value="{{ auth()->user()->profile->sex ?? 'Hombre' }}">
                <div class="gender-switch">
                    <div class="gender-opt {{ (auth()->user()->profile->sex ?? 'Hombre') == 'Hombre' ? 'active' : '' }}" onclick="updateSex('Hombre', this)">
                        Hombre
                    </div>
                    <div class="gender-opt {{ (auth()->user()->profile->sex ?? '') == 'Mujer' ? 'active' : '' }}" onclick="updateSex('Mujer', this)">
                        Mujer
                    </div>
                </div>
            </div>

            <div class="divider-label" style="color: var(--amber);">Objetivos</div>

            <div class="smart-input">
                <div class="icon-box icon-amber">
                    <i class="ph-fill ph-lightning"></i>
                </div>
                <div class="input-wrapper">
                    <span class="input-label-small">Nivel de Actividad</span>
                    <select name="activity" class="field-clean" style="cursor: pointer; background: transparent; appearance: none;">
                        <option value="baja" {{ (auth()->user()->profile->activity ?? '') == 'baja' ? 'selected' : '' }}>Sedentario</option>
                        <option value="moderada" {{ (auth()->user()->profile->activity ?? '') == 'moderada' ? 'selected' : '' }}>Activo (Moderado)</option>
                        <option value="alta" {{ (auth()->user()->profile->activity ?? '') == 'alta' ? 'selected' : '' }}>Atleta (Alto)</option>
                    </select>
                </div>
                <i class="ph-bold ph-caret-down text-muted small me-2"></i>
            </div>

            <button type="submit" class="btn-save-main">
                <i class="ph-bold ph-floppy-disk"></i>
                Guardar Cambios
            </button>

        </div>
    </form>

    @if(session('success'))
        <div class="position-fixed top-0 start-50 translate-middle-x mt-4" style="z-index: 9999; width: 90%; max-width: 350px;">
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
                reader.onload = function(e) {
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
        if(toast) {
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