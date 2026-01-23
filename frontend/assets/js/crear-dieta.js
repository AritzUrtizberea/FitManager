// =========================================================
// CREAR-DIETA.JS (Iconos de basura + Suma arreglada)
// =========================================================

if (typeof window.productosSeleccionados === 'undefined') {
    window.productosSeleccionados = [];
}

document.addEventListener('DOMContentLoaded', () => {
    // 1. Configurar Día
    const params = new URLSearchParams(window.location.search);
    const diaUrl = params.get('dia') || 'lunes';
    const selectDia = document.getElementById('select-dia');
    
    if (selectDia) {
        selectDia.value = diaUrl;
        cargarDatosDelDia(diaUrl);
        selectDia.addEventListener('change', (e) => cargarDatosDelDia(e.target.value));
    }

    // 2. Desbloquear Botón Terminar
    const btnFinalizarOriginal = document.getElementById('btn-finalizar');
    if (btnFinalizarOriginal) {
        const btnNuevo = btnFinalizarOriginal.cloneNode(true);
        btnFinalizarOriginal.parentNode.replaceChild(btnNuevo, btnFinalizarOriginal);
        btnNuevo.addEventListener('click', guardarYSalir);
    }

    // 3. Activar el detector inteligente
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

function cargarDatosDelDia(dia) {
    const raw = localStorage.getItem(`dieta_${dia}`);
    if (raw) {
        window.productosSeleccionados = JSON.parse(raw).items || [];
    } else {
        window.productosSeleccionados = [];
    }
    renderizarCesta();
}

function guardarYSalir() {
    const dia = document.getElementById('select-dia').value;
    const total = window.productosSeleccionados.reduce((acc, item) => acc + (parseFloat(item.kcal) || 0), 0);
    
    localStorage.setItem(`dieta_${dia}`, JSON.stringify({ 
        items: window.productosSeleccionados, 
        total: total,
        completado: true 
    }));
    
    window.location.href = 'nutricion.html';
}