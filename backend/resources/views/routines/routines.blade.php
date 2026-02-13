<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FitManager - Gestor de Rutinas</title>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    
    <link rel="stylesheet" href="{{ asset('css/routines.css') }}">
</head>

<body>
    <h1 class="sr-only">Gestor de Rutinas de Entrenamiento</h1>

    <div class="row" style="margin-top: 20px; margin-bottom: 0;">
        <div class="col s12 container" style="width: 95%; max-width: 1100px;">
            <a href="{{ route('training') }}" id="btn-volver" class="btn-flat waves-effect waves-grey fm-btn-back-custom" aria-label="Volver al panel de entrenamiento">
                <i class="ph ph-arrow-left" aria-hidden="true"></i>
                <span>Volver a Entrenamiento</span>
            </a>
        </div>
    </div>

    <div class="container" style="margin-top: 10px; width: 95%;">
        <div class="row main-row">

            <div class="col s12 m6">
                <div class="card-panel shadow-none" style="background: #f9f9f9; border: 1px solid #eee;">
                    <h2 class="blue-text" style="font-size: 1.3rem; margin-top: 0;"><i class="ph ph-magnifying-glass" aria-hidden="true"></i> Buscador Global</h2>

                    <div class="input-field">
                        <label for="buscador-total" class="active">Buscar ejercicio (ej: Press, Sentadilla...)</label>
                        <input type="text" id="buscador-total" placeholder="Escribe para buscar..." autocomplete="off">
                    </div>

                    <div id="resultados-busqueda" class="collection" role="list" aria-live="polite"></div>

                    <hr style="margin: 30px 0; border: 0; border-top: 1px solid #e0e0e0;">

                    <h2 class="fm-h2-contrast">
                        <i class="ph ph-sparkle" aria-hidden="true"></i> Sugerencias para ti
                    </h2>
                    <div id="lista-ejercicios" role="list" aria-label="Lista de ejercicios sugeridos">
                        <div class="fm-loader-container" role="progressbar" aria-label="Cargando ejercicios">
                            <div class="fm-dot" aria-hidden="true"></div>
                            <div class="fm-dot" aria-hidden="true"></div>
                            <div class="fm-dot" aria-hidden="true"></div>
                        </div>
                        <p class="loader-text" style="color: #454f5b !important;">Cargando rutinas inteligentes...</p>
                    </div>
                </div>
            </div>

            <div class="col s12 m6">
                <div class="card-panel"
                    style="position: sticky; top: 20px; border: 2px solid #1a73e8; box-shadow: 0 8px 24px rgba(26, 115, 232, 0.15);">
                    
                    <h2 style="margin-top: 0 !important; font-size: 1.3rem;"><i class="ph ph-barbell" aria-hidden="true"></i> Mi Rutina </h2>
                    
                    <p class="grey-text" style="margin-top: -5px; margin-bottom: 20px; font-size: 0.9rem;">
                        Tiempo total estimado: <b id="tiempo-total" class="blue-text">0</b> min
                    </p>

                    <div class="input-field" style="margin-bottom: 20px;">
                        <input id="routine_name" type="text" class="validate" required aria-required="true">
                        <label for="routine_name" class="active" style="color: #4a5568; font-weight: bold;">Nombre de la Rutina</label>
                    </div>

                    <div id="rutina-personalizada" class="collection" role="list" aria-label="Zona para soltar ejercicios y crear rutina">
                        <div class="placeholder-text center-align" style="padding-top: 80px; opacity: 0.6;">
                            <i class="ph ph-hand-grabbing" style="font-size: 30px; display: block; margin-bottom: 10px;" aria-hidden="true"></i>
                            Arrastra aquí tus ejercicios o usa el teclado
                        </div>
                    </div>

                    <div style="margin-top: 20px; display: flex; gap: 10px;">
                        <a href="#modal-ejercicios" id="trigger-añadir" class="waves-effect waves-light btn-flat modal-trigger"
                            style="flex: 1; border: 1px solid #1a73e8; color: #1a73e8; font-weight: bold;">
                            +
                        </a>
                    <button id="save-routine" class="btn-large" aria-label="Guardar esta rutina de entrenamiento">
                        <i class="ph ph-floppy-disk" aria-hidden="true"></i> Guardar Rutina
                    </button>
                    </div>

                    <div style="margin-top: 40px; padding-top: 20px; border-top: 1px solid #eee;">
                        <h2 style="font-size: 1.1rem !important; color: #4a5568;"><i class="ph ph-folder-star" aria-hidden="true"></i> Guardadas</h2>
                        <div id="lista-rutinas-db" class="collection" role="list" style="border: none;"></div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <div id="modal-ejercicios" class="modal modal-fixed-footer" role="dialog" aria-labelledby="modal-title">
        <div class="modal-content">
            <h2 id="modal-title" style="font-size: 1.4rem; font-weight: bold; margin-top:0;">Añadir Ejercicios</h2>
            <p class="grey-text">Selecciona los que quieras añadir a tu rutina:</p>
            <div id="contenedor-multiselect" role="group" aria-label="Selector múltiple de ejercicios"></div>
        </div>
        <div class="modal-footer">
            <button class="modal-close waves-effect waves-red btn-flat">Cancelar</button>
            <button id="btn-añadir-seleccionados" class="modal-close waves-effect waves-green btn blue">
                Añadir Seleccionados
            </button>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
    <script src="{{ asset('js/apiEjercicios.js') }}"></script>

</body>
</html>