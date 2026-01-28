document.addEventListener("DOMContentLoaded", function() {

    // Referencias a los elementos
    const btnLogout = document.getElementById("btn-logout-trigger");
    const modal = document.getElementById("modal-logout");
    const btnCancel = document.getElementById("btn-cancel");
    const btnConfirm = document.getElementById("btn-confirm-logout");

    // 1. ABRIR EL MODAL
    if (btnLogout) {
        btnLogout.addEventListener("click", function(e) {
            e.preventDefault(); // Prevenir cualquier comportamiento raro
            modal.classList.add("active");
            // Accesibilidad: Mover el foco al botón de cancelar para evitar accidentes
            btnCancel.focus();
        });
    }

    // 2. CERRAR EL MODAL (CANCELAR)
    if (btnCancel) {
        btnCancel.addEventListener("click", function() {
            modal.classList.remove("active");
            // Devolver foco al botón original
            btnLogout.focus();
        });
    }

    // 3. CONFIRMAR SALIDA (IR A LANDING)
    if (btnConfirm) {
        btnConfirm.addEventListener("click", function() {
            console.log("Cerrando sesión...");
            
            // AQUÍ PONES LA URL DE TU LANDING PAGE
            window.location.href = "/landing.html"; 
        });
    }

    // Cerrar si clickan fuera del cuadro blanco
    window.addEventListener("click", function(e) {
        if (e.target === modal) {
            modal.classList.remove("active");
        }
    });
});

document.addEventListener("DOMContentLoaded", function() {
    const trigger = document.getElementById('triggerLogout');
    const modal = document.getElementById('modalLogout');

    // 1. Abrir el pop-up
    trigger.onclick = () => modal.style.display = 'flex';

    // 2. Al confirmar, redirigimos a la ruta del BACKEND
    document.getElementById('confirmarLogout').onclick = function() {
        // Redirigimos a la ruta que Nginx mandará al back
        // Usamos un formulario dinámico para que sea una petición POST (requerido por Laravel)
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '/api/logout'; // Esta ruta la configuramos ahora en Nginx
        document.body.appendChild(form);
        form.submit();
    };

    document.getElementById('cancelarLogout').onclick = () => modal.style.display = 'none';
});