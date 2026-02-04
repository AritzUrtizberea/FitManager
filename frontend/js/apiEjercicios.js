document.addEventListener('DOMContentLoaded', function () {

    // ==========================================
    // 1. DEFINICIÓN DE ELEMENTOS
    // ==========================================
    const listaRecomendados = document.getElementById('lista-ejercicios');
    const listaMia = document.getElementById('rutina-personalizada');

    // Inputs y Botones clave
    const buscador = document.getElementById('buscador-total');
    const inputNombreRutina = document.getElementById('routine_name');
    const linkAñadirRapido = document.getElementById('trigger-añadir');
    const btnGuardar = document.getElementById('save-routine');
    const btnVolver = document.getElementById('btn-volver');

    // Modal
    const btnAñadirSeleccionados = document.getElementById('btn-añadir-seleccionados');
    const contenedorMultiselect = document.getElementById('contenedor-multiselect');

    // Listas
    const listaResultadosBusqueda = document.getElementById('resultados-busqueda');
    const listaGuardadas = document.getElementById('lista-rutinas-db');


    M.Modal.init(document.querySelectorAll('.modal'));

    // ==========================================
    // 2. CONFIGURACIÓN DRAG & DROP (SORTABLE)
    // ==========================================
    
    // Opciones para que funcione fluido (SOLUCIÓN AL FALLO DE ARRASTRE)
    const sortableOptionsSource = {
        group: { name: 'fit', pull: 'clone', put: false },
        animation: 150,
        sort: false, 
        handle: '.handle', 
        forceFallback: true, // CLAVE: Usa el motor JS, evita bugs visuales
        fallbackOnBody: true, 
        swapThreshold: 0.65
    };

    // A. Inicializar Drag & Drop en MI RUTINA (Destino)
    if (listaMia) {
        new Sortable(listaMia, {
            group: 'fit',
            animation: 150,
            handle: '.handle',
            forceFallback: true,
            fallbackOnBody: true,
            swapThreshold: 0.65,
            onAdd: (evt) => formatearItemRutina(evt.item)
        });
    }

    // B. Inicializar Drag & Drop en BUSCADOR (Origen 1)
    if (listaResultadosBusqueda) {
        new Sortable(listaResultadosBusqueda, sortableOptionsSource);
    }

    // ==========================================
    // 3. SISTEMA DE NAVEGACIÓN TOTAL (TU LÓGICA RESTAURADA)
    // ==========================================

    document.addEventListener('keydown', function (e) {
        const active = document.activeElement;

        // --- FLECHA ABAJO ---
        if (e.key === 'ArrowDown') {

            // 1. NUEVO: Si estoy en el botón de VOLVER -> Bajar al Buscador
            if (active === btnVolver) {
                e.preventDefault();
                if (buscador) buscador.focus();
                return;
            }

            // A. SITUACIÓN: INPUTS -> LISTAS
            if (active === inputNombreRutina) {
                e.preventDefault();
                const primerItem = listaMia.querySelector('.collection-item');
                if (primerItem) primerItem.focus();
                else if (linkAñadirRapido) linkAñadirRapido.focus();
                return;
            }
            if (active === buscador) {
                e.preventDefault();
                const primerItem = listaResultadosBusqueda.querySelector('.collection-item');
                if (primerItem) primerItem.focus();
                else {
                    // Buscar en el tab visible
                    const tabActivo = listaRecomendados.querySelector('.tab-content-area[style*="display: block"]');
                    const targetContainer = tabActivo || listaRecomendados.querySelector('.tab-content-area');

                    if (targetContainer) {
                        const primeraSugerencia = targetContainer.querySelector('.collection-item');
                        if (primeraSugerencia) primeraSugerencia.focus();
                    }
                }
                return;
            }

            // B. SITUACIÓN: DENTRO DE UNA LISTA (Items)
            if (active.classList.contains('collection-item') || active.classList.contains('selectable-item')) {
                const next = active.nextElementSibling;

                // 1. Si hay un hermano siguiente, vamos a él
                if (next && (next.classList.contains('collection-item') || next.classList.contains('selectable-item'))) {
                    e.preventDefault();
                    next.focus();
                }
                // 2. Si NO hay hermano, saltamos al siguiente BLOQUE
                else {
                    const parentList = active.parentElement;

                    // CASO MODAL
                    if (active.classList.contains('selectable-item')) {
                        e.preventDefault();
                        if (btnAñadirSeleccionados) btnAñadirSeleccionados.focus();
                        return;
                    }

                    // CASO 1: Columna Derecha (Mi Rutina -> Botón Añadir)
                    if (parentList.id === 'rutina-personalizada') {
                        e.preventDefault();
                        if (linkAñadirRapido) linkAñadirRapido.focus();
                    }

                    // CASO 2: Columna Izquierda (Búsqueda -> Sugerencias)
                    else if (parentList.id === 'resultados-busqueda') {
                        e.preventDefault();
                        const primeraSugerencia = listaRecomendados.querySelector('.collection-item');
                        if (primeraSugerencia) primeraSugerencia.focus();
                    }

                    // CASO 3: Entre listas de sugerencias (TU CÓDIGO RESTAURADO)
                    else if (parentList.classList.contains('sortable-list')) {
                        e.preventDefault();
                        // Buscamos la siguiente lista visible
                        const tabActual = parentList.closest('.tab-content-area');
                        // Aquí, como ahora usamos Tabs, la navegación "hacia abajo" entre listas
                        // solo tiene sentido dentro del mismo tab o salir de la sección.
                        // Si no hay más en este tab, no hacemos nada o saltamos foco.
                    }

                    // CASO 4: Guardadas -> Botón Volver (o input nombre para ciclar)
                    else if (parentList.id === 'lista-rutinas-db') {
                        e.preventDefault();
                        // Al estar abajo del todo, podemos volver al principio (input nombre) o al botón volver
                        if (btnVolver) btnVolver.focus(); 
                    }
                }
                return;
            }

            // C. SITUACIÓN: BOTONES INTERMEDIOS
            if (active === linkAñadirRapido) {
                e.preventDefault();
                if (btnGuardar) btnGuardar.focus();
                return;
            }
            if (active === btnGuardar) {
                e.preventDefault();
                const primeraGuardada = listaGuardadas.querySelector('.collection-item');
                if (primeraGuardada) primeraGuardada.focus();
                else if (btnVolver) btnVolver.focus();
                return;
            }
        }

        // --- FLECHA ARRIBA ---
        else if (e.key === 'ArrowUp') {

            // NUEVO: Input buscador -> Botón Volver
            if (active === buscador || active === inputNombreRutina) {
                e.preventDefault();
                if (btnVolver) btnVolver.focus();
                return;
            }

            // A. MODAL
            if (active === btnAñadirSeleccionados) {
                e.preventDefault();
                const ultimoItem = contenedorMultiselect.lastElementChild;
                if (ultimoItem) ultimoItem.focus();
                return;
            }

            // B. Botón Volver -> Última Rutina Guardada
            if (active === btnVolver) {
                e.preventDefault();
                const ultimaGuardada = listaGuardadas.lastElementChild;
                if (ultimaGuardada) ultimaGuardada.focus();
                else if (btnGuardar) btnGuardar.focus();
                return;
            }

            // C. Listas Guardadas -> Botón Guardar
            if (active.parentElement === listaGuardadas && !active.previousElementSibling) {
                e.preventDefault();
                btnGuardar.focus();
                return;
            }

            // D. Botón Guardar -> Botón Añadir Rápido
            if (active === btnGuardar) {
                e.preventDefault();
                if (linkAñadirRapido) linkAñadirRapido.focus();
                return;
            }

            // E. Botón Añadir Rápido -> Último ejercicio de Mi Rutina
            if (active === linkAñadirRapido) {
                e.preventDefault();
                const items = listaMia.querySelectorAll('.collection-item');
                if (items.length > 0) items[items.length - 1].focus();
                else inputNombreRutina.focus();
                return;
            }

            // F. Navegación en Listas hacia arriba
            if (active.classList.contains('collection-item') || active.classList.contains('selectable-item')) {
                const prev = active.previousElementSibling;

                if (prev && (prev.classList.contains('collection-item') || prev.classList.contains('selectable-item'))) {
                    e.preventDefault();
                    prev.focus();
                }
                else {
                    const parentList = active.parentElement;

                    if (active.classList.contains('selectable-item')) return;

                    if (parentList.id === 'rutina-personalizada') {
                        e.preventDefault();
                        inputNombreRutina.focus();
                    }
                    else if (parentList.id === 'resultados-busqueda') {
                        e.preventDefault();
                        buscador.focus();
                    }
                    // TU CÓDIGO RESTAURADO PARA LISTAS ANIDADAS
                    else if (parentList.classList.contains('sortable-list')) {
                        // En la estructura de tabs, solo saltamos al buscador
                        e.preventDefault();
                        const ultimoResultado = listaResultadosBusqueda.lastElementChild;
                        if (ultimoResultado) ultimoResultado.focus();
                        else buscador.focus();
                    }
                }
                return;
            }
        }

        // --- FLECHAS LATERALES (Restaurado) ---
        else if (e.key === 'ArrowRight') {
            if (active.classList.contains('selectable-item') || active === btnAñadirSeleccionados) return;
            const parentId = active.parentElement ? active.parentElement.id : '';
            if (active === buscador || parentId === 'resultados-busqueda' || active.closest('#lista-ejercicios')) {
                e.preventDefault();
                const primerItem = listaMia.querySelector('.collection-item');
                if (primerItem) primerItem.focus();
                else inputNombreRutina.focus();
            }
        }
        else if (e.key === 'ArrowLeft') {
            if (active.classList.contains('selectable-item') || active === btnAñadirSeleccionados) return;
            const parentId = active.parentElement ? active.parentElement.id : '';
            if (active === inputNombreRutina || active === linkAñadirRapido || active === btnGuardar || active === btnVolver || parentId === 'rutina-personalizada' || parentId === 'lista-rutinas-db') {
                e.preventDefault();
                if (buscador.value !== '') {
                    const primerRes = listaResultadosBusqueda.querySelector('.collection-item');
                    if (primerRes) primerRes.focus();
                    else buscador.focus();
                } else {
                    buscador.focus();
                }
            }
        }

        // --- ENTER ---
        else if (e.key === 'Enter') {
            if (active === linkAñadirRapido || active === btnVolver || active === btnAñadirSeleccionados) return;
            if (active.classList.contains('collection-item')) {
                e.preventDefault();
                const actionIcon = active.querySelector('.action-icon');
                if (icon) icon.click();
            }
            if (active.classList.contains('selectable-item')) {
                e.preventDefault();
                active.click();
            }
        }
    });

    // Inputs (Restaurado)
    if (buscador) {
        buscador.addEventListener('keydown', (e) => {
            if (e.key === 'ArrowDown') {
                e.preventDefault();
                const primerItem = listaResultadosBusqueda.querySelector('.collection-item');
                if (primerItem) primerItem.focus();
                else {
                    const tabActivo = listaRecomendados.querySelector('.tab-content-area[style*="display: block"]');
                    const targetContainer = tabActivo || listaRecomendados.querySelector('.tab-content-area');
                    if (targetContainer) {
                        const primSug = targetContainer.querySelector('.collection-item');
                        if (primSug) primSug.focus();
                    }
                }
            }
        });
    }

    // ==========================================
    // 4. FUNCIONES LÓGICAS (Sin cambios)
    // ==========================================

    function calcularDuracionEstimada(nombre) {
        const n = nombre.toLowerCase();
        if (n.includes('sentadilla') || n.includes('press') || n.includes('muerto')) return 10;
        if (n.includes('biceps') || n.includes('triceps') || n.includes('elevación')) return 5;
        if (n.includes('plancha') || n.includes('crunch')) return 3;
        return 7;
    }

    function recalcularTiempoTotal() {
        const items = listaMia.querySelectorAll('.collection-item');
        let total = 0;
        items.forEach(item => total += parseInt(item.dataset.duration || 0));
        const contador = document.getElementById('tiempo-total');
        if (contador) contador.innerText = total;
    }

    window.agregarEjercicio = function (id, name, duration, btnElement) {
        const newItem = document.createElement('div');
        newItem.dataset.id = id;
        newItem.dataset.name = name;
        newItem.dataset.duration = duration;
        listaMia.appendChild(newItem);

        formatearItemRutina(newItem);
        M.toast({ html: `Añadido: ${name}`, classes: 'rounded green lighten-1' });

        if (btnElement && btnElement.closest('.collection-item')) {
            btnElement.closest('.collection-item').focus();
        }
    };

    function formatearItemRutina(item) {
        const placeholder = listaMia.querySelector('.placeholder-text');
        if (placeholder) placeholder.remove();

        const nombreEx = item.dataset.name || item.innerText.trim().split('\n')[0];
        const dur = item.dataset.duration || calcularDuracionEstimada(nombreEx);
        item.dataset.duration = dur;

        item.className = 'collection-item';
        item.setAttribute('tabindex', '0');
        item.setAttribute('role', 'button');
        item.setAttribute('aria-label', `Ejercicio ${nombreEx}, enter para borrar`);
        
        item.innerHTML = `
        <div style="padding: 5px; display: flex; justify-content: space-between; align-items: center; width: 100%;">
            <div class="handle" style="cursor: grab; display: flex; align-items: center; gap: 10px;">
                <i class="ph ph-dots-six-vertical grey-text" aria-hidden="true" style="font-size: 1.2rem;"></i>
                <div style="line-height: 1.2;">
                    <b style="font-size: 14px;">${nombreEx}</b><br>
                    <small class="blue-text" style="font-weight: 600;">${dur} min</small>
                </div>
            </div>
            <i class="ph ph-trash red-text action-icon" aria-hidden="true" style="cursor:pointer; font-size: 1.3rem;"></i>
        </div>`;

        const btnDelete = item.querySelector('.action-icon');
        btnDelete.onclick = function () {
            const nextFocus = item.nextElementSibling || item.previousElementSibling || inputNombreRutina;
            item.remove();
            recalcularTiempoTotal();
            if (listaMia.querySelectorAll('.collection-item').length === 0) {
                listaMia.innerHTML = `
                <div class="placeholder-text center-align" style="padding-top: 80px; opacity: 0.6;">
                    <i class="ph ph-hand-grabbing" style="font-size: 30px; display: block; margin-bottom: 10px;"></i>
                    Arrastra aquí tus ejercicios
                </div>`;
            }
            if (nextFocus) nextFocus.focus();
        };
        recalcularTiempoTotal();
    }

    // ==========================================
    // 5. CARGA DE DATOS (API)
    // ==========================================

    function cargarMenuEjercicios() {
        fetch('/api/routines/recommendations', { headers: { 'Accept': 'application/json' } })
            .then(res => res.json())
            .then(data => {
                // TABS
                if (listaRecomendados && data.rutinas) {
                    listaRecomendados.innerHTML = '';
                    let tabsHtml = `<ul class="tabs">`;
                    let contentHtml = '';

                    data.rutinas.forEach((rutina, idx) => {
                        const tabId = `tab-rutina-${idx}`;
                        tabsHtml += `
                            <li class="tab" style="min-width: fit-content; padding: 0 10px;">
                                <a href="#${tabId}" class="${idx === 0 ? 'active' : ''} blue-text text-darken-2" style="font-weight:700; font-size:12px; white-space: nowrap;">
                                    ${rutina.nombre.toUpperCase()}
                                </a>
                            </li>`;

                        contentHtml += `
                        <div id="${tabId}" class="col s12 tab-content-area">
                            <div class="collection sortable-list" role="list">`;

                        rutina.ejercicios.forEach(ex => {
                            const dur = calcularDuracionEstimada(ex.name);
                            contentHtml += `
                                <div class="collection-item" tabindex="0" role="button"
                                     data-id="${ex.wger_id || ex.id}" data-name="${ex.name}" data-duration="${dur}"
                                     style="padding: 10px 15px;"> 
                                     <div style="display: flex; align-items: center; justify-content: space-between; width: 100%;">
                                         <div style="display: flex; align-items: center; gap: 12px; flex: 1;">
                                             <i class="ph ph-dots-six-vertical grey-text handle" style="cursor: grab; flex-shrink: 0; font-size: 1.2rem;"></i>
                                             <div style="display: flex; flex-direction: column; line-height: 1.3;">
                                                 <span style="font-weight: 600; font-size: 14px; color: #333;">${ex.name}</span>
                                                 <span class="grey-text" style="font-size: 12px;">
                                                     <i class="ph ph-clock" style="font-size: 11px; vertical-align: middle;"></i> ${dur} min
                                                 </span>
                                             </div>
                                         </div>
                                         <i class="ph ph-plus-circle blue-text action-icon" 
                                            style="font-size: 1.6rem; cursor: pointer; flex-shrink: 0; margin-left: 10px;"
                                            onclick="agregarEjercicio('${ex.wger_id || ex.id}', '${ex.name}', '${dur}', this)">
                                         </i>
                                     </div>
                                </div>`;
                        });
                        contentHtml += `</div></div>`;
                    });

                    tabsHtml += `</ul>`;
                    listaRecomendados.innerHTML = tabsHtml + contentHtml;

                    const tabsContainer = listaRecomendados.querySelector('.tabs');
                    M.Tabs.init(tabsContainer, { swipeable: false, duration: 300 });

                    // Inicializar Sortable con el FIX de forceFallback
                    listaRecomendados.querySelectorAll('.sortable-list').forEach(el => {
                        new Sortable(el, sortableOptionsSource);
                    });
                }

                // BUSCADOR
                if (buscador && data.todos) {
                    const renderBusqueda = (ejercicios) => {
                        listaResultadosBusqueda.innerHTML = '';
                        ejercicios.forEach(ex => {
                            const dur = calcularDuracionEstimada(ex.name);
                            listaResultadosBusqueda.innerHTML += `
                            <div class="collection-item" tabindex="0" role="button"
                                 data-id="${ex.wger_id || ex.id}" data-name="${ex.name}" data-duration="${dur}"
                                 style="padding: 10px 15px;">
                                <div style="display: flex; align-items: center; justify-content: space-between; width: 100%;">
                                    <div style="display: flex; align-items: center; gap: 12px; flex: 1;">
                                        <i class="ph ph-dots-six-vertical grey-text handle" style="cursor: grab; flex-shrink: 0;"></i>
                                        <div style="display: flex; flex-direction: column; line-height: 1.3;">
                                            <span style="font-weight: 600; font-size: 14px;">${ex.name}</span>
                                            <span class="grey-text" style="font-size: 12px;">${dur} min</span>
                                        </div>
                                    </div>
                                    <i class="ph ph-plus-circle blue-text action-icon" 
                                       style="font-size: 1.6rem; cursor: pointer; flex-shrink: 0; margin-left: 10px;"
                                       onclick="agregarEjercicio('${ex.wger_id || ex.id}', '${ex.name}', '${dur}', this)">
                                    </i>
                                </div>
                            </div>`;
                        });
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

    // Modal, Guardar y Cargar (Sin cambios, manteniendo tu lógica)
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
                div.className = 'selectable-item card-panel shadow-none';
                div.style.cssText = 'cursor:pointer; border:1px solid #ddd; margin-bottom:5px; padding:10px;';
                div.setAttribute('tabindex', '0');
                div.setAttribute('role', 'checkbox');
                div.setAttribute('aria-checked', 'false');
                div.dataset.id = id; div.dataset.name = nombre; div.dataset.duration = dur;
                div.innerHTML = `<div style="display:flex; justify-content:space-between; align-items:center;">
                        <span>${nombre} <small class="grey-text">(${dur} min)</small></span>
                        <i class="material-icons blue-text hide check-icon">check_circle</i>
                    </div>`;
                div.onclick = function () {
                    div.classList.toggle('selected');
                    const isSel = div.classList.contains('selected');
                    div.style.background = isSel ? '#e3f2fd' : 'white';
                    div.querySelector('.check-icon').classList.toggle('hide');
                    div.setAttribute('aria-checked', isSel);
                };
                contenedorMultiselect.appendChild(div);
            }
        });
        setTimeout(() => { const primer = contenedorMultiselect.querySelector('.selectable-item'); if (primer) primer.focus(); }, 300);
    }
    document.addEventListener('click', (e) => { if (e.target.closest('.modal-trigger')) llenarModalSeleccion(); });

    if (btnAñadirSeleccionados) {
        btnAñadirSeleccionados.onclick = function () {
            contenedorMultiselect.querySelectorAll('.selected').forEach(sel =>
                agregarEjercicio(sel.dataset.id, sel.dataset.name, sel.dataset.duration, null));
            M.toast({ html: 'Añadidos' });
            const modalInstance = M.Modal.getInstance(document.querySelector('.modal'));
            if (modalInstance) modalInstance.close();
            if (linkAñadirRapido) linkAñadirRapido.focus();
        };
    }

    function cargarRutinasGuardadas() {
        if (!listaGuardadas) return;
        fetch('/api/routines', { headers: { 'Accept': 'application/json' } }).then(res => res.json()).then(rutinas => {
            listaGuardadas.innerHTML = '';
            if (rutinas.length > 0) {
                rutinas.forEach(r => {
                    const chips = (r.exercises || []).map(e => `<span class="chip small">${e.name}</span>`).join('');
                    const div = document.createElement('div');
                    div.className = 'collection-item card-panel';
                    div.setAttribute('tabindex', '0');
                    div.style.cssText = 'margin-bottom:10px; border-left:5px solid #26a69a;';
                    div.innerHTML = `<div style="display:flex; justify-content:space-between; align-items:center;">
                        <span class="card-title"><b>${r.name}</b></span>
                        <i class="ph ph-trash red-text action-icon" style="cursor:pointer;"></i>
                    </div><div style="margin-top:5px; font-size:0.8rem">${chips}</div>`;
                    div.querySelector('.action-icon').onclick = () => eliminarRutina(r.id);
                    listaGuardadas.appendChild(div);
                });
            } else listaGuardadas.innerHTML = '<p class="center-align grey-text">No tienes rutinas guardadas.</p>';
        });
    }

    window.eliminarRutina = function (id) {
        if (!confirm('¿Borrar rutina?')) return;
        const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        fetch(`/api/routines/${id}`, { method: 'DELETE', headers: { 'X-CSRF-TOKEN': token } })
            .then(res => { if (res.ok) { M.toast({ html: 'Borrada' }); cargarRutinasGuardadas(); } });
    };

    if (btnGuardar) {
        btnGuardar.onclick = function () {
            const nombre = inputNombreRutina.value;
            const items = listaMia.querySelectorAll('.collection-item');
            if (!nombre || items.length === 0) return M.toast({ html: 'Falta nombre o ejercicios' });

            const ejercicios = Array.from(items).map(i => ({ exercise_id: i.dataset.id, duration: i.dataset.duration || 7 }));
            const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

            fetch('/api/routines', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': token },
                body: JSON.stringify({ name: nombre, exercises: ejercicios })
            }).then(res => {
                if (res.ok) {
                    M.toast({ html: 'Guardada' });
                    listaMia.innerHTML = `
                    <div class="placeholder-text center-align" style="padding-top: 80px; opacity: 0.6;">
                        <i class="ph ph-hand-grabbing" style="font-size: 30px; display: block; margin-bottom: 10px;"></i>
                        Arrastra aquí tus ejercicios
                    </div>`;
                    inputNombreRutina.value = '';
                    recalcularTiempoTotal();
                    cargarRutinasGuardadas();
                }
            });
        };
    }

    cargarMenuEjercicios();
    cargarRutinasGuardadas();
});