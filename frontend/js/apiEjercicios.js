document.addEventListener('DOMContentLoaded', function () {
    // 1. ELEMENTOS DEL DOM
    const listaRecomendados = document.getElementById('lista-ejercicios');
    const listaMia = document.getElementById('rutina-personalizada');
    const btnGuardar = document.getElementById('save-routine');
    const buscador = document.getElementById('buscador-total');
    const listaResultadosBusqueda = document.getElementById('resultados-busqueda');
    const listaGuardadas = document.getElementById('lista-rutinas-db');
    const contenedorMultiselect = document.getElementById('contenedor-multiselect');
    const btnAñadir = document.getElementById('btn-añadir-seleccionados');

    // Inicializar Componentes de Materialize
    M.Modal.init(document.querySelectorAll('.modal'));

    // --- 2. LÓGICA DE TIEMPOS Y DURACIÓN ---

    function calcularDuracionEstimada(nombre) {
        const n = nombre.toLowerCase();
        if (n.includes('sentadilla') || n.includes('press') || n.includes('peso muerto')) return 10;
        if (n.includes('biceps') || n.includes('triceps') || n.includes('elevación')) return 5;
        if (n.includes('plancha') || n.includes('crunch')) return 3;
        return 7; // Por defecto
    }

    function recalcularTiempoTotal() {
        const items = listaMia.querySelectorAll('.collection-item');
        let total = 0;
        items.forEach(item => {
            total += parseInt(item.dataset.duration || 0);
        });
        const contador = document.getElementById('tiempo-total');
        if (contador) contador.innerText = total;
    }

    // --- 3. GESTIÓN DE RUTINAS (API) ---

    // Función global para borrar (necesaria para el onclick en el HTML inyectado)
    window.eliminarRutina = function (id) {
        if (!confirm('¿Estás seguro de que quieres eliminar esta rutina?')) return;

        fetch(`/api/routines/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        })
            .then(res => {
                if (res.ok) {
                    M.toast({ html: 'Rutina eliminada con éxito' });
                    cargarRutinasGuardadas();
                } else {
                    M.toast({ html: 'Error al eliminar la rutina' });
                }
            })
            .catch(err => console.error("Error:", err));
    };

    // 1. Nueva versión de cargarRutinasGuardadas con cálculo de tiempo
    function cargarRutinasGuardadas() {
        if (!listaGuardadas) return;
        fetch('/api/routines', {
            headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
        })
            .then(res => res.json())
            .then(rutinas => {
                listaGuardadas.innerHTML = '';
                if (Array.isArray(rutinas) && rutinas.length > 0) {
                    rutinas.forEach(r => {
                        // CALCULAMOS EL TIEMPO TOTAL DE ESTA RUTINA GUARDADA
                        let tiempoTotalRutina = 0;
                        const chips = (r.exercises || []).map(e => {
                            const dur = calcularDuracionEstimada(e.name);
                            tiempoTotalRutina += dur;
                            return `<span class="chip blue white-text">${e.name}</span>`;
                        }).join('');

                        listaGuardadas.innerHTML += `
                <div class="collection-item card-panel" id="rutina-item-${r.id}" style="margin-bottom: 10px; border-left: 5px solid #26a69a;">
                    <div style="display:flex; justify-content:space-between; align-items:center;">
                        <div>
                            <span class="card-title"><b>${r.name}</b></span>
                            <br>
                            <small class="blue-text text-darken-2">
                                <i class="ph ph-clock"></i> Duración estimada: ${tiempoTotalRutina} min
                            </small>
                        </div>
                        <i class="ph ph-trash red-text" style="cursor:pointer; font-size:1.4rem;" onclick="eliminarRutina(${r.id})"></i>
                    </div>
                    <div style="margin-top:10px;">${chips}</div>
                </div>`;
                    });
                } else {
                    listaGuardadas.innerHTML = '<p class="center-align grey-text">No tienes rutinas guardadas.</p>';
                }
            });
    }

    // 2. Nueva versión de eliminarRutina (Asegurando el Token)
    window.eliminarRutina = function (id) {
        if (!confirm('¿Estás seguro de que quieres eliminar esta rutina?')) return;

        // Buscamos el token de Laravel
        const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

        fetch(`/api/routines/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': token,
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
            .then(async res => {
                if (res.ok) {
                    M.toast({ html: 'Rutina eliminada' });
                    // Eliminamos el elemento del DOM directamente para feedback instantáneo
                    const el = document.getElementById(`rutina-item-${id}`);
                    if (el) el.remove();
                    // Opcional: recargar lista completa
                    // cargarRutinasGuardadas(); 
                } else {
                    const errorData = await res.json();
                    console.error("Error del servidor:", errorData);
                    M.toast({ html: 'Error al eliminar: ' + (errorData.message || 'Desconocido') });
                }
            })
            .catch(err => {
                console.error("Error en la petición:", err);
                M.toast({ html: 'Error de red al eliminar' });
            });
    };

    // --- 4. CARGAR EJERCICIOS Y BUSCADOR ---

    function cargarMenuEjercicios() {
        fetch('/api/routines/recommendations', {
            headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
        })
            .then(res => res.json())
            .then(data => {
                // Pintar Sugerencias
                if (listaRecomendados && data.rutinas) {
                    listaRecomendados.innerHTML = '';
                    data.rutinas.forEach((rutina, idx) => {
                        let html = `<h6 class="blue-text" style="margin-top:25px"><b>${rutina.nombre}</b></h6>
                                <div class="collection sortable-list" id="rutina-sug-${idx}">`;
                        rutina.ejercicios.forEach(ex => {
                            const dur = calcularDuracionEstimada(ex.name);
                            html += `
                        <div class="collection-item" data-id="${ex.wger_id || ex.id}" data-name="${ex.name}" data-duration="${dur}">
                            <span class="handle"><i class="ph ph-dots-six-vertical"></i> ${ex.name}</span>
                            <span class="badge grey lighten-3 grey-text">${dur} min</span>
                        </div>`;
                        });
                        html += `</div>`;
                        listaRecomendados.innerHTML += html;
                    });

                    document.querySelectorAll('.sortable-list').forEach(el => {
                        new Sortable(el, { group: { name: 'fit', pull: 'clone', put: false }, animation: 150, handle: '.handle' });
                    });
                }

                // Lógica del Buscador
                if (buscador && data.todos) {
                    const renderBusqueda = (ejercicios) => {
                        listaResultadosBusqueda.innerHTML = '';
                        ejercicios.forEach(ex => {
                            const dur = calcularDuracionEstimada(ex.name);
                            listaResultadosBusqueda.innerHTML += `
                        <div class="collection-item" data-id="${ex.wger_id || ex.id}" data-name="${ex.name}" data-duration="${dur}">
                            <span class="handle"><i class="ph ph-dots-six-vertical"></i> <b>${ex.name}</b></span>
                            <i class="ph ph-plus right blue-text" tabindex = 0></i>
                        </div>`;
                        });
                        new Sortable(listaResultadosBusqueda, { group: { name: 'fit', pull: 'clone', put: false }, animation: 150, handle: '.handle' });
                    };

                    renderBusqueda(data.todos.slice(0, 5));
                    buscador.addEventListener('input', (e) => {
                        const term = e.target.value.toLowerCase();
                        const filtrados = data.todos.filter(ex => ex.name.toLowerCase().includes(term));
                        renderBusqueda(filtrados.slice(0, 15));
                    });
                }
            });
    }

    // --- 5. ZONA DE DESTINO (MI RUTINA) ---

    function formatearItemRutina(item) {
        const placeholder = listaMia.querySelector('.placeholder-text');
        if (placeholder) placeholder.remove();

        const nombreEx = item.dataset.name || item.innerText.trim();
        const dur = item.dataset.duration || calcularDuracionEstimada(nombreEx);

        // Aseguramos que el item mantenga su data-duration
        item.dataset.duration = dur;

        item.innerHTML = `
        <div style="padding: 12px; display: flex; justify-content: space-between; align-items: center; width: 100%;">
            <div class="handle" style="cursor: grab; display: flex; align-items: center; gap: 10px;">
                <i class="ph ph-dots-six-vertical grey-text"></i>
                <div>
                    <b>${nombreEx}</b><br>
                    <small class="blue-text">${dur} minutos estim.</small>
                </div>
            </div>
            <i class="ph ph-trash red-text btn-delete" style="cursor:pointer; font-size: 1.3rem;"></i>
        </div>`;

        item.querySelector('.btn-delete').onclick = (e) => {
            item.remove();
            recalcularTiempoTotal();
            if (listaMia.querySelectorAll('.collection-item').length === 0) {
                listaMia.innerHTML = '<p class="center-align placeholder-text" style="padding-top: 50px;">Arrastra ejercicios aquí</p>';
            }
        };
        recalcularTiempoTotal();
    }

    if (listaMia) {
        new Sortable(listaMia, {
            group: 'fit',
            animation: 150,
            handle: '.handle',
            onAdd: function (evt) {
                formatearItemRutina(evt.item);
            },
            onUpdate: function () {
                // Si solo cambian el orden, no afecta al tiempo, pero podrías guardarlo
            }
        });
    }

    // --- 6. MODAL MULTI-SELECCIÓN ---

    function llenarModalSeleccion() {
        contenedorMultiselect.innerHTML = '';
        const ejercicios = document.querySelectorAll('#lista-ejercicios [data-id], #resultados-busqueda [data-id]');
        const unicos = new Set();

        ejercicios.forEach(ej => {
            const id = ej.dataset.id;
            const nombre = ej.dataset.name;
            const dur = ej.dataset.duration;

            if (id && !unicos.has(id)) {
                unicos.add(id);
                const div = document.createElement('div');
                div.className = 'selectable-item';
                div.dataset.id = id;
                div.dataset.name = nombre;
                div.dataset.duration = dur;
                div.innerHTML = `
                    <span>${nombre} <br><small class="grey-text">${dur} min</small></span>
                    <i class="material-icons blue-text hide">check_circle</i>
                `;
                div.onclick = function () {
                    this.classList.toggle('selected');
                    this.querySelector('i').classList.toggle('hide');
                };
                contenedorMultiselect.appendChild(div);
            }
        });
    }

    // Abrir modal y llenarlo
    document.addEventListener('click', function (e) {
        if (e.target.closest('.modal-trigger')) {
            llenarModalSeleccion();
        }
    });

    if (btnAñadir) {
        btnAñadir.onclick = function () {
            const seleccionados = contenedorMultiselect.querySelectorAll('.selected');
            seleccionados.forEach(sel => {
                const nuevo = document.createElement('div');
                nuevo.className = 'collection-item';
                nuevo.dataset.id = sel.dataset.id;
                nuevo.dataset.name = sel.dataset.name;
                nuevo.dataset.duration = sel.dataset.duration;
                listaMia.appendChild(nuevo);
                formatearItemRutina(nuevo);
            });
            M.toast({ html: 'Ejercicios añadidos' });
        };
    }

    // --- 7. GUARDAR EN BD ---

    // --- 7. GUARDAR EN BD ---
    if (btnGuardar) {
        btnGuardar.onclick = function () {
            const nombreInput = document.getElementById('routine_name').value;
            const items = listaMia.querySelectorAll('.collection-item');
            const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

            if (!nombreInput || items.length === 0) return M.toast({ html: 'Ponle un nombre y añade ejercicios' });

            // Mapeo corregido para incluir rest_time y asegurar duration
            const ejerciciosArr = Array.from(items).map(item => ({
                exercise_id: item.dataset.id,
                duration: parseInt(item.dataset.duration) || 7
            }));

            fetch('/api/routines', {
                method: 'POST',
                credentials: 'include',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': token
                },
                credentials: 'include',
                body: JSON.stringify({ name: nombreInput, exercises: ejerciciosArr })
            })
                .then(async res => {
                    if (res.ok) return res.json();
                    const errorData = await res.json();
                    throw new Error(errorData.message || 'Error al guardar');
                })
                .then(() => {
                    M.toast({ html: '¡Rutina guardada correctamente!' });
                    document.getElementById('routine_name').value = '';
                    listaMia.innerHTML = '<p class="center-align placeholder-text" style="padding-top: 50px;">Arrastra ejercicios aquí</p>';
                    recalcularTiempoTotal();
                    cargarRutinasGuardadas();
                })
                .catch(err => {
                    console.error("Error detallado:", err);
                    M.toast({ html: 'Error: ' + err.message });
                });
        };

        // Función auxiliar para leer la cookie XSRF-TOKEN
        function getCookie(name) {
            let match = document.cookie.match(new RegExp('(^| )' + name + '=([^;]+)'));
            if (match) return decodeURIComponent(match[2]);
            return null;
        }
    }

    // Ejecución inicial
    cargarMenuEjercicios();
    cargarRutinasGuardadas();
});