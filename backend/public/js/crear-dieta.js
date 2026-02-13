// =========================================================
// CREAR-DIETA.JS (Corregido: Contador + Accesibilidad)
// =========================================================

const urlParams = new URLSearchParams(window.location.search);
const diaSeleccionado = urlParams.get('day');
console.log("Editando el día:", diaSeleccionado);

if (typeof window.productosSeleccionados === 'undefined') {
    window.productosSeleccionados = [];
}

document.addEventListener('DOMContentLoaded', () => {
    // 1. Configurar Día (CON ARREGLO DE TILDES/MAYÚSCULAS)
    const params = new URLSearchParams(window.location.search);
    const diaUrl = params.get('day') || params.get('dia') || 'Lunes';
    const selectDia = document.getElementById('select-dia');

    if (selectDia) {
        // --- INICIO: NORMALIZADOR INTELIGENTE ---
        const limpiarTexto = (t) => t.toLowerCase().normalize("NFD").replace(/[\u0300-\u036f]/g, "").trim();
        const diaBuscado = limpiarTexto(diaUrl);

        let valorOficial = 'lunes';

        // Buscamos coincidencia flexible
        Array.from(selectDia.options).forEach(opcion => {
            if (limpiarTexto(opcion.value) === diaBuscado || limpiarTexto(opcion.text) === diaBuscado) {
                valorOficial = opcion.value;
                selectDia.value = valorOficial;
            }
        });
        // --- FIN: NORMALIZADOR ---

        cargarDatosDelDia(selectDia.value);

        // Listener para cambio manual
        selectDia.addEventListener('change', (e) => cargarDatosDelDia(e.target.value));
    }

    // 2. Desbloquear Botón Terminar
    const btnFinalizarOriginal = document.getElementById('btn-finalizar');
    if (btnFinalizarOriginal) {
        const btnNuevo = btnFinalizarOriginal.cloneNode(true);
        btnFinalizarOriginal.parentNode.replaceChild(btnNuevo, btnFinalizarOriginal);
        btnNuevo.addEventListener('click', guardarYSalir);
    }

    // 3. Activar el detector de clicks
    activarDetectorDeClicks();

    // 4. Configurar búsqueda
    const searchInput = document.getElementById('laravel-search');
    if (searchInput) {
        searchInput.addEventListener('input', (e) => realizarBusqueda(e.target.value));
    }
});

// LÓGICA DE BÚSQUEDA
async function realizarBusqueda(query) {
    const resultsDiv = document.getElementById('laravel-results');
    if (!resultsDiv) return;
    if (query.length < 3) { resultsDiv.innerHTML = ''; return; }

    try {
        const response = await fetch(`/api/products/search?query=${query}`);
        if (!response.ok) throw new Error("Error red");
        const products = await response.json();

        resultsDiv.innerHTML = '';
        products.forEach(p => {
            const div = document.createElement('div');
            // Añadimos role="listitem" para accesibilidad
            div.setAttribute('role', 'listitem');
            div.style.cssText = 'border-bottom:1px solid #eee; padding:10px; display:flex; justify-content:space-between; align-items:center;';
            div.innerHTML = `
                <div><strong>${p.name}</strong><br><small style="color:#4B5563">${p.kcal} kcal</small></div>
                <button class="btn-add-item" type="button" aria-label="Añadir ${p.name}"
                        style="background:#F3F4F6; color:#007AFF; border:none; border-radius:50%; width:40px; height:40px; cursor:pointer; display:flex; align-items:center; justify-content:center;">
                    <i class="ph-bold ph-plus" aria-hidden="true"></i>
                </button>
            `;
            resultsDiv.appendChild(div);
        });
    } catch (err) { console.error(err); }
}

// DETECTOR DE CLICKS
function activarDetectorDeClicks() {
    document.body.addEventListener('click', function (e) {
        // A. BORRAR ITEM
        const btnBorrar = e.target.closest('.btn-borrar-item');
        if (btnBorrar) {
            const index = btnBorrar.getAttribute('data-index');
            window.productosSeleccionados.splice(index, 1);
            renderizarCesta();
            return;
        }

        // B. AÑADIR ITEM
        const btnAdd = e.target.closest('.btn-add-item') || e.target.closest('button.add-btn');
        if (btnAdd) {
            e.preventDefault(); e.stopPropagation();

            const contenedor = btnAdd.parentElement;
            const texto = contenedor.innerText || contenedor.textContent;

            let kcal = 0;
            const matchKcal = texto.match(/(\d+(?:\.\d+)?)\s*(?:k|c|kcal)/i);
            if (matchKcal) kcal = parseFloat(matchKcal[1]);
            else {
                const matchNumber = texto.match(/(\d+)/g);
                if (matchNumber) kcal = parseFloat(matchNumber[matchNumber.length - 1]);
            }

            let nombre = texto.split('\n')[0].replace(/\d+\s*(kcal|k|cal)/gi, '').replace(/[()]/g, '').trim();
            if (!nombre) nombre = "Producto";

            window.productosSeleccionados.push({ id: Date.now(), name: nombre, kcal: kcal });
            renderizarCesta();
        }
    });
}

// --- FUNCIÓN CORREGIDA (Aquí estaba el fallo del contador) ---
function renderizarCesta() {
    const lista = document.getElementById('lista-cesta');
    const totalTxt = document.getElementById('kcal-total');
    const barra = document.getElementById('resumen-fijo');
    const badgeCount = document.getElementById('item-count'); // <--- NUEVO

    let suma = 0;
    const html = window.productosSeleccionados.map((p, i) => {
        const val = parseFloat(p.kcal) || 0;
        suma += val;

        return `<div class="item-cesta" role="listitem">
            <div>
                <strong style="display:block; color:#1D1D1F;">${p.name}</strong>
                <span style="color:#007AFF; font-size:0.9rem; font-weight:600;">${val.toFixed(0)} kcal</span>
            </div>
            <button type="button" class="btn-borrar-item" data-index="${i}" aria-label="Borrar ${p.name}">
                <i class="ph-fill ph-trash" style="font-size:1.2rem;" aria-hidden="true"></i>
            </button>
        </div>`;
    }).join('');

    // 1. Renderizar lista HTML
    if (lista) lista.innerHTML = html || '<div class="empty-state"><div class="icon-circle"><i class="ph-fill ph-basket"></i></div><p>Tu cesta está vacía</p></div>';

    // 2. Actualizar Kcal Totales
    if (totalTxt) totalTxt.innerText = Math.round(suma);

    // 3. Actualizar la Barra Flotante
    if (barra) barra.style.display = window.productosSeleccionados.length > 0 ? 'block' : 'none';

    // 4. ¡LA CORRECCIÓN! Actualizar el contador azul
    if (badgeCount) {
        const cantidad = window.productosSeleccionados.length;
        badgeCount.innerText = cantidad;
        // Accesibilidad: Actualizar etiqueta para lectores de pantalla
        badgeCount.setAttribute('aria-label', `${cantidad} elementos en la lista`);
    }
}

function cargarDatosDelDia(diaSeleccionado) {
    const diaRaw = diaSeleccionado;
    const diaLimpio = diaSeleccionado.toLowerCase().normalize("NFD").replace(/[\u0300-\u036f]/g, "").trim();

    const variantes = [
        `dieta_${diaLimpio}`,
        `dieta_${diaRaw}`,
        `dieta_${diaSeleccionado.toLowerCase()}`
    ];

    let productosEncontrados = [];
    let origenDatos = 'ninguno';

    for (const key of variantes) {
        const raw = localStorage.getItem(key);
        if (raw) {
            try {
                const data = JSON.parse(raw);
                if (data.items && data.items.length > 0) {
                    productosEncontrados = data.items;
                    origenDatos = key;
                    break;
                }
            } catch (e) { console.error("Error leyendo", key); }
        }
    }

    console.log(`Cargando día: ${diaSeleccionado} | Fuente: ${origenDatos}`);
    window.productosSeleccionados = productosEncontrados;
    renderizarCesta();
}

async function guardarYSalir() {
    const selectDia = document.getElementById('select-dia');
    let diaRaw = selectDia.value;
    const diaOficial = diaRaw.toLowerCase().normalize("NFD").replace(/[\u0300-\u036f]/g, "").trim();

    const total = window.productosSeleccionados.reduce((acc, item) => acc + (parseFloat(item.kcal) || 0), 0);
    const nombres = window.productosSeleccionados.map(p => p.name);
    const resumen = nombres.slice(0, 3).join(', ') + (nombres.length > 3 ? '...' : '');

    try {
        const xsrfToken = getCookie('XSRF-TOKEN');
        const response = await fetch('/api/weekly-plans/save-day', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-XSRF-TOKEN': xsrfToken
            },
            body: JSON.stringify({
                day: diaRaw,
                calories: Math.round(total),
                summary: resumen || "Dieta personalizada"
            })
        });

        if (response.ok) {
            localStorage.setItem(`dieta_${diaOficial}`, JSON.stringify({
                items: window.productosSeleccionados,
                total: total,
                completado: true
            }));

            if (`dieta_${diaRaw}` !== `dieta_${diaOficial}`) {
                localStorage.removeItem(`dieta_${diaRaw}`);
            }

            if (diaRaw !== 'Sabado' && diaRaw !== 'sabado') localStorage.removeItem('dieta_Sábado');

            window.location.href = '/nutrition';
        } else {
            alert("Error al guardar en servidor.");
        }
    } catch (error) {
        console.error(error);
        alert("Error de conexión.");
    }
}

function getCookie(name) {
    let match = document.cookie.match(new RegExp('(^| )' + name + '=([^;]+)'));
    if (match) return decodeURIComponent(match[2]);
    return null;
}