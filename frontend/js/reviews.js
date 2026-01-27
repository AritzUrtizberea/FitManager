const API_URL = 'http://localhost/api/reviews';

async function cargarReseñas() {
    try {
        const response = await fetch(API_URL);
        const reseñas = await response.json();
        
        const contenedor = document.getElementById('contenedor-reseñas');
        if (!contenedor) return;

        // 1. Generamos el HTML de todas las tarjetas primero
        let reseñasHTML = '';
        reseñas.forEach(r => {
            const iniciales = (r.user.name.charAt(0) + (r.user.surname ? r.user.surname.charAt(0) : '')).toUpperCase();
            const estrellas = '★'.repeat(r.rating) + '☆'.repeat(5 - r.rating);
            const fecha = new Date(r.created_at).toLocaleDateString('es-ES', { day: 'numeric', month: 'short' });

            reseñasHTML += `
                <div class="review-card">
                    <div class="review-stars">${estrellas}</div>
                    <p class="review-text">"${r.comment}"</p>
                    <div class="review-user-info">
                        <div class="review-avatar">${iniciales}</div>
                        <div class="user-details">
                            <span class="user-name">${r.user.name} ${r.user.surname || ''}</span>
                            <span class="review-date">${fecha}</span>
                        </div>
                    </div>
                </div>
            `;
        });

        // 2. Creamos el "Tren" (track) y duplicamos el contenido para el efecto infinito
        contenedor.innerHTML = `
            <div class="carousel-track">
                ${reseñasHTML}
                ${reseñasHTML} 
            </div>
        `;

    } catch (error) {
        console.error("Error al cargar reseñas:", error);
    }
}

// 2. FUNCIÓN PARA ENVIAR (Esta se usa solo si hay formulario)
async function enviarReseña(rating, comment) {
    const token = localStorage.getItem('auth_token');
    
    try {
        const response = await fetch(API_URL, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Authorization': `Bearer ${token}`,
                'Accept': 'application/json'
            },
            body: JSON.stringify({ rating, comment })
        });

        if (response.ok) {
            alert('¡Gracias por tu opinión!');
            cargarReseñas(); // Actualiza la pantalla
        }
    } catch (error) {
        console.error("Error enviando:", error);
    }
} 
// <--- ¡OJO! Aquí se cierra la función enviarReseña.



// 3. EL DETONADOR (¡ESTO ES LO QUE FALTABA!)
// Esto le dice al navegador: "En cuanto cargues la web, PINTA las reseñas".
document.addEventListener('DOMContentLoaded', () => {
    cargarReseñas();
});