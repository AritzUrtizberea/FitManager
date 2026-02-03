// Variable global para mantener los datos del producto
let currentProductData = {}; 

document.addEventListener('DOMContentLoaded', () => {
    // 1. Cargar datos del LocalStorage
    cargarDatosProducto();

    // 2. Asignar el evento al botón (CORREGIDO)
    // Usamos getElementById porque en tu HTML el botón tiene id="btn-add"
    const btnGuardar = document.getElementById('btn-add');
    
    if (btnGuardar) {
        btnGuardar.addEventListener('click', guardarProducto);
    } else {
        console.error("Error: No se encontró el botón de guardar (#btn-add)");
    }
    
    // Configuración del video modal (lo he movido aquí para tener un solo DOMContentLoaded)
    setupVideoModal(); 
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
                document.getElementById('img-placeholder').style.display = 'block';
            }
        }

        // Nutriscore y Nova
        updateBadges(data);

        // Macros
        setMacro('prod-kcal', nut['energy-kcal_100g'], 0);
        setMacro('prod-prot', nut.proteins_100g, 1);
        setMacro('prod-carb', nut.carbohydrates_100g, 1);
        setMacro('prod-gras', nut.fat_100g, 1);
        setMacro('prod-azucar', nut.sugars_100g, 1);
        setMacro('prod-fibra', nut.fiber_100g, 1);
        setMacro('prod-sal', nut.salt_100g, 2);

    } else {
        alert("No hay producto seleccionado");
        window.location.href = '/nutrition'; // Asegúrate de la barra al inicio
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
        
        // Limpiamos clases viejas y ponemos la nueva
        badge.className = 'badge-score'; 
        badge.classList.add(`bg-${score}`); 
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

// Función para obtener cookie XSRF de Laravel
function getCookie(name) {
    let value = "; " + document.cookie;
    let parts = value.split("; " + name + "=");
    if (parts.length === 2) return parts.pop().split(";").shift();
}

// --- FUNCIÓN DE GUARDADO (LA IMPORTANTE) ---
async function guardarProducto() {
    // CORREGIDO: Buscamos por ID
    const btn = document.getElementById('btn-add'); 
    const originalText = btn.innerHTML;
    
    // Feedback visual
    btn.innerHTML = '<i class="ph ph-spinner ph-spin"></i> Guardando...';
    btn.disabled = true;

    // Payload
    const nut = currentProductData.nutriments || {};
    const payload = {
        name: currentProductData.product_name || 'Sin Nombre',
        barcode: currentProductData.code || null,
        image_url: currentProductData.image_url || '',
        kcal: nut['energy-kcal_100g'] || 0,
        proteins: nut.proteins_100g || 0,
        carbs: nut.carbohydrates_100g || 0,
        fats: nut.fat_100g || 0,
        category_id: 1 // OJO: Asegúrate de que existe la categoría ID 1 en tu DB
    };

    try {
        // Obtenemos el token. Si es null (primera carga), a veces Laravel falla.
        const token = getCookie('XSRF-TOKEN');
        
        const response = await fetch('/api/products', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-XSRF-TOKEN': token ? decodeURIComponent(token) : ''
            },
            body: JSON.stringify(payload)
        });

        if (response.ok) {
            btn.style.background = '#4cd137'; // Verde éxito
            btn.innerHTML = '<i class="ph ph-check"></i> ¡Guardado!';
            
            setTimeout(() => { 
                window.location.href = '/nutrition'; 
            }, 1500); 
        } else {
            console.log("Error Status:", response.status);
            const errorData = await response.json();
            console.log("Error Detalles:", errorData); // Mira la consola si falla
            throw new Error('Error en servidor');
        }
    } catch (error) {
        console.error(error);
        btn.style.background = '#e84118'; // Rojo error
        btn.innerHTML = '<i class="ph ph-warning"></i> Error';
        
        setTimeout(() => {
            btn.disabled = false;
            btn.innerHTML = originalText;
            btn.style.background = ''; // Vuelve al color del CSS
        }, 2000);
    }
}

// Lógica del Modal de Video (Separada en función limpia)
function setupVideoModal() {
    const triggerBtn = document.getElementById('video-trigger-btn');
    const closeBtn = document.getElementById('close-modal-btn');
    const modal = document.getElementById('video-modal');
    const video = document.getElementById('popup-video');

    if(triggerBtn && modal) {
        triggerBtn.addEventListener('click', () => {
            modal.classList.add('open');
            if(video) video.play(); 
        });

        const closeModal = () => {
            modal.classList.remove('open');
            if(video) {
                video.pause();
                video.currentTime = 0;
            }
        };

        closeBtn.addEventListener('click', closeModal);
        modal.addEventListener('click', (e) => {
            if (e.target === modal) closeModal();
        });
    }
}