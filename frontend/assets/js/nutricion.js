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

function initWeeklyPlan() {
    const diasSemana = ['lunes', 'martes', 'miercoles', 'jueves', 'viernes', 'sabado', 'domingo'];

    diasSemana.forEach(dia => {
        // 1. GESTIÓN DE NOMBRES
        let sufijoDia = dia;
        if (dia === 'miercoles') sufijoDia = 'miércoles';
        if (dia === 'sabado')    sufijoDia = 'sábado';

        // 2. REFERENCIAS DOM
        const cardUI = document.querySelector(`.border-${dia}`) || document.querySelector(`.border-${sufijoDia}`);
        const statusUI = document.getElementById(`status-${dia}`) || document.getElementById(`status-${sufijoDia}`);
        const listaUI = document.getElementById(`lista-comida-${dia}`) || document.getElementById(`lista-comida-${sufijoDia}`);
        
        const dataJson = localStorage.getItem(`dieta_${dia}`);

        // --- A. CONFIGURACIÓN TARJETA ---
        if (cardUI) {
            // Accesibilidad intacta
            cardUI.setAttribute('tabindex', '0'); 
            cardUI.style.cursor = 'pointer';

            // Función de navegación con ANIMACIÓN DE SALIDA
            const irALaDieta = () => {
                // Animación: La tarjeta se encoge un poco antes de irse
                anime({
                    targets: cardUI,
                    scale: 0.95,
                    duration: 150,
                    easing: 'easeOutQuad',
                    complete: () => {
                        window.location.href = `crear-dieta.html?dia=${dia}`;
                    }
                });
            };

            // Evento Click
            cardUI.onclick = (e) => {
                if (statusUI && statusUI.contains(e.target)) return;
                irALaDieta();
            };

            // Evento Teclado
            cardUI.addEventListener('keydown', (e) => {
                if (e.key === 'Enter' || e.key === ' ') {
                    if (document.activeElement === statusUI) return; 
                    e.preventDefault();
                    irALaDieta();
                }
            });
        }

        // --- B. SI HAY DATOS ---
        if (dataJson) {
            const dieta = JSON.parse(dataJson);

            // Resumen visual
            if (listaUI && dieta.items && dieta.items.length > 0) {
                const nombres = dieta.items.slice(0, 2).map(p => p.name || "Producto").join(', ');
                listaUI.innerHTML = `
                    <div style="display:flex; justify-content:space-between; align-items:center;">
                        <span style="color:#555; font-size: 0.9rem;">🍴 ${nombres}...</span>
                        <span style="font-weight:700; color:#2ecc71;">${Math.round(dieta.total)} kcal</span>
                    </div>`;
            }

            // --- C. LÓGICA DEL BADGE (Estado) ---
            if (statusUI) {
                statusUI.setAttribute('tabindex', '0');
                statusUI.style.cursor = 'pointer';
                
                // Función pintar
                const actualizarVisual = (animar = false) => {
                    if (dieta.completado) {
                        statusUI.className = 'badge badge-complete'; // Usamos tus clases CSS
                        statusUI.style.backgroundColor = '#d1fae5';
                        statusUI.style.color = '#065f46';
                        statusUI.innerHTML = '<i class="ph ph-check-circle"></i> Completo';
                    } else {
                        statusUI.className = 'badge badge-pending';
                        statusUI.style.backgroundColor = '#fffbeb';
                        statusUI.style.color = '#92400e';
                        statusUI.innerHTML = '<i class="ph ph-hourglass"></i> Pendiente';
                    }

                    // ANIMACIÓN "POP" (Solo si se activa por interacción)
                    if (animar) {
                        anime({
                            targets: statusUI,
                            scale: [0.5, 1], // Rebote elástico fuerte
                            duration: 800,
                            easing: 'easeOutElastic(1, .5)'
                        });
                    }
                };
                
                // Carga inicial (sin animación)
                actualizarVisual(false);

                // Función cambio
                const toggleEstado = (e) => {
                    e.stopPropagation(); 
                    if(e.type === 'keydown') e.preventDefault();

                    dieta.completado = !dieta.completado;
                    localStorage.setItem(`dieta_${dia}`, JSON.stringify(dieta));
                    
                    // Actualizamos CON animación
                    actualizarVisual(true); 
                };

                statusUI.onclick = toggleEstado;
                statusUI.addEventListener('keydown', (e) => {
                    if (e.key === 'Enter' || e.key === ' ') toggleEstado(e);
                });
            }
        } else {
            if (listaUI) listaUI.textContent = "Sin planificar";
            if (statusUI) statusUI.style.display = 'none';
        }
    });
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
    if (btnCrear) btnCrear.onclick = () => window.location.href = 'crear-dieta.html';
}

async function goToProduct(code) {
    try {
        const response = await fetch(`https://world.openfoodfacts.org/api/v0/product/${code.trim()}.json`);
        const data = await response.json();
        if (data.status === 1) {
            localStorage.setItem('producto_actual', JSON.stringify(data.product));
            window.location.href = 'info-producto.html';
        } else { alert("Producto no encontrado."); }
    } catch (e) { console.error(e); }
}