// ==========================================
// MAIN.JS (Solo para nutricion.html y navegación)
// ==========================================

document.addEventListener('DOMContentLoaded', () => {
    
    // --- 1. LÓGICA DEL ESCÁNER Y CÓDIGO DE BARRAS ---
    const btnVerManual = document.getElementById('btn-ver');
    const inputManual = document.getElementById('barcode-input');
    const btnScan = document.getElementById('btn-scan');

    // Botón manual "Ver"
    if (btnVerManual && inputManual) {
        btnVerManual.addEventListener('click', () => {
            const code = inputManual.value.trim();
            if (code) goToProduct(code);
            else alert("Por favor, escribe un código de barras.");
        });
    }

    // Botón Escanear (Cámara)
    if (btnScan && typeof Html5Qrcode !== 'undefined') {
        const html5QrCode = new Html5Qrcode("reader");
        btnScan.addEventListener('click', () => {
            document.getElementById('reader').style.display = 'block';
            html5QrCode.start(
                { facingMode: "environment" },
                { fps: 10, qrbox: 250 },
                (decodedText) => {
                    html5QrCode.stop();
                    document.getElementById('reader').style.display = 'none';
                    goToProduct(decodedText);
                }
            ).catch(err => console.error("Error cámara", err));
        });
    }

    // --- 2. REPARACIÓN DEL BOTÓN "CREAR / MODIFICAR DIETA" ---
    // Busca en tu HTML qué ID tiene este botón. Normalmente es uno de estos:
    const btnIrADieta = document.getElementById('btn-crear-dieta') || 
                        document.querySelector('a[href="crear-dieta"]');

    if (btnIrADieta) {
        btnIrADieta.addEventListener('click', (e) => {
            // Si es un <a> normal, esto no hace falta, pero si es un <button>:
            e.preventDefault(); 
            window.location.href = 'crear-dieta';
        });
    }
});

// --- FUNCIONES GLOBALES DEL ESCÁNER ---
async function goToProduct(code) {
    const url = `https://world.openfoodfacts.org/api/v0/product/${code.trim()}.json`;
    try {
        const response = await fetch(url, { headers: { "User-Agent": "FitManager/1.0" } });
        const data = await response.json();
        if (data.status === 1) {
            localStorage.setItem('producto_actual', JSON.stringify(data.product));
            window.location.href = 'info-producto';
        } else {
            alert("❌ Producto no encontrado.");
        }
    } catch (e) { console.error("Error API", e); }
}