// ==========================================
// NUTRICION.JS (Corregido - Sin bordes forzados)
// ==========================================

document.addEventListener('DOMContentLoaded', () => {
    initScannerLogic();
    initNavigationButtons();
    initVideoModal();
    initWeeklyPlan();

    // Animaciones (Anime.js)
    try {
        if (typeof anime !== 'undefined') {
            anime({
                targets: '.status-card',
                opacity: [0, 1],
                translateY: [20, 0],
                delay: anime.stagger(100),
                easing: 'easeOutQuad',
                duration: 800
            });
        }
    } catch (e) {
        console.warn("Anime.js no cargó.");
        document.querySelectorAll('.status-card').forEach(el => el.style.opacity = 1);
    }
});

// --- LÓGICA DE DATOS Y RENDERIZADO ---

async function initWeeklyPlan() {
    try {
        const baseUrl = window.location.origin;
        const response = await fetch(`${baseUrl}/api/weekly-plans`, {
            headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
        });

        if (!response.ok) throw new Error(`Error: ${response.status}`);

        const data = await response.json();
        if (!Array.isArray(data)) return;

        const diasOrden = ["Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado", "Domingo"];
        data.sort((a, b) => diasOrden.indexOf(a.day) - diasOrden.indexOf(b.day));

        renderPlan(data);

    } catch (error) {
        console.error("Error:", error);
        const container = document.getElementById('weekly-plan-container');
        if (container) container.innerHTML = `<p style="color: #dc2626;">Error: ${error.message}</p>`;
    }
}

// Función auxiliar para convertir "Miércoles" -> "miercoles"
function normalizeDay(dayString) {
    return dayString.toLowerCase().normalize("NFD").replace(/[\u0300-\u036f]/g, "");
}

function renderPlan(plans) {
    const container = document.getElementById('weekly-plan-container');
    if (!container) return;

    container.innerHTML = '';

    plans.forEach(plan => {
        const isCompleted = plan.status === 'completed';

        // 1. GENERAR LA CLASE DEL DÍA (border-lunes, border-martes...)
        const dayClass = `border-${normalizeDay(plan.day)}`;

        // Estilos base
        const bgStyle = isCompleted ? 'background-color: #f0fdf4;' : 'background-color: #ffffff;';
        
        // CORRECCIÓN IMPORTANTE:
        // Si está completado, forzamos borde verde. 
        // Si NO está completado, NO ponemos nada (para que use el CSS border-lunes, etc.)
        const borderStyle = isCompleted ? 'border-left-color: #166534;' : '';

        const iconColor = isCompleted ? '#166534' : '#4b5563'; 
        const iconClass = isCompleted ? 'ph-fill ph-check-circle' : 'ph-bold ph-circle';
        // Añadimos dayClass a las clases
        const extraClass = isCompleted ? 'completed-mode' : dayClass;

        let summaryHtml = !plan.meals_summary || plan.meals_summary === 'Planificar comidas...' 
            ? '<span style="color: #555555; font-style: italic; font-size: 0.9rem;">Toque para planificar...</span>'
            : `<span style="color: #1f2937; font-size: 0.95rem;">${plan.meals_summary}</span>`;

        const cardHtml = `
            <div class="status-card ${extraClass}" 
                 data-id="${plan.id}"
                 role="link" 
                 tabindex="0" 
                 onclick="navigateToDay('${plan.day}')"
                 onkeydown="if(event.key === 'Enter') navigateToDay('${plan.day}')"
                 aria-label="Día ${plan.day}, ${plan.calories} calorías."
                 style="${bgStyle} ${borderStyle} cursor: pointer; position: relative; margin-bottom: 1rem; padding: 18px; border-radius: 12px; box-shadow: 0 2px 5px rgba(0,0,0,0.05); transition: all 0.3s ease;">
                
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <div style="flex: 1; padding-right: 15px;">
                        <div style="display: flex; align-items: center; margin-bottom: 4px;">
                            <h3 style="margin: 0; font-size: 1.1rem; font-weight: 800; color: #111827; margin-right: 10px;">${plan.day}</h3>
                            <span style="font-size: 0.75rem; font-weight: 700; color: #ffffff; background: #374151; padding: 2px 8px; border-radius: 6px;">
                                ${plan.calories} kcal
                            </span>
                        </div>
                        <p style="margin: 0; line-height: 1.4;">${summaryHtml}</p>
                    </div>

                    <button onclick="togglePlanStatus(event, ${plan.id})" 
                            onkeydown="event.stopPropagation()"
                            class="status-btn"
                            style="background: none; border: none; cursor: pointer; padding: 10px; z-index: 20;">
                        <i class="${iconClass}" style="font-size: 30px; color: ${iconColor}; transition: transform 0.2s;"></i>
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

// --- FUNCIONES DE NAVEGACIÓN Y ACCIÓN ---

function navigateToDay(day) {
    window.location.href = `/crear-dieta?day=${encodeURIComponent(day)}`;
}

async function togglePlanStatus(event, id) {
    if (event) {
        event.preventDefault();
        event.stopPropagation();
        event.stopImmediatePropagation();
    }

    const btn = event.currentTarget;
    const card = btn.closest('.status-card');
    const icon = btn.querySelector('i');

    const isCurrentlyCompleted = card.classList.contains('completed-mode');
    const newStatus = isCurrentlyCompleted ? 'pending' : 'completed';

    // UI Optimista
    if (newStatus === 'completed') {
        card.classList.add('completed-mode');
        // Quitamos la clase del día para evitar conflictos visuales (opcional)
        // card.classList.remove('border-lunes', 'border-martes', ...); 
        
        icon.className = 'ph-fill ph-check-circle';
        icon.style.color = '#166534';
        card.style.backgroundColor = '#f0fdf4';
        
        // Forzamos borde verde de éxito
        card.style.borderLeftColor = '#166534'; 
        
        if (typeof anime !== 'undefined') anime({ targets: icon, scale: [0.8, 1.2, 1], duration: 400 });
    } else {
        card.classList.remove('completed-mode');
        icon.className = 'ph-bold ph-circle';
        icon.style.color = '#4b5563';
        card.style.backgroundColor = '#ffffff';
        
        // CRUCIAL: Limpiamos el color en línea para que vuelva a mandar el CSS (Azul, Naranja, etc.)
        card.style.borderLeftColor = ''; 
    }

    const xsrfToken = getCookie('XSRF-TOKEN');
    try {
        await fetch(`/api/weekly-plans/${id}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-XSRF-TOKEN': xsrfToken
            },
            body: JSON.stringify({ status: newStatus })
        });
    } catch (error) {
        console.error("Error de red:", error);
    }
}

// --- UTILIDADES ---

function getCookie(name) {
    let match = document.cookie.match(new RegExp('(^| )' + name + '=([^;]+)'));
    if (match) return decodeURIComponent(match[2]);
    return null;
}

// --- ESCÁNER Y PRODUCTOS ---

function initScannerLogic() {
    const btnVer = document.getElementById('btn-ver');
    const inputManual = document.getElementById('barcode-input');
    const btnScan = document.getElementById('btn-scan');
    const readerDiv = document.getElementById('reader');

    if (inputManual) inputManual.setAttribute('aria-label', 'Introduce el código de barras');

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
                (decodedText) => { 
                    html5QrCode.stop(); 
                    readerDiv.style.display = 'none'; 
                    goToProduct(decodedText); 
                }
            ).catch(() => { readerDiv.style.display = 'none'; });
        });
    }
}

async function goToProduct(code) {
    try {
        const response = await fetch(`https://world.openfoodfacts.org/api/v0/product/${code.trim()}.json`);
        const data = await response.json();
        if (data.status === 1) {
            localStorage.setItem('producto_actual', JSON.stringify(data.product));
            window.location.href = 'info-producto';
        } else { 
            alert("Producto no encontrado."); 
        }
    } catch (e) { console.error(e); }
}

function initNavigationButtons() {
    const btnCrear = document.getElementById('btn-crear-dieta');
    if (btnCrear) btnCrear.onclick = () => window.location.href = 'crear-dieta';
}

function initVideoModal() {
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

        if(closeBtn) closeBtn.addEventListener('click', closeModal);
        modal.addEventListener('click', (e) => { if (e.target === modal) closeModal(); });
    }
}