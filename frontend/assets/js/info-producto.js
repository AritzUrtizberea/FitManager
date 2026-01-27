// Variable global para mantener los datos del producto
let currentProductData = {}; 

document.addEventListener('DOMContentLoaded', () => {
    // 1. Cargar datos del LocalStorage
    cargarDatosProducto();

    // 2. Asignar el evento al botón (Programación No Obstructiva)
    // Buscamos el botón por su clase y le decimos "escucha el click"
    const btnGuardar = document.querySelector('.btn-add');
    if (btnGuardar) {
        btnGuardar.addEventListener('click', guardarProducto);
    }
});

function cargarDatosProducto() {
    const rawData = localStorage.getItem('producto_actual');
    
    if (rawData) {
        const data = JSON.parse(rawData);
        currentProductData = data; 
        const nut = data.nutriments || {};

        // Rellenar Info Básica
        const nombreEl = document.getElementById('prod-nombre');
        if(nombreEl) nombreEl.textContent = data.product_name || 'Producto sin nombre';
        
        const marcaEl = document.getElementById('prod-marca');
        if(marcaEl) marcaEl.textContent = data.brands || 'Marca desconocida';
        
        // Imagen
        const imgEl = document.getElementById('prod-img');
        if (imgEl) {
            if(data.image_url) {
                imgEl.src = data.image_url;
            } else {
                imgEl.style.display = 'none';
                // Fallback visual si quieres agregarlo
            }
        }

        // Nutriscore y Nova
        updateBadges(data);

        // Macros (usamos una función auxiliar para no repetir document.getElementById)
        setMacro('prod-kcal', nut['energy-kcal_100g'], 0);
        setMacro('prod-prot', nut.proteins_100g, 1);
        setMacro('prod-carb', nut.carbohydrates_100g, 1);
        setMacro('prod-gras', nut.fat_100g, 1);
        setMacro('prod-azucar', nut.sugars_100g, 1);
        setMacro('prod-fibra', nut.fiber_100g, 1);
        setMacro('prod-sal', nut.salt_100g, 2);

    } else {
        alert("No hay producto seleccionado");
        window.location.href = 'nutrition';
    }
}

// Helpers para limpiar código
function setMacro(id, value, decimals) {
    const el = document.getElementById(id);
    if(el) el.textContent = (value || 0).toFixed(decimals);
}

function updateBadges(data) {
    const score = data.nutriscore_grade ? data.nutriscore_grade.toLowerCase() : '';
    const badge = document.getElementById('nutriscore-badge');
    
    if(badge && ['a','b','c','d','e'].includes(score)){
        badge.textContent = 'Nutri-Score ' + score.toUpperCase();
        badge.className = `badge-score bg-${score}`; // Reemplaza clases limpiamente
    } else if (badge) {
        badge.style.display = 'none';
    }

    const nova = data.nova_group;
    const novaBadge = document.getElementById('nova-badge');
    if(novaBadge && nova) {
        novaBadge.textContent = 'NOVA ' + nova;
    } else if (novaBadge) {
        novaBadge.style.display = 'none';
    }
}

// 1. FUNCIÓN AUXILIAR (Pégala arriba del todo o antes de guardarProducto)
// Sirve para recuperar el token de seguridad que Laravel guarda en las cookies
function getCookie(name) {
    let value = "; " + document.cookie;
    let parts = value.split("; " + name + "=");
    if (parts.length === 2) return parts.pop().split(";").shift();
}

// 2. TU FUNCIÓN CORREGIDA
async function guardarProducto() {
    const btn = document.querySelector('.btn-add');
    const originalText = btn.innerHTML;
    
    // Feedback visual
    btn.innerHTML = '<i class="ph ph-spinner ph-spin"></i> Guardando...';
    btn.disabled = true;

    // Payload limpio para tu Backend
    const nut = currentProductData.nutriments || {};
    const payload = {
        name: currentProductData.product_name || 'Sin Nombre',
        barcode: currentProductData.code || null,
        image_url: currentProductData.image_url || '',
        kcal: nut['energy-kcal_100g'] || 0,
        proteins: nut.proteins_100g || 0,
        carbs: nut.carbohydrates_100g || 0,
        fats: nut.fat_100g || 0,
        category_id: 1 
    };

    try {
        const response = await fetch('/api/products', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                // --- CAMBIO IMPORTANTE AQUÍ (Soluciona el error 419) ---
                'X-XSRF-TOKEN': decodeURIComponent(getCookie('XSRF-TOKEN'))
            },
            body: JSON.stringify(payload)
        });

        if (response.ok) {
            btn.style.background = '#4cd137';
            btn.innerHTML = '<i class="ph ph-check"></i> ¡Guardado!';
            
            // He añadido la barra / para asegurar que vuelve a la raíz correcta
            setTimeout(() => { window.location.href = '/nutrition'; }, 1500); 
        } else {
            // Si el servidor devuelve error (ej. 422 o 500)
            console.log("Status:", response.status);
            throw new Error('Error en servidor');
        }
    } catch (error) {
        console.error(error);
        btn.style.background = '#e84118';
        btn.innerHTML = '<i class="ph ph-warning"></i> Error';
        setTimeout(() => {
            btn.disabled = false;
            btn.innerHTML = originalText;
            btn.style.background = 'linear-gradient(90deg, #7ab346 0%, #5d8f33 100%)';
        }, 2000);
    }
}