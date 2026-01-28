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
    // 2. SISTEMA DE NAVEGACIÓN TOTAL (FLECHAS)
    // ==========================================

    document.addEventListener('keydown', function(e) {
        const active = document.activeElement;
        
        // --- FLECHA ABAJO (El flujo natural hacia abajo) ---
        if (e.key === 'ArrowDown') {
            
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
                    const primeraSugerencia = listaRecomendados.querySelector('.collection-item');
                    if (primeraSugerencia) primeraSugerencia.focus();
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

                    // CASO MODAL: Lista del pop-up -> Botón Añadir Seleccionados
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

                    // CASO 3: Entre listas de sugerencias
                    else if (parentList.classList.contains('sortable-list') && parentList.parentElement === listaRecomendados) {
                        e.preventDefault();
                        let foundCurrent = false;
                        const allLists = listaRecomendados.querySelectorAll('.sortable-list');
                        for (let list of allLists) {
                            if (foundCurrent) {
                                const primerItem = list.querySelector('.collection-item');
                                if (primerItem) { primerItem.focus(); return; }
                            }
                            if (list === parentList) foundCurrent = true;
                        }
                    }

                    // CASO 4: Columna Derecha Abajo (Guardadas -> Botón Volver)
                    else if (parentList.id === 'lista-rutinas-db') {
                        e.preventDefault();
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

        // --- FLECHA ARRIBA (El flujo inverso) ---
        else if (e.key === 'ArrowUp') {
            
            // A. MODAL: Botón Añadir Seleccionados -> Último item de la lista
            if (active === btnAñadirSeleccionados) {
                e.preventDefault();
                const ultimoItem = contenedorMultiselect.lastElementChild;
                if (ultimoItem) ultimoItem.focus();
                return;
            }

            // B. Desde Botón Volver -> Última Rutina Guardada
            if (active === btnVolver) {
                e.preventDefault();
                const ultimaGuardada = listaGuardadas.lastElementChild;
                if (ultimaGuardada) ultimaGuardada.focus();
                else if (btnGuardar) btnGuardar.focus();
                return;
            }

            // C. Desde Listas Guardadas -> Botón Guardar
            if (active.parentElement === listaGuardadas && !active.previousElementSibling) {
                e.preventDefault();
                btnGuardar.focus();
                return;
            }

            // D. Desde Botón Guardar -> Botón Añadir Rápido
            if (active === btnGuardar) {
                e.preventDefault();
                if (linkAñadirRapido) linkAñadirRapido.focus();
                return;
            }

            // E. Desde Botón Añadir Rápido -> Último ejercicio de Mi Rutina
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
                
                // 1. Si hay hermano anterior
                if (prev && (prev.classList.contains('collection-item') || prev.classList.contains('selectable-item'))) {
                    e.preventDefault();
                    prev.focus();
                } 
                // 2. Si es el PRIMERO de la lista
                else {
                    const parentList = active.parentElement;

                    // Modal (Arriba del todo del modal) -> No hacemos nada o focus al título
                    if (active.classList.contains('selectable-item')) {
                        // Opcional: Podríamos poner el foco en el input de búsqueda del modal si existiera
                        return; 
                    }

                    // Mi Rutina -> Input Nombre
                    if (parentList.id === 'rutina-personalizada') {
                        e.preventDefault();
                        inputNombreRutina.focus();
                    } 
                    // Resultados Búsqueda -> Input Buscador
                    else if (parentList.id === 'resultados-busqueda') {
                        e.preventDefault();
                        buscador.focus();
                    }
                    // Sugerencias -> Input Buscador o último resultado
                    else if (parentList.classList.contains('sortable-list')) {
                        const allLists = Array.from(listaRecomendados.querySelectorAll('.sortable-list'));
                        const myIndex = allLists.indexOf(parentList);

                        if (myIndex === 0) {
                            e.preventDefault();
                            const ultimoResultado = listaResultadosBusqueda.lastElementChild;
                            if (ultimoResultado) ultimoResultado.focus();
                            else buscador.focus();
                        } else if (myIndex > 0) {
                            e.preventDefault();
                            const listaAnterior = allLists[myIndex - 1];
                            const ultimoItemAnterior = listaAnterior.lastElementChild;
                            if (ultimoItemAnterior) ultimoItemAnterior.focus();
                        }
                    }
                }
                return;
            }
        }

        // --- FLECHAS LATERALES (Saltar Columnas) ---
        else if (e.key === 'ArrowRight') {
            // Evitar salirnos si estamos en el MODAL
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
            // Evitar salirnos si estamos en el MODAL
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

        // --- ENTER (Acción global) ---
        else if (e.key === 'Enter') {
            if (active === linkAñadirRapido || active === btnVolver || active === btnAñadirSeleccionados) return; 
            
            if (active.classList.contains('collection-item')) {
                e.preventDefault();
                const actionIcon = active.querySelector('.action-icon');
                if (actionIcon) actionIcon.click();
            }
            if (active.classList.contains('selectable-item')) {
                e.preventDefault();
                active.click();
            }
        }
    });

    // Inputs
    if (buscador) {
        buscador.addEventListener('keydown', (e) => {
            if (e.key === 'ArrowDown') {
                e.preventDefault();
                const primerItem = listaResultadosBusqueda.querySelector('.collection-item');
                if (primerItem) primerItem.focus();
                else {
                    const primSug = listaRecomendados.querySelector('.collection-item');
                    if(primSug) primSug.focus();
                }
            }
        });
    }

    // ==========================================
    // 3. FUNCIONES LÓGICAS (API, RENDER, ETC.)
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

    window.agregarEjercicio = function(id, name, duration, btnElement) {
        const newItem = document.createElement('div');
        newItem.dataset.id = id;
        newItem.dataset.name = name;
        newItem.dataset.duration = duration;
        listaMia.appendChild(newItem);
        
        formatearItemRutina(newItem);
        M.toast({html: `Añadido: ${name}`, classes: 'rounded green lighten-1'});
        
        if(btnElement && btnElement.closest('.collection-item')) {
            btnElement.closest('.collection-item').focus();
        }
    };

    function formatearItemRutina(item) {
        const placeholder = listaMia.querySelector('.placeholder-text');
        if (placeholder) placeholder.remove();

        const nombreEx = item.dataset.name || item.innerText.trim();
        const dur = item.dataset.duration || calcularDuracionEstimada(nombreEx);
        item.dataset.duration = dur;

        item.className = 'collection-item'; 
        item.setAttribute('tabindex', '0');
        item.setAttribute('role', 'button');
        item.setAttribute('aria-label', `Ejercicio ${nombreEx}, enter para borrar`);
        
        item.innerHTML = `
        <div style="padding: 5px; display: flex; justify-content: space-between; align-items: center; width: 100%;">
            <div class="handle" style="cursor: grab; display: flex; align-items: center; gap: 10px;">
                <i class="ph ph-dots-six-vertical grey-text" aria-hidden="true"></i>
                <div>
                    <b>${nombreEx}</b><br>
                    <small class="blue-text">${dur} min</small>
                </div>
            </div>
            <i class="ph ph-trash red-text action-icon" aria-hidden="true" style="cursor:pointer; font-size: 1.3rem;"></i>
        </div>`;

        const btnDelete = item.querySelector('.action-icon');
        btnDelete.onclick = function() {
            const nextFocus = item.nextElementSibling || item.previousElementSibling || inputNombreRutina;
            item.remove();
            recalcularTiempoTotal();
            if (listaMia.querySelectorAll('.collection-item').length === 0) {
                listaMia.innerHTML = '<p class="center-align placeholder-text" style="padding-top: 50px;">Usa el buscador o añade ejercicios.</p>';
            }
            if (nextFocus) nextFocus.focus();
        };
        recalcularTiempoTotal();
    }

    // ==========================================
    // 4. CARGA DE DATOS (API)
    // ==========================================

    function cargarMenuEjercicios() {
        fetch('/api/routines/recommendations', { headers: { 'Accept': 'application/json' } })
            .then(res => res.json())
            .then(data => {
                if (listaRecomendados && data.rutinas) {
                    listaRecomendados.innerHTML = '';
                    data.rutinas.forEach((rutina, idx) => {
                        let html = `<h6 class="blue-text" style="margin-top:25px"><b>${rutina.nombre}</b></h6>
                                <div class="collection sortable-list" id="rutina-sug-${idx}" role="list">`;
                        rutina.ejercicios.forEach(ex => {
                            const dur = calcularDuracionEstimada(ex.name);
                            html += `
                            <div class="collection-item" tabindex="0" role="button" aria-label="Añadir ${ex.name}"
                                 data-id="${ex.wger_id || ex.id}" data-name="${ex.name}" data-duration="${dur}">
                                <span class="handle"><i class="ph ph-dots-six-vertical"></i> ${ex.name}</span>
                                <div class="secondary-content">
                                    <span class="badge grey lighten-3">${dur} min</span>
                                    <i class="ph ph-plus-circle blue-text action-icon" 
                                       onclick="agregarEjercicio('${ex.wger_id || ex.id}', '${ex.name}', '${dur}', this)"></i>
                                </div>
                            </div>`;
                        });
                        html += `</div>`;
                        listaRecomendados.innerHTML += html;
                    });
<<<<<<< HEAD
                    document.querySelectorAll('.sortable-list').forEach(el => new Sortable(el, { group: { name: 'fit', pull: 'clone', put: false }, animation: 150, handle: '.handle' }));
                }

=======

                    document.querySelectorAll('.sortable-list').forEach(el => {
                        new Sortable(el, { group: { name: 'fit', pull: 'clone', put: false }, animation: 150, handle: '.handle' });
                    });
                }

                // Lógica del Buscador
>>>>>>> 5b73b3423500c534d27e9a0c1dbff7c24628e996
                if (buscador && data.todos) {
                    const renderBusqueda = (ejercicios) => {
                        listaResultadosBusqueda.innerHTML = '';
                        ejercicios.forEach(ex => {
                            const dur = calcularDuracionEstimada(ex.name);
                            listaResultadosBusqueda.innerHTML += `
<<<<<<< HEAD
                            <div class="collection-item" tabindex="0" role="button" aria-label="Añadir ${ex.name}"
                                 data-id="${ex.wger_id || ex.id}" data-name="${ex.name}" data-duration="${dur}">
                                <span class="handle"><i class="ph ph-dots-six-vertical"></i> <b>${ex.name}</b></span>
                                <i class="ph ph-plus right blue-text action-icon" 
                                   onclick="agregarEjercicio('${ex.wger_id || ex.id}', '${ex.name}', '${dur}', this)"></i>
                            </div>`;
                        });
                        new Sortable(listaResultadosBusqueda, { group: { name: 'fit', pull: 'clone', put: false }, animation: 150, handle: '.handle' });
                    };
=======
                        <div class="collection-item" data-id="${ex.wger_id || ex.id}" data-name="${ex.name}" data-duration="${dur}">
                            <span class="handle"><i class="ph ph-dots-six-vertical"></i> <b>${ex.name}</b></span>
                            <i class="ph ph-plus right blue-text" tabindex = 0></i>
                        </div>`;
                        });
                        new Sortable(listaResultadosBusqueda, { group: { name: 'fit', pull: 'clone', put: false }, animation: 150, handle: '.handle' });
                    };

>>>>>>> 5b73b3423500c534d27e9a0c1dbff7c24628e996
                    renderBusqueda(data.todos.slice(0, 5));
                    buscador.addEventListener('input', (e) => {
                        const term = e.target.value.toLowerCase();
                        const filtrados = data.todos.filter(ex => ex.name.toLowerCase().includes(term));
                        renderBusqueda(filtrados.slice(0, 15));
                    });
                }
            });
    }

<<<<<<< HEAD
=======
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

>>>>>>> 5b73b3423500c534d27e9a0c1dbff7c24628e996
    function llenarModalSeleccion() {
        contenedorMultiselect.innerHTML = '';
        const ejercicios = document.querySelectorAll('#lista-ejercicios [data-id], #resultados-busqueda [data-id]');
        const unicos = new Set();
<<<<<<< HEAD
=======

>>>>>>> 5b73b3423500c534d27e9a0c1dbff7c24628e996
        ejercicios.forEach(ej => {
            const id = ej.dataset.id;
            const nombre = ej.dataset.name;
            const dur = ej.dataset.duration;
<<<<<<< HEAD
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
                div.onclick = function() {
                    div.classList.toggle('selected');
                    const isSel = div.classList.contains('selected');
                    div.style.background = isSel ? '#e3f2fd' : 'white';
                    div.querySelector('.check-icon').classList.toggle('hide');
                    div.setAttribute('aria-checked', isSel);
=======

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
>>>>>>> 5b73b3423500c534d27e9a0c1dbff7c24628e996
                };
                contenedorMultiselect.appendChild(div);
            }
        });
<<<<<<< HEAD
        
        // Poner foco en el primer elemento al abrir
        setTimeout(() => {
            const primer = contenedorMultiselect.querySelector('.selectable-item');
            if(primer) primer.focus();
        }, 300);
    }

    document.addEventListener('click', (e) => { if (e.target.closest('.modal-trigger')) llenarModalSeleccion(); });
    
    if (btnAñadirSeleccionados) {
        btnAñadirSeleccionados.onclick = function () {
            contenedorMultiselect.querySelectorAll('.selected').forEach(sel => 
                agregarEjercicio(sel.dataset.id, sel.dataset.name, sel.dataset.duration, null));
            M.toast({ html: 'Añadidos' });
            
            // Cerrar modal y devolver foco al botón que lo abrió
            const modalInstance = M.Modal.getInstance(document.querySelector('.modal'));
            if(modalInstance) modalInstance.close();
            if(linkAñadirRapido) linkAñadirRapido.focus();
        };
    }

    if (listaMia) {
        new Sortable(listaMia, { group: 'fit', animation: 150, handle: '.handle', onAdd: (evt) => formatearItemRutina(evt.item) });
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
            .then(res => { if(res.ok) { M.toast({html: 'Borrada'}); cargarRutinasGuardadas(); }});
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
            }).then(res => { if(res.ok) { 
                M.toast({ html: 'Guardada' }); 
                listaMia.innerHTML = '<p class="center-align placeholder-text" style="padding-top: 50px;">Rutina guardada.</p>';
                inputNombreRutina.value = '';
                recalcularTiempoTotal();
                cargarRutinasGuardadas();
            }});
        };
    }

=======
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
>>>>>>> 5b73b3423500c534d27e9a0c1dbff7c24628e996
    cargarMenuEjercicios();
    cargarRutinasGuardadas();
});