// =========================================================
// CREAR-DIETA.JS (Iconos de basura + Suma arreglada)
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
        // Esta función convierte "Sábado" -> "sabado" para compararlos
        const limpiarTexto = (t) => t.toLowerCase().normalize("NFD").replace(/[\u0300-\u036f]/g, "").trim();
        const diaBuscado = limpiarTexto(diaUrl); 

        let valorOficial = 'lunes'; // Valor por defecto
        
        // Buscamos en el select la opción que coincida (ignorando tildes y mayúsculas)
        Array.from(selectDia.options).forEach(opcion => {
            if (limpiarTexto(opcion.value) === diaBuscado || limpiarTexto(opcion.text) === diaBuscado) {
                valorOficial = opcion.value; // ¡Encontrado! Usamos el valor real del HTML (ej: "sabado")
                selectDia.value = valorOficial; // Marcamos la opción visualmente
            }
        });
        // --- FIN: NORMALIZADOR ---

        // Cargamos los datos usando el valor OFICIAL (ej: "sabado")
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

    // 3. Activar el detector inteligente de clicks
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
            div.style.cssText = 'border-bottom:1px solid #eee; padding:10px; display:flex; justify-content:space-between; align-items:center;';
            div.innerHTML = `
                <div><strong>${p.name}</strong><br><small>${p.kcal} kcal</small></div>
                <button class="btn-add-item" type="button" 
                        style="background:#2ecc71; color:white; border:none; border-radius:50%; width:30px; height:30px; cursor:pointer; display:flex; align-items:center; justify-content:center;">
                    <i class="ph ph-plus"></i>
                </button>
            `;
            resultsDiv.appendChild(div);
        });
    } catch (err) { console.error(err); }
}

// DETECTOR DE CLICKS
function activarDetectorDeClicks() {
    document.body.addEventListener('click', function(e) {
        // A. BORRAR ITEM (¡Aquí está tu icono de basura!)
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
            if(!nombre) nombre = "Producto";

            window.productosSeleccionados.push({ id: Date.now(), name: nombre, kcal: kcal });
            renderizarCesta();
        }
    });
}

function renderizarCesta() {
    const lista = document.getElementById('lista-cesta');
    const totalTxt = document.getElementById('kcal-total');
    const barra = document.getElementById('resumen-fijo');
    
    let suma = 0;
    const html = window.productosSeleccionados.map((p, i) => {
        const val = parseFloat(p.kcal) || 0;
        suma += val;
        
        // AQUÍ ESTÁ EL CAMBIO: Icono ph-trash en rojo
        return `<div class="item-cesta" style="display:flex; justify-content:space-between; align-items:center; padding:12px; border-bottom:1px solid #eee;">
            <div>
                <strong style="display:block; color:#333;">${p.name}</strong>
                <span style="color:#2ecc71; font-size:0.9rem;">${val.toFixed(0)} kcal</span>
            </div>
            <button type="button" class="btn-borrar-item" data-index="${i}" 
                    style="color:#e74c3c; border:none; background:none; cursor:pointer; padding:8px;">
                <i class="ph ph-trash" style="font-size:1.4rem;"></i>
            </button>
        </div>`;
    }).join('');

    if (lista) lista.innerHTML = html || '<div style="padding:20px; text-align:center; color:#888;">Lista vacía</div>';
    if (totalTxt) totalTxt.innerText = Math.round(suma);
    if (barra) barra.style.display = window.productosSeleccionados.length > 0 ? 'block' : 'none';
}

function cargarDatosDelDia(diaSeleccionado) {
    // 1. Definir versiones del nombre
    // EJEMPLO: Si entras en "Martes"...
    const diaRaw = diaSeleccionado; // "Martes"
    const diaLimpio = diaSeleccionado.toLowerCase()
                                     .normalize("NFD")
                                     .replace(/[\u0300-\u036f]/g, "")
                                     .trim(); // "martes"

    // 2. Definir Prioridad: PRIMERO buscamos la llave limpia ('dieta_martes')
    // Si esa existe, es la más reciente y CORRECTA. Ignoramos 'dieta_Martes'.
    const variantes = [
        `dieta_${diaLimpio}`, // Prioridad 1: dieta_martes
        `dieta_${diaRaw}`,    // Prioridad 2: dieta_Martes (Solo si no existe la 1)
        `dieta_${diaSeleccionado.toLowerCase()}` // Por si acaso: dieta_martes (con tilde si aplica)
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
                    // IMPORTANTE: Si encontramos datos en la prioritaria, PARAMOS.
                    // Así evitamos cargar la versión vieja "sucia".
                    break; 
                }
            } catch (e) {
                console.error("Error leyendo", key);
            }
        }
    }

    console.log(`Cargando día: ${diaSeleccionado} | Fuente: ${origenDatos}`);

    window.productosSeleccionados = productosEncontrados;
    renderizarCesta();
}
async function guardarYSalir() {
    const selectDia = document.getElementById('select-dia');
    let diaRaw = selectDia.value; // Ej: "Martes"
    
    // Limpiamos el nombre para usarlo como llave oficial
    const diaOficial = diaRaw.toLowerCase().normalize("NFD").replace(/[\u0300-\u036f]/g, "").trim(); // "martes"

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
            // 1. Guardar en la llave LIMPIA (el futuro)
            localStorage.setItem(`dieta_${diaOficial}`, JSON.stringify({ 
                items: window.productosSeleccionados, 
                total: total,
                completado: true 
            }));
            
            // 2. BORRAR la llave SUCIA (el pasado)
            // Si la llave vieja era "dieta_Martes" y es distinta a "dieta_martes", la borramos.
            if (`dieta_${diaRaw}` !== `dieta_${diaOficial}`) {
                console.log(`Borrando datos fantasma en: dieta_${diaRaw}`);
                localStorage.removeItem(`dieta_${diaRaw}`);
            }

            // Opcional: Borrar otras variantes comunes por si acaso
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