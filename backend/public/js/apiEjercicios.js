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

    let cacheEjercicios = [];

    M.Modal.init(document.querySelectorAll('.modal'));

    // ==========================================
    // 2. CONFIGURACIÓN DRAG & DROP (SORTABLE)
    // ==========================================

    // Opciones para que funcione fluido (SOLUCIÓN AL FALLO DE ARRASTRE)
    // SUSTITUYE TU CONFIGURACIÓN DE SORTABLE POR ESTA:
    const sortableOptionsSource = {
        group: { name: 'fit', pull: 'clone', put: false },
        animation: 150,
        sort: false,
        handle: '.handle',           // IMPORTANTE: Solo arrastra desde los puntos
        filter: 'button, .btn-flat', // No arrastrar si se pisa un botón
        preventOnFilter: false,      // Permite que el botón funcione al hacer clic
        tabIndex: -1,                // ARREGLA EL ERROR DE ACCESIBILIDAD (No da foco a la fila)
        forceFallback: true,         // CREA UN CLON VISUAL: Solución definitiva al "no arrastra"
        fallbackClass: "sortable-fallback",
        fallbackOnBody: true,
        swapThreshold: 0.65
    };

    // Asegúrate de aplicarlo también a la lista de destino (Mi Rutina)
    if (listaMia) {
        new Sortable(listaMia, {
            group: 'fit',
            animation: 150,
            handle: '.handle',
            tabIndex: -1,
            forceFallback: true,
            onAdd: function (evt) {
                formatearItemRutina(evt.item);
                recalcularTiempoTotal();
            }
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

            // A. SITUACIÓN: DESDE EL BUSCADOR (Hacia abajo)
            if (active === buscador) {
                if (e.key === 'ArrowDown') {
                    e.preventDefault();
                    // Buscamos el primer botón interactivo en los resultados o sugerencias
                    const primerBoton = listaResultadosBusqueda.querySelector('.exercise-focusable') ||
                        listaRecomendados.querySelector('.exercise-focusable');
                    if (primerBoton) primerBoton.focus();
                }
                return;
            }

            // B. SITUACIÓN: NAVEGANDO ENTRE BOTONES DE EJERCICIOS
            if (active.classList.contains('exercise-focusable') || active.classList.contains('selectable-item')) {
                if (e.key === 'ArrowDown') {
                    e.preventDefault();
                    const parentItem = active.closest('.collection-item') || active.closest('.selectable-item');
                    const nextItem = parentItem.nextElementSibling;

                    if (nextItem) {
                        // Saltamos al botón del siguiente ejercicio
                        const nextBtn = nextItem.querySelector('.exercise-focusable') || nextItem;
                        if (nextBtn) nextBtn.focus();
                    } else {
                        // Lógica de salto entre bloques (Si no hay más items en la lista actual)
                        const parentList = parentItem.parentElement;

                        if (parentList.id === 'resultados-busqueda') {
                            const primeraSugerencia = listaRecomendados.querySelector('.exercise-focusable');
                            if (primeraSugerencia) primeraSugerencia.focus();
                        }
                        else if (parentList.id === 'rutina-personalizada') {
                            if (linkAñadirRapido) linkAñadirRapido.focus();
                        }
                        else if (active.classList.contains('selectable-item')) {
                            if (btnAñadirSeleccionados) btnAñadirSeleccionados.focus();
                        }
                    }
                }

                if (e.key === 'ArrowUp') {
                    e.preventDefault();
                    const parentItem = active.closest('.collection-item') || active.closest('.selectable-item');
                    const prevItem = parentItem.previousElementSibling;

                    if (prevItem) {
                        const prevBtn = prevItem.querySelector('.exercise-focusable') || prevItem;
                        if (prevBtn) prevBtn.focus();
                    } else {
                        // Si estamos al principio de la lista, volvemos al buscador o al nombre
                        if (active.closest('#resultados-busqueda') || active.closest('#lista-ejercicios')) {
                            buscador.focus();
                        } else if (active.closest('#rutina-personalizada')) {
                            inputNombreRutina.focus();
                        }
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

            // CORRECCIÓN AQUÍ:
            if (active.classList.contains('collection-item')) {
                e.preventDefault();
                // Buscamos el icono
                const elIcono = active.querySelector('.action-icon');
                // Hacemos click en él si existe
                if (elIcono) elIcono.click();
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

    // BUSCA ESTA FUNCIÓN Y CÁMBIALA ENTERA POR ESTO:

    window.formatearItemRutina = function (item) {
        // 1. Limpieza inicial
        const placeholder = document.getElementById('rutina-personalizada').querySelector('.placeholder-text');
        if (placeholder) placeholder.remove();

        // 2. Obtener datos
        const nombreEx = item.dataset.name || item.innerText.trim();
        const duracion = item.dataset.duration || "10";

        // 3. Configurar la fila para ACCESIBILIDAD (Clave AAA)
        item.className = 'collection-item';
        item.setAttribute('role', 'listitem');
        item.removeAttribute('tabindex'); // ELIMINAMOS EL FOCO DE LA FILA, SOLO LOS BOTONES LO RECIBEN

        // ESTO CORRIGE EL ERROR "ROLE BUTTON":
        item.setAttribute('role', 'listitem');
        item.removeAttribute('tabindex'); // La fila no recibe foco, solo sus botones

        // 4. HTML interno accesible (Iconos ocultos + Botones claros)
        item.innerHTML = `
    <div style="display: flex; align-items: center; width: 100%;">
        <div class="order-controls">
            <button type="button" class="btn-order" onclick="moverItem(this, -1)" aria-label="Subir ejercicio">
                <i class="ph ph-caret-up" aria-hidden="true"></i>
            </button>
            <button type="button" class="btn-order" onclick="moverItem(this, 1)" aria-label="Bajar ejercicio">
                <i class="ph ph-caret-down" aria-hidden="true"></i>
            </button>
        </div>

        <div class="handle" style="margin-right:10px; cursor:grab;" title="Arrastrar para reordenar">
            <i class="ph ph-dots-six-vertical" style="font-size:20px;" aria-hidden="true"></i>
        </div>

        <div style="flex-grow: 1; display: flex; flex-direction: column;">
            <span style="font-weight: 800; color: #1a1f36; line-height: 1.2;">${nombreEx}</span>
            <small class="blue-text" style="font-weight: 700; color: #1558b0 !important;">${duracion} min</small>
        </div>

        <button type="button" class="btn-flat" onclick="eliminarItem(this)" aria-label="Eliminar ${nombreEx} de la rutina">
            <i class="ph ph-trash fm-danger-text-contrast" aria-hidden="true"></i>
        </button>
    </div>
`;

        // Recalcular tiempo si tienes esa función
        if (typeof recalcularTiempoTotal === 'function') {
            recalcularTiempoTotal();
        }
    };

    // ==========================================
    // 5. CARGA DE DATOS (API)
    // ==========================================

function cargarMenuEjercicios() {
    fetch('/api/routines/recommendations', { headers: { 'Accept': 'application/json' } })
        .then(res => res.json())
        .then(data => {
            // --- NUEVO: Guardamos todos los ejercicios en la cache para el modal ---
            cacheEjercicios = data.todos || [];
                if (listaRecomendados && data.rutinas) {
                    listaRecomendados.innerHTML = '';

                    // --- REFERENCIA AL POP-UP ---
                    const contenedorPopup = document.getElementById('contenedor-multiselect');
                    if (contenedorPopup) contenedorPopup.innerHTML = '';

                    let tabsHtml = `<ul class="tabs" role="tablist" tabindex="0" aria-label="Categorías de ejercicios">`;
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

                            // 1. Esto llena la lista normal de la izquierda
                            contentHtml += `
                        <div class="collection-item" role="listitem" style="padding: 0;"> 
                            <div style="display: flex; align-items: center; justify-content: space-between; width: 100%; padding: 10px 15px;">
                                <div style="display: flex; align-items: center; gap: 5px; flex: 1;">
                                    <div class="handle" style="padding: 10px; cursor: grab; display: flex; align-items: center;">
                                        <i class="ph ph-dots-six-vertical grey-text" style="font-size: 1.4rem;" aria-hidden="true"></i>
                                    </div>
                                    <div style="display: flex; flex-direction: column; line-height: 1.3;">
                                        <span style="font-weight: 600; font-size: 14px; color: #1a1f36;">${ex.name}</span>
                                        <span class="grey-text" style="font-size: 12px; display: flex; align-items: center; gap: 4px;">
                                            <i class="ph ph-clock" aria-hidden="true"></i> ${dur} min
                                        </span>
                                    </div>
                                </div>
                                <button type="button" class="btn-flat action-icon exercise-focusable" 
                                    onclick="agregarEjercicio('${ex.wger_id || ex.id}', '${ex.name}', '${dur}', this)"
                                    aria-label="Añadir ${ex.name}" style="margin: 0; padding: 8px;">
                                    <i class="ph ph-plus-circle blue-text" style="font-size: 1.6rem;" aria-hidden="true"></i>
                                </button>
                            </div>
                        </div>`;

                            // 2. <--- ESTO LLENA EL POP-UP (Añadimos el checkbox) --->
                            if (contenedorPopup) {
                                const p = document.createElement('p');
                                p.style.marginBottom = "10px";
                                p.innerHTML = `
                                <label>
                                    <input type="checkbox" class="filled-in" value="${ex.wger_id || ex.id}" data-name="${ex.name}" data-dur="${dur}" />
                                    <span style="color:#1a1f36; font-weight:500;">${ex.name}</span>
                                </label>
                            `;
                                contenedorPopup.appendChild(p);
                            }
                        });
                        contentHtml += `</div></div>`;
                    });

                    tabsHtml += `</ul>`;
                    listaRecomendados.innerHTML = tabsHtml + contentHtml;

                    const tabsContainer = listaRecomendados.querySelector('.tabs');
                    M.Tabs.init(tabsContainer, { swipeable: false, duration: 300 });
                    activarArrastreTabs('.tabs');

                    listaRecomendados.querySelectorAll('.sortable-list').forEach(el => {
                        new Sortable(el, sortableOptionsSource);
                    });
                }

                // ... (Resto del código del buscador igual que lo tienes)
                if (buscador && data.todos) {
                    const renderBusqueda = (ejercicios) => {
                        listaResultadosBusqueda.innerHTML = '';
                        ejercicios.forEach(ex => {
                            const dur = calcularDuracionEstimada(ex.name);
                            listaResultadosBusqueda.innerHTML += `
                            <div class="collection-item" role="listitem">
                                <div style="display: flex; align-items: center; justify-content: space-between; width: 100%;">
                                    <div style="display: flex; align-items: center; gap: 12px; flex: 1;">
                                        <i class="ph ph-dots-six-vertical grey-text handle" style="cursor:grab; padding:10px;" aria-hidden="true"></i>
                                        <div style="display: flex; flex-direction: column;">
                                            <span style="font-weight: 600;">${ex.name}</span>
                                            <span class="grey-text" style="font-size: 12px;">${dur} min</span>
                                        </div>
                                    </div>
                                    <button type="button" class="btn-flat exercise-focusable" 
                                        onclick="agregarEjercicio('${ex.wger_id || ex.id}', '${ex.name}', '${dur}', this)">
                                        <i class="ph ph-plus-circle blue-text" style="font-size: 1.6rem;"></i>
                                    </button>
                                </div>
                            </div>`;
                        });
                        new Sortable(listaResultadosBusqueda, sortableOptionsSource);
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
    // ==========================================
// 6. LÓGICA DEL MODAL (CORREGIDA)
// ==========================================
function llenarModalSeleccion() {
    const contenedorMultiselect = document.getElementById('contenedor-multiselect');
    const modalElem = document.getElementById('modal-ejercicios');
    
    let instance = M.Modal.getInstance(modalElem);
    if (!instance) instance = M.Modal.init(modalElem);

    contenedorMultiselect.innerHTML = '';
    
    // Si la cache está vacía, intentamos sacarlos del DOM por si acaso
    let ejerciciosParaMostrar = cacheEjercicios;

    if (ejerciciosParaMostrar.length === 0) {
        // Plan B: Intentar leer lo que haya en pantalla
        const itemsEnPantalla = document.querySelectorAll('#lista-ejercicios [data-id]');
        itemsEnPantalla.forEach(el => {
            ejerciciosParaMostrar.push({
                id: el.dataset.id,
                name: el.dataset.name,
                duration: el.dataset.duration
            });
        });
    }

    if (ejerciciosParaMostrar.length === 0) {
        contenedorMultiselect.innerHTML = '<p class="center grey-text">Cargando datos de la API... Reintenta en un segundo.</p>';
        instance.open();
        return;
    }

    ejerciciosParaMostrar.forEach(ex => {
        const id = ex.wger_id || ex.id;
        const nombre = ex.name;
        const dur = ex.duration || calcularDuracionEstimada(nombre);

        const div = document.createElement('div');
        div.className = 'selectable-item card-panel shadow-none';
        div.style.cssText = 'cursor:pointer; border:1px solid #ddd; margin-bottom:5px; padding:12px;';
        div.setAttribute('tabindex', '0');
        div.dataset.id = id; 
        div.dataset.name = nombre; 
        div.dataset.duration = dur;

        div.innerHTML = `
            <div style="display:flex; justify-content:space-between; align-items:center;">
                <span style="color:#1a1f36; font-weight:600;">${nombre} <small class="grey-text">(${dur} min)</small></span>
                <i class="material-icons blue-text hide check-icon">check_circle</i>
            </div>`;

    div.onclick = function () {
        this.classList.toggle('selected');
        const isSel = this.classList.contains('selected');
        
        // Actualiza el estado semántico
        this.setAttribute('aria-checked', isSel);
        
        // Mejora el contraste visual de la selección
        this.style.background = isSel ? '#eef2ff' : 'white';
        this.style.border = isSel ? '2px solid #114b9a' : '1px solid #ddd';
        this.querySelector('.check-icon').classList.toggle('hide');
    };

        contenedorMultiselect.appendChild(div);
    });

    instance.open();
    setTimeout(() => { 
        const primer = contenedorMultiselect.querySelector('.selectable-item'); 
        if (primer) primer.focus(); 
    }, 250);
}

// Vinculación de eventos
document.getElementById('trigger-añadir')?.addEventListener('click', (e) => {
    e.preventDefault();
    llenarModalSeleccion();
});

document.getElementById('btn-añadir-seleccionados').onclick = function () {
    const seleccionados = document.querySelectorAll('#contenedor-multiselect .selected');
    
    seleccionados.forEach(sel => {
        window.agregarEjercicio(sel.dataset.id, sel.dataset.name, sel.dataset.duration, null);
    });

    if (seleccionados.length > 0) {
        M.toast({ html: 'Ejercicios añadidos' });
        const m = document.getElementById('modal-ejercicios');
        M.Modal.getInstance(m).close();
        document.getElementById('trigger-añadir')?.focus();
    } else {
        M.toast({ html: 'Selecciona algún ejercicio' });
    }
};

    function cargarRutinasGuardadas() {
        if (!listaGuardadas) return;
        fetch('/api/routines', { headers: { 'Accept': 'application/json' } })
            .then(res => res.json())
            .then(rutinas => {
                listaGuardadas.innerHTML = '';
                if (rutinas.length > 0) {
                    rutinas.forEach(r => {
                        const chips = (r.exercises || []).map(e => `<span class="chip small">${e.name}</span>`).join('');
                        const div = document.createElement('div');
                        div.className = 'selectable-item card-panel shadow-none';
                        div.setAttribute('tabindex', '0');
                        div.setAttribute('role', 'checkbox'); // <--- Indispensable
                        div.setAttribute('aria-checked', 'false'); // <--- Estado inicial
                        div.setAttribute('aria-label', `${nombre} duración ${dur} minutos`); // <--- Descripción clara

                        div.innerHTML = `
                        <div style="display:flex; justify-content:space-between; align-items:center;">
                            <span class="card-title"><b>${r.name}</b></span>
                            <button type="button" 
                                aria-label="Eliminar rutina ${r.name}"
                                onclick="eliminarRutina(${r.id})"
                                style="background:transparent; border:none; cursor:pointer;">
                                <i class="ph ph-trash fm-danger-text-contrast" style="font-size: 1.2rem;" aria-hidden="true"></i>
                            </button>
                        </div>
                        <div style="margin-top:5px; font-size:0.8rem">${chips}</div>`;

                        listaGuardadas.appendChild(div);
                    });
                } else {
                    // También aseguramos el gris correcto aquí
                    listaGuardadas.innerHTML = '<p class="center-align grey-text" style="color:#454f5b !important;">No tienes rutinas guardadas.</p>';
                }
            });
    }

    function cargarEjerciciosEnModal() {
        const contenedor = document.getElementById('contenedor-multiselect');
        // Esta es la lista que ya tienes visible en pantalla con los ejercicios
        const listaOrigen = document.getElementById('lista-ejercicios');

        if (!contenedor) return;

        contenedor.innerHTML = ''; // Limpiamos el popup

        // 1. Buscamos los ejercicios que ya existen en el HTML
        // (Ajusta '.collection-item' si tus ejercicios tienen otra clase, pero esa es la estándar de Materialize)
        const ejerciciosEnPantalla = listaOrigen.querySelectorAll('.collection-item');

        if (ejerciciosEnPantalla.length === 0) {
            contenedor.innerHTML = '<p class="center-align grey-text">No se encontraron ejercicios en la lista principal.</p>';
            return;
        }

        // 2. Creamos los checkboxes copiando los datos
        ejerciciosEnPantalla.forEach((item, index) => {
            // Intentamos sacar el nombre. Si tienes un span con clase 'title', lo usamos. Si no, todo el texto.
            let nombre = item.querySelector('.title') ? item.querySelector('.title').innerText : item.innerText;
            nombre = nombre.trim(); // Quitamos espacios sobrantes

            // Intentamos sacar el ID. Si el HTML tiene data-id, perfecto. Si no, usamos el índice.
            const id = item.getAttribute('data-id') || item.getAttribute('id') || index;
            const gif = item.getAttribute('data-gif') || '';

            // Evitamos añadir elementos vacíos o botones de "añadir" si se colaron
            if (nombre.length < 2 || nombre.includes("Añadir")) return;

            const p = document.createElement('p');
            p.style.marginBottom = "10px";
            p.innerHTML = `
            <label>
                <input type="checkbox" class="filled-in" value="${id}" data-name="${nombre}" data-gif="${gif}" />
                <span style="color:#000; font-weight:500;">${nombre}</span>
            </label>
        `;
            contenedor.appendChild(p);
        });
    }

    window.eliminarRutina = function (id) {
        if (!confirm('¿Borrar rutina?')) return;
        const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        fetch(`/api/routines/${id}`, { method: 'DELETE', headers: { 'X-CSRF-TOKEN': token } })
            .then(res => { if (res.ok) { M.toast({ html: 'Borrada' }); cargarRutinasGuardadas(); } });
    };

    // --- NUEVO: Permitir guardar dando Enter en el input del nombre ---
    if (inputNombreRutina) {
        inputNombreRutina.addEventListener('keydown', function (e) {
            if (e.key === 'Enter') {
                e.preventDefault(); // Evita que se envíe formulario si lo hubiera
                btnGuardar.click(); // Simula el clic en el botón guardar
            }
        });
    }

    // ==========================================
    // LOGICA DEL BOTÓN (+) AÑADIR EJERCICIOS
    // ==========================================
    if (linkAñadirRapido) {
        linkAñadirRapido.addEventListener('click', function (e) {
            e.preventDefault(); // Evita comportamientos raros

            // 1. Si el modal está vacío, cargamos los datos
            const contenedor = document.getElementById('contenedor-multiselect');
            if (contenedor && contenedor.children.length === 0) {
                // Verificamos si la función existe antes de llamarla
                if (typeof cargarEjerciciosEnModal === 'function') {
                    cargarEjerciciosEnModal();
                } else {
                    console.error("Falta la función cargarEjerciciosEnModal");
                    contenedor.innerHTML = '<p class="red-text center">Error: Falta función de carga.</p>';
                }
            }

            // 2. Abrimos el modal
            const modalElem = document.getElementById('modal-ejercicios');
            const instance = M.Modal.getInstance(modalElem);
            if (instance) {
                instance.open();
            }
        });
    }

    // ==========================================
    // LÓGICA DEL BOTÓN GUARDAR (MODO DIAGNÓSTICO)
    // ==========================================
    if (btnGuardar) {
        btnGuardar.addEventListener('click', async function (e) {
            e.preventDefault();

            // 1. OBTENER DATOS
            const nombreRutina = inputNombreRutina.value.trim();
            const ejerciciosDOM = listaMia.querySelectorAll('.collection-item');
            const textoOriginal = '<i class="ph ph-floppy-disk" style="margin-right: 5px;" aria-hidden="true"></i> Guardar Rutina';

            // 2. VALIDACIONES
            if (nombreRutina === '') {
                M.toast({ html: '⚠️ Ponle un nombre a tu rutina', classes: 'rounded red' });
                return;
            }
            if (ejerciciosDOM.length === 0) {
                M.toast({ html: '⚠️ La rutina está vacía', classes: 'rounded red' });
                return;
            }

            // 3. PREPARAR ENVÍO
            btnGuardar.innerHTML = '<i class="ph ph-spinner" style="animation: spin 1s linear infinite;"></i> Guardando...';
            btnGuardar.disabled = true;

            try {
                // --- CORRECCIÓN: Usamos 'exercise_id' para que Laravel lo reconozca ---
                const exerciseIds = Array.from(ejerciciosDOM).map(item => ({
                    exercise_id: item.dataset.id || item.getAttribute('data-id')
                }));
                // ---------------------------------------------------------------------

                const token = document.querySelector('meta[name="csrf-token"]')?.content;

                // console.log("Enviando datos:", { name: nombreRutina, exercises: exerciseIds });

                const response = await fetch('/api/routines', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': token
                    },
                    body: JSON.stringify({
                        name: nombreRutina,
                        exercises: exerciseIds
                    })
                });

                if (!response.ok) {
                    const errorText = await response.text();
                    console.error("Respuesta del servidor:", errorText);
                    throw new Error(`Error ${response.status}: No se pudo guardar la rutina.`);
                }

                const data = await response.json();

                // ÉXITO
                M.toast({ html: '✅ ¡Rutina guardada correctamente!', classes: 'rounded green' });

                // Limpiar formulario y lista
                inputNombreRutina.value = '';
                listaMia.innerHTML = `<div class="placeholder-text center-align" style="padding-top: 40px; opacity: 0.6;"><i class="ph ph-hand-grabbing" style="font-size: 30px; display: block; margin-bottom: 10px;"></i> Arrastra ejercicios aquí</div>`;

                // Actualizar otras partes de la interfaz si es necesario
                if (typeof recalcularTiempoTotal === 'function') recalcularTiempoTotal();
                if (typeof cargarRutinasGuardadas === 'function') cargarRutinasGuardadas();

            } catch (error) {
                console.error('Error capturado:', error);
                M.toast({ html: `❌ ${error.message}`, classes: 'rounded red' });
            } finally {
                // Restaurar el botón siempre
                btnGuardar.innerHTML = textoOriginal;
                btnGuardar.disabled = false;
            }
        });
    }

    // ==========================================
    // 6. HELPER MEJORADO: ARRASTRAR TABS SUAVE (PC)
    // ==========================================
    function activarArrastreTabs() {
        const slider = document.querySelector('.tabs');
        if (!slider) return;

        let isDown = false;
        let startX;
        let scrollLeft;
        let haArrastrado = false; // Para diferenciar click de arrastre

        slider.addEventListener('mousedown', (e) => {
            isDown = true;
            haArrastrado = false;
            slider.style.cursor = 'grabbing';
            slider.style.userSelect = 'none'; // Evita que se seleccione el texto azul
            startX = e.pageX - slider.offsetLeft;
            scrollLeft = slider.scrollLeft;
        });

        slider.addEventListener('mouseleave', () => {
            isDown = false;
            slider.style.cursor = 'grab';
            slider.style.userSelect = 'auto';
        });

        slider.addEventListener('mouseup', () => {
            isDown = false;
            slider.style.cursor = 'grab';
            slider.style.userSelect = 'auto';
            // Pequeño truco: quitamos la clase de bloqueo tras soltar
            setTimeout(() => { haArrastrado = false; }, 50);
        });

        slider.addEventListener('mousemove', (e) => {
            if (!isDown) return;
            e.preventDefault();
            const x = e.pageX - slider.offsetLeft;
            const walk = (x - startX) * 1.5; // Velocidad ajustada
            slider.scrollLeft = scrollLeft - walk;

            // Si nos movemos más de 5 pixels, consideramos que es arrastre
            if (Math.abs(x - startX) > 5) {
                haArrastrado = true;
            }
        });

        // BLOQUEO DE CLICKS: Si ha arrastrado, no cambies de tab
        const links = slider.querySelectorAll('a');
        links.forEach(link => {
            link.addEventListener('click', function (e) {
                if (haArrastrado) {
                    e.preventDefault();
                    e.stopImmediatePropagation();
                }
            });
        });
    }

    // ==========================================
    // 7. FUNCIONES GLOBALES FALTANTES (Para los botones generados)
    // ==========================================

    window.eliminarItem = function (btn) {
        const item = btn.closest('.collection-item');
        item.remove();
        recalcularTiempoTotal();
        M.toast({ html: 'Ejercicio eliminado', classes: 'rounded' });

        // Devolver el foco a la lista o al input si está vacía
        const lista = document.getElementById('rutina-personalizada');
        const primerItem = lista.querySelector('.collection-item');
        if (primerItem) primerItem.focus();
        else document.getElementById('routine_name').focus();
    };

    window.moverItem = function (btn, direccion) {
        const item = btn.closest('.collection-item');
        const lista = item.parentElement;

        if (direccion === -1) { // Mover Arriba
            const prev = item.previousElementSibling;
            if (prev) {
                lista.insertBefore(item, prev);
                btn.focus(); // Mantener foco en el botón pulsado
            }
        } else { // Mover Abajo
            const next = item.nextElementSibling;
            if (next) {
                lista.insertBefore(next, item);
                btn.focus(); // Mantener foco en el botón pulsado
            }
        }
    };

    // Pon esto al final de apiEjercicios.js
    document.addEventListener('DOMContentLoaded', function () {
        // 1. Inicializar el modal (asegurarnos de que Materialize lo reconoce)
        const modalElem = document.getElementById('modal-ejercicios');
        M.Modal.init(modalElem);

        // 2. Escuchar cuando se hace clic en el botón "+"
        const btnPlus = document.getElementById('trigger-añadir');

        if (btnPlus) {
            btnPlus.addEventListener('click', function () {
                const contenedorPopup = document.getElementById('contenedor-multiselect');
                if (!contenedorPopup) return;

                // Buscamos todos los ejercicios que ya cargó tu API en la columna izquierda
                // Buscamos los SPAN que tienen el nombre del ejercicio
                const ejerciciosCargados = document.querySelectorAll('#lista-ejercicios .collection-item span[style*="font-weight: 600"]');

                if (ejerciciosCargados.length === 0) {
                    contenedorPopup.innerHTML = '<p class="center grey-text">No hay ejercicios cargados todavía. Espera a que aparezcan en la lista de la izquierda.</p>';
                    return;
                }

                // Limpiamos el popup y volcamos los ejercicios encontrados
                contenedorPopup.innerHTML = '';

                // Usamos un Set para no repetir nombres si salen en varias pestañas
                const nombresVistos = new Set();

                ejerciciosCargados.forEach((span, index) => {
                    const nombre = span.innerText.trim();

                    if (!nombresVistos.has(nombre)) {
                        nombresVistos.add(nombre);

                        const p = document.createElement('p');
                        p.style.marginBottom = "10px";
                        p.innerHTML = `
                        <label>
                            <input type="checkbox" class="filled-in" value="id-${index}" data-name="${nombre}" data-dur="5" />
                            <span style="color: #1a1f36; font-weight: 500;">${nombre}</span>
                        </label>
                    `;
                        contenedorPopup.appendChild(p);
                    }
                });
            });
        }
    });

    // ==========================================
    // 8. INICIALIZACIÓN FINAL
    // ==========================================

    cargarMenuEjercicios();
    cargarRutinasGuardadas();
});