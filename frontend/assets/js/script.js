document.addEventListener('DOMContentLoaded', () => {
        const menuToggle = document.getElementById('menuToggle');
        const navOverlay = document.getElementById('navOverlay');

        menuToggle.addEventListener('click', () => {
            navOverlay.classList.toggle('active');
            // Animación básica del icono hamburguesa
            menuToggle.classList.toggle('open');
        });
    });