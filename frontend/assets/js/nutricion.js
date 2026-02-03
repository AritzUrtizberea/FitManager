// ==========================================
// NUTRICION.JS (Versión PRO Visual + Accesible)
// ==========================================

document.addEventListener('DOMContentLoaded', () => {
    // 1. Iniciamos los botones LO PRIMERO (para que siempre funcionen)
    initScannerLogic();      
    initNavigationButtons();
    
    // 2. Luego la lógica de la semana
    initWeeklyPlan();
    
    // 3. Al final las animaciones (si fallan, no rompen los botones)
    try {
        anime({
            targets: '.status-card',
            opacity: [0, 1],
            translateY: [20, 0],
            delay: anime.stagger(100),
            easing: 'easeOutQuad',
            duration: 800
        });
    } catch (e) {
        console.warn("Anime.js no cargó, pero la app sigue funcionando.");
        // Si falla la animación, hacemos visibles las cartas manualmente
        document.querySelectorAll('.status-card').forEach(el => el.style.opacity = 1);
    }
});

// ==========================================
// NUEVA LÓGICA CONECTADA A BASE DE DATOS
// ==========================================

async function initWeeklyPlan() {
    try {
        // 1. Detectamos automáticamente la IP/Dominio actual
        // Si entras por 10.10.18.181, esto valdrá "http://10.10.18.181"
        // Si entras por localhost, esto valdrá "http://localhost"
        const baseUrl = window.location.origin;

        // 2. Construimos la URL usando esa variable
        // IMPORTANTE: Añadimos credentials: 'include' para que funcione Auth::user()
        const response = await fetch(`${baseUrl}/api/weekly-plans`, {
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        });
        
        if (!response.ok) {
            if(response.status === 401) throw new Error("No has iniciado sesión.");
            throw new Error(`Error del servidor: ${response.status}`);
        }

        const data = await response.json();

        if (!Array.isArray(data)) return;

        const diasOrden = ["Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado", "Domingo"];
        data.sort((a, b) => diasOrden.indexOf(a.day) - diasOrden.indexOf(b.day));

        // Ahora esta función YA EXISTE:
        renderPlan(data);

    } catch (error) {
        console.error("Error:", error);
        const container = document.getElementById('weekly-plan-container');
        if (container) {
            container.innerHTML = `<p class="text-red-500">Error: ${error.message}</p>`;
        }
    }
}

// Esta función transforma los datos del JSON en código HTML
function renderPlan(plans) {
    const container = document.getElementById('weekly-plan-container');
    if (!container) return;

    container.innerHTML = ''; 

    plans.forEach(plan => {
        const isCompleted = plan.status === 'completed';
        
        // Estilos
        const bgStyle = isCompleted ? 'background-color: #ecfdf5;' : 'background-color: #ffffff;';
        const borderStyle = isCompleted ? 'border-left: 5px solid #10b981;' : 'border-left: 5px solid #f59e0b;';
        const iconColor = isCompleted ? '#10b981' : '#e5e7eb'; 
        const iconClass = isCompleted ? 'ph-fill ph-check-circle' : 'ph-bold ph-circle';
        const extraClass = isCompleted ? 'completed-mode' : '';

        // Resumen
        let summaryHtml = '';
        if (!plan.meals_summary || plan.meals_summary === 'Planificar comidas...') {
            summaryHtml = '<span style="color: #9ca3af; font-style: italic; font-size: 0.9rem;">Toque para planificar...</span>';
        } else {
            summaryHtml = `<span style="color: #4b5563; font-size: 0.95rem;">${plan.meals_summary}</span>`;
        }

        // AQUÍ ESTÁ EL CAMBIO: Añadido onkeydown
        const cardHtml = `
            <div class="status-card ${extraClass}" 
                 data-id="${plan.id}"
                 role="button" 
                 tabindex="0" 
                 onclick="navigateToDay('${plan.day}')"
                 onkeydown="if(event.key === 'Enter') navigateToDay('${plan.day}')"
                 style="${bgStyle} ${borderStyle} cursor: pointer; position: relative; margin-bottom: 1rem; padding: 16px; border-radius: 12px; box-shadow: 0 2px 5px rgba(0,0,0,0.05); transition: all 0.3s ease;">
                
                <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                    
                    <div style="flex: 1; padding-right: 15px;">
                        <div style="display: flex; align-items: center; margin-bottom: 6px;">
                            <h3 style="margin: 0; font-size: 1.1rem; font-weight: 700; color: #374151; margin-right: 10px;">${plan.day}</h3>
                            <span class="kcal-badge" style="font-size: 0.75rem; font-weight: 600; color: #6b7280; background: rgba(0,0,0,0.05); padding: 2px 6px; border-radius: 4px;">
                                ${plan.calories} kcal
                            </span>
                        </div>
                        <p style="margin: 0; line-height: 1.4;">${summaryHtml}</p>
                    </div>

                    <button onclick="togglePlanStatus(event, ${plan.id})" 
                            class="status-btn"
                            style="background: none; border: none; cursor: pointer; padding: 5px; margin-top: -5px; margin-right: -5px; z-index: 10;">
                        <i class="${iconClass}" style="font-size: 28px; color: ${iconColor}; transition: transform 0.2s;"></i>
                    </button>
                
                </div>
            </div>
        `;
        container.insertAdjacentHTML('beforeend', cardHtml);
    });
    
    if (typeof anime !== 'undefined') {
        anime({ targets: '.status-card', opacity: [0, 1], translateY: [10, 0], delay: anime.stagger(50) });
    }
}


function navigateToDay(day) {
    console.log("Navegando al día:", day);
    window.location.href = `/crear-dieta?day=${encodeURIComponent(day)}`;
}

// ==========================================
// 2. FUNCIÓN PARA LEER EL TOKEN DE LA COOKIE
// ==========================================
function getCookie(name) {
    let match = document.cookie.match(new RegExp('(^| )' + name + '=([^;]+)'));
    if (match) return decodeURIComponent(match[2]);
    return null;
}
// ==========================================
// FUNCIÓN INTERACCIÓN (LÓGICA + API)
// ==========================================
async function togglePlanStatus(event, id) {
    // 1. IMPORTANTE: Matar el evento aquí mismo
    // Esto evita que el clic "suba" al div grande y te redirija
    if (event) {
        event.preventDefault(); // Evita comportamientos por defecto
        event.stopPropagation(); // Evita que llegue al padre (div grande)
        event.stopImmediatePropagation(); // Asegura que nada más lo escuche
    }

    const btn = event.currentTarget;
    const card = btn.closest('.status-card');
    const icon = btn.querySelector('i');

    // 2. Detectar estado actual
    const isCurrentlyCompleted = card.classList.contains('completed-mode');
    const newStatus = isCurrentlyCompleted ? 'pending' : 'completed';

    // 3. CAMBIO VISUAL (Optimista)
    if (newStatus === 'completed') {
        card.classList.add('completed-mode');
        icon.className = 'ph-fill ph-check-circle';
        icon.style.color = '#10b981';
        card.style.backgroundColor = '#ecfdf5';
        card.style.borderLeft = '5px solid #10b981';
        // Animación pequeña
        if (typeof anime !== 'undefined') anime({ targets: icon, scale: [0.8, 1.2, 1], duration: 400 });
    } else {
        card.classList.remove('completed-mode');
        icon.className = 'ph-bold ph-circle';
        icon.style.color = '#e5e7eb';
        card.style.backgroundColor = '#ffffff';
        card.style.borderLeft = '5px solid #f59e0b';
    }

    // 4. GUARDAR EN DB
    const xsrfToken = getCookie('XSRF-TOKEN');
    try {
        const response = await fetch(`/api/weekly-plans/${id}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-XSRF-TOKEN': xsrfToken 
            },
            body: JSON.stringify({ status: newStatus })
        });

        if (!response.ok) {
             console.error("Error guardando estado");
             // Opcional: deshacer cambios visuales si falla
        }
    } catch (error) {
        console.error(error);
    }
}
// ... RESTO DE FUNCIONES IGUALES ...
function initScannerLogic() {
    const btnVer = document.getElementById('btn-ver'); 
    const inputManual = document.getElementById('barcode-input'); 
    const btnScan = document.getElementById('btn-scan');
    const readerDiv = document.getElementById('reader');

    if (btnVer && inputManual) {
        btnVer.addEventListener('click', () => {
            const code = inputManual.value.trim();
            if (code) goToProduct(code);
        });
    }
    if (btnScan && readerDiv && typeof Html5Qrcode !== 'undefined') {
        const html5QrCode = new Html5Qrcode("reader");
        btnScan.addEventListener('click', () => {
            readerDiv.style.display = 'block';
            html5QrCode.start({ facingMode: "environment" }, { fps: 10, qrbox: 250 },
                (decodedText) => { html5QrCode.stop(); readerDiv.style.display = 'none'; goToProduct(decodedText); }
            ).catch(() => { readerDiv.style.display = 'none'; });
        });
    }
}

function initNavigationButtons() {
    const btnCrear = document.getElementById('btn-crear-dieta');
    if (btnCrear) btnCrear.onclick = () => window.location.href = 'crear-dieta';
}

async function goToProduct(code) {
    try {
        const response = await fetch(`https://world.openfoodfacts.org/api/v0/product/${code.trim()}.json`);
        const data = await response.json();
        if (data.status === 1) {
            localStorage.setItem('producto_actual', JSON.stringify(data.product));
            window.location.href = 'info-producto';
        } else { alert("Producto no encontrado."); }
    } catch (e) { console.error(e); }
}

/* --- LÓGICA DEL VIDEO MODAL --- */
document.addEventListener('DOMContentLoaded', () => {
    const triggerBtn = document.getElementById('video-trigger-btn');
    const closeBtn = document.getElementById('close-modal-btn');
    const modal = document.getElementById('video-modal');
    const video = document.getElementById('popup-video');

    if(triggerBtn && modal) {
        // Abrir modal
        triggerBtn.addEventListener('click', () => {
            modal.classList.add('open');
            if(video) video.play(); // Auto-reproducir al abrir (opcional)
        });

        // Cerrar modal
        const closeModal = () => {
            modal.classList.remove('open');
            if(video) {
                video.pause();
                video.currentTime = 0; // Reiniciar video
            }
        };

        closeBtn.addEventListener('click', closeModal);
        
        // Cerrar si tocas fuera del video (en lo oscuro)
        modal.addEventListener('click', (e) => {
            if (e.target === modal) closeModal();
        });
    }
});

