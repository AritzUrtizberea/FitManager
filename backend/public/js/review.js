/* public/js/reviews.js */

document.addEventListener('DOMContentLoaded', () => {
    
    // Buscamos el contenedor
    const contenedor = document.getElementById('contenedor-reseñas');
    if (!contenedor) return;

    // Leemos la URL de la API desde el atributo "data-url" del HTML (más limpio)
    const API_URL = contenedor.dataset.url; 

    cargarReseñas(API_URL, contenedor);
});

async function cargarReseñas(url, contenedor) {
    try {
        const response = await fetch(url);
        
        if (!response.ok) throw new Error('Error conectando con la API');

        const reseñas = await response.json();

        // Si no hay reseñas
        if (reseñas.length === 0) {
            contenedor.innerHTML = '<p class="text-center p-4">Aún no hay reseñas. ¡Sé el primero!</p>';
            return;
        }

        // Generamos HTML
        let reseñasHTML = '';
        reseñas.forEach(r => {
            // Protección si el usuario no tiene apellido
            const user = r.user || { name: 'Anónimo', surname: '' };
            const iniciales = user.name.charAt(0).toUpperCase();
            const estrellas = '★'.repeat(r.rating) + '☆'.repeat(5 - r.rating);
            
            reseñasHTML += `
                <div class="review-card">
                    <div class="review-header">
                        <div class="review-avatar">${iniciales}</div>
                        <div>
                            <strong>${user.name} ${user.surname || ''}</strong>
                        </div>
                    </div>
                    <div class="review-stars">${estrellas}</div>
                    <p class="review-text">"${r.comment}"</p>
                </div>
            `;
        });

        // Inyectamos el HTML duplicado para el efecto infinito
        contenedor.innerHTML = `
            <div class="carousel-track">
                ${reseñasHTML}
                ${reseñasHTML} 
            </div>
        `;

    } catch (error) {
        console.error("Error:", error);
        contenedor.innerHTML = '<p class="text-center text-danger">Error cargando comentarios.</p>';
    }
}