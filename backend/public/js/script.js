/* ARCHIVO: public/js/script.js 
   ESTE ARCHIVO SE CARGA EN TODAS LAS PÁGINAS.
   NO PONGAS AQUÍ LÓGICA DE CALORÍAS, NI REDIRECCIONES, NI FETCH DE GUARDAR.
*/

document.addEventListener('DOMContentLoaded', () => {
    
    // 1. Lógica del Menú Hamburguesa (Segura para todos)
    const menuToggle = document.getElementById('menuToggle');
    const navOverlay = document.getElementById('navOverlay');

    if (menuToggle && navOverlay) {
        menuToggle.addEventListener('click', () => {
            navOverlay.classList.toggle('active');
            menuToggle.classList.toggle('open');
        });
        
        // Cierra el menú si tocas fuera
        navOverlay.addEventListener('click', (e) => {
            if (e.target === navOverlay) {
                navOverlay.classList.remove('active');
                menuToggle.classList.remove('open');
            }
        });
    }
});