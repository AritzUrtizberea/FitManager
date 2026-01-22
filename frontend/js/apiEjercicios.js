document.addEventListener('DOMContentLoaded', function () {
    // 1. Elementos del DOM
    const listaRecomendados = document.getElementById('lista-ejercicios');
    const listaMia = document.getElementById('rutina-personalizada');
    const btnGuardar = document.getElementById('save-routine');
    const buscador = document.getElementById('buscador-total');
    const listaResultadosBusqueda = document.getElementById('resultados-busqueda');
    const listaGuardadas = document.getElementById('lista-rutinas-db');

    const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

    if (!token) {
        console.error("Error: No se encontró el meta tag CSRF en el HTML.");
        return;
    }

    // 2. Sincronización silenciosa
    fetch('/api/ingest-exercises', {
        headers: { 'X-Requested-With': 'XMLHttpRequest' },
        credentials: 'same-origin'
    }).catch(err => console.log("Sincronización de fondo..."));

    // 3. FUNCIÓN PARA CARGAR (Obtener datos de la DB)
    function cargarRutinasGuardadas() {
        if (!listaGuardadas) return;

        fetch('/api/routines', {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            credentials: 'same-origin' // Fundamental para enviar la sesión
        })
            .then(res => {
                if (res.status === 401) throw new Error("No autorizado");
                return res.json();
            })
            .then(rutinas => {
                listaGuardadas.innerHTML = '';
                if (Array.isArray(rutinas) && rutinas.length > 0) {
                    rutinas.forEach(r => {
                        const ejercicios = r.exercises || [];
                        const chips = ejercicios.map(e => `<span class="chip blue white-text">${e.name}</span>`).join('');
                        listaGuardadas.innerHTML += `
                    <div class="collection-item card-panel" style="margin-bottom: 10px; border-left: 5px solid #26a69a;">
                        <span class="card-title"><b>${r.name}</b></span>
                        <div style="margin-top:10px;">${chips}</div>
                    </div>`;
                    });
                } else {
                    listaGuardadas.innerHTML = '<p class="center-align grey-text">No hay rutinas guardadas.</p>';
                }
            })
            .catch(err => console.error("Error al cargar:", err));
    }

    // 4. Cargar Menú y Recomendaciones
    function cargarMenuEjercicios() {
        fetch('/api/routines/recommendations', {
            headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
            credentials: 'same-origin'
        })
            .then(res => res.json())
            .then(data => {
                if (listaRecomendados && data.rutinas) {
                    listaRecomendados.innerHTML = '';
                    data.rutinas.forEach((rutina, idx) => {
                        let html = `<h6 class="blue-text" style="margin-top:25px"><b>${rutina.nombre}</b></h6>
                                <div class="collection sortable-list" id="rutina-sug-${idx}">`;
                        rutina.ejercicios.forEach(ex => {
                            html += `
                            <div class="collection-item" data-id="${ex.wger_id || ex.id}" data-name="${ex.name}">
                                <span>${ex.name}</span><i class="ph ph-plus right blue-text"></i>
                            </div>`;
                        });
                        html += `</div>`;
                        listaRecomendados.innerHTML += html;
                    });

                    document.querySelectorAll('.sortable-list').forEach(el => {
                        new Sortable(el, { group: { name: 'fit', pull: 'clone', put: false }, animation: 150 });
                    });
                }

                if (buscador && data.todos) {
                    const renderBusqueda = (ejercicios) => {
                        listaResultadosBusqueda.innerHTML = '';
                        ejercicios.forEach(ex => {
                            listaResultadosBusqueda.innerHTML += `
                            <div class="collection-item" data-id="${ex.wger_id || ex.id}" data-name="${ex.name}">
                                <b>${ex.name}</b><i class="ph ph-hand-grabbing right grey-text"></i>
                            </div>`;
                        });
                        new Sortable(listaResultadosBusqueda, { group: { name: 'fit', pull: 'clone', put: false }, animation: 150 });
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

    // 5. Sortable para Mi Rutina
    if (listaMia) {
        new Sortable(listaMia, {
            group: 'fit',
            animation: 150,
            onAdd: function (evt) {
                const placeholder = listaMia.querySelector('.placeholder-text');
                if (placeholder) placeholder.remove();
                const item = evt.item;
                const nombreEx = item.dataset.name;
                item.innerHTML = `
                    <div style="padding: 15px; background: white; border-bottom: 1px solid #eee;">
                        <b>${nombreEx}</b><i class="ph ph-trash right red-text btn-delete" style="cursor:pointer"></i>
                        <div class="grey-text" style="font-size: 0.8rem; margin-top:5px;">3 Series x 12 Reps</div>
                    </div>`;
                item.querySelector('.btn-delete').onclick = () => item.remove();
            }
        });
    }

    // 6. BOTÓN GUARDAR (Enviar datos a la DB)
    // Busca la sección del botón guardar (Punto 6) y asegúrate de que sea así:
    // 6. BOTÓN GUARDAR
    if (btnGuardar) {
        btnGuardar.onclick = function () {
            const nombreInput = document.getElementById('routine_name').value;
            const items = listaMia.querySelectorAll('.collection-item');

            // Capturamos el token justo antes de enviar
            const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

            if (!nombreInput || items.length === 0) return M.toast({ html: 'Falta nombre o ejercicios' });

            const ejercicios = Array.from(items).map(item => ({
                exercise_id: item.dataset.id
            }));

            fetch('/api/routines', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest', // Crucial para Laravel
                    'X-CSRF-TOKEN': token                // El sello de seguridad
                },
                body: JSON.stringify({ name: nombreInput, exercises: ejercicios })
            })
                .then(res => {
                    if (res.status === 419) throw new Error("Sesión expirada o falta token CSRF");
                    return res.ok ? res.json() : Promise.reject(res);
                })
                .then(() => {
                    M.toast({ html: '¡Rutina guardada!' });
                    document.getElementById('routine_name').value = '';
                    listaMia.innerHTML = '<p class="center-align placeholder-text" style="padding-top: 150px;">Arrastra aquí</p>';
                    cargarRutinasGuardadas();
                })
                .catch(err => {
                    console.error("Error completo:", err);
                    M.toast({ html: 'Error 419: Intenta recargar la página' });
                });
        };
    }

    cargarMenuEjercicios();
    cargarRutinasGuardadas();
});