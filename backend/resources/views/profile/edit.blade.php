<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FitManager - Editar Perfil</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <link rel="stylesheet" href="{{ asset('assets/css/edit.css') }}">
</head>

<body>

    <main class="container fm-edit-container">
        <h4 class="center-align edit-main-title">Editar Perfil</h4>

        <div class="edit-card">
            <div class="edit-card-header">
                Información Personal
            </div>

            <div class="edit-card-content">
                <form action="{{ route('profile.update') }}" method="POST">
                @csrf
                @method('PUT')

                <div class="fm-input-wrapper-exact">
                    <i class="ph ph-phone"></i>
                    <input type="tel" name="phone" value="{{ auth()->user()->profile->phone ?? '' }}" class="fm-input-field-exact">
                </div>

                <div class="row" style="margin-bottom: 0;">
                    <div class="col s6" style="padding-left: 0;">
                        <label class="fm-label-exact">Prefijo</label>
                        <input type="text" value="+34" class="fm-input-field-exact no-icon">
                    </div>
                    <div class="col s6" style="padding-right: 0;">
                        <label class="fm-label-exact">Peso</label>
                        <div class="input-with-unit">
                            <input type="number" name="weight" value="{{ auth()->user()->profile->weight ?? '' }}" class="fm-input-field-exact no-icon">
                            <span class="unit">kg</span>
                        </div>
                    </div>
                </div>

                <div class="row" style="margin-bottom: 0;">
                    <div class="col s12" style="padding: 0;">
                        <label class="fm-label-exact">Altura</label>
                        <div class="input-with-unit">
                            <input type="number" name="height" value="{{ auth()->user()->profile->height ?? '' }}" class="fm-input-field-exact no-icon">
                            <span class="unit-select">cm</span>
                        </div>
                    </div>
                </div>

                <div class="row" style="margin-bottom: 0;">
                    <div class="col s12" style="padding: 0;">
                        <label class="fm-label-exact">Actividad Física</label>
                        <div class="input-with-unit">
                            <select name="activity" class="browser-default fm-input-field-exact no-icon">
                                <option value="baja" {{ (auth()->user()->profile->activity ?? '') == 'baja' ? 'selected' : '' }}>Baja</option>
                                <option value="moderada" {{ (auth()->user()->profile->activity ?? '') == 'moderada' ? 'selected' : '' }}>Moderada</option>
                                <option value="alta" {{ (auth()->user()->profile->activity ?? '') == 'alta' ? 'selected' : '' }}>Alta</option>
                            </select>
                        </div>
                    </div>
                </div>

                <input type="hidden" name="sex" id="sex-input" value="{{ auth()->user()->profile->sex ?? 'Hombre' }}">

                <label class="fm-label-exact">Sexo</label>
                <div class="gender-btn-group">
                    <div class="g-btn {{ (auth()->user()->profile->sex ?? 'Hombre') == 'Hombre' ? 'active' : '' }}" onclick="updateSex('Hombre', this)">Hombre</div>
                    <div class="g-btn {{ (auth()->user()->profile->sex ?? '') == 'Mujer' ? 'active' : '' }}" onclick="updateSex('Mujer', this)">Mujer</div>
                </div>

                <button type="submit" class="btn waves-effect waves-light fm-btn-green-exact">
                    Guardar Cambios
                </button>
            </form>

            @if(session('success'))
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    M.toast({
                        html: '<span><i class="ph-bold ph-check-circle" style="vertical-align: middle; margin-right: 8px;"></i> {{ session("success") }}</span>',
                        classes: 'rounded green darken-1',
                        displayLength: 4000
                    });
                });
            </script>
            @endif

            <script>
                function updateSex(sexo, el) {
                    // 1. Buscamos el input oculto que tiene el name="sex"
                    const sexInput = document.getElementById('sex-input');
                    
                    // 2. IMPORTANTE: Asignamos el nuevo valor
                    sexInput.value = sexo; 
                    
                    // 3. Cambiamos la clase visual
                    document.querySelectorAll('.g-btn').forEach(btn => btn.classList.remove('active'));
                    el.classList.add('active');
                    
                    // Prueba esto: abre la consola (F12) y pulsa el botón, debería salir el mensaje
                    console.log("Dato preparado para enviar:", sexInput.value);
                }
            </script>
            </div>
        </div>
    </main>

    <footer class="fm-bottom-nav white z-depth-2"
        style="display: flex; justify-content: space-around; align-items: center; position: fixed; bottom: 0; width: 100%; height: 65px; padding: 0; background-color: white; border-top: 1px solid #f0f0f0;">

        <a href="/home" class="nav-item"
            style="display: flex; flex-direction: column; align-items: center; text-decoration: none; color: #5f6368;">
            <i class="ph-bold ph-house" style="font-size: 24px;"></i>
            <span style="font-size: 12px;">Inicio</span>
        </a>

        <a href="/nutrition" class="nav-item"
            style="display: flex; flex-direction: column; align-items: center; text-decoration: none; color: #5f6368;">
            <i class="ph-bold ph-unite" style="font-size: 24px;"></i>
            <span style="font-size: 12px;">Nutrición</span>
        </a>

        <a href="/training" class="nav-item"
            style="display: flex; flex-direction: column; align-items: center; text-decoration: none; color: #5f6368;">
            <i class="ph-bold ph-barbell" style="font-size: 24px;"></i>
            <span style="font-size: 12px;">Entrenamiento</span>
        </a>

        <a href="/perfil" class="nav-item active"
            style="display: flex; flex-direction: column; align-items: center; text-decoration: none; color: #1a73e8;">
            <i class="ph-fill ph-user" style="font-size: 24px;"></i>
            <span style="font-size: 12px; font-weight: bold;">Perfil</span>
        </a>
    </footer>

</body>

</html>