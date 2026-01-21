

    const menuToggle = document.getElementById('menu-toggle');
    
if (menuToggle) { // Añade esta condición
    menuToggle.addEventListener('click', function() {
    document.addEventListener('DOMContentLoaded', () => {
        const menuToggle = document.getElementById('menuToggle');
        const navOverlay = document.getElementById('navOverlay');

        menuToggle.addEventListener('click', () => {
            navOverlay.classList.toggle('active');
            // Animación básica del icono hamburguesa
            menuToggle.classList.toggle('open');
        });
    });
    });
}