// 1. URL para LEER los datos (Viene de routes/api.php)
const API_URL_GET = '/api/reviews'; 

// 2. URL para GUARDAR los datos (Viene de routes/web.php)
const API_URL_POST = '/reviews'; 

async function cargarReseñas() {
    try {
        const response = await fetch(API_URL_GET); // Usamos la ruta API
        const reseñas = await response.json();
        
        const contenedor = document.getElementById('contenedor-reseñas');
        if (!contenedor) return;

        // Limpiamos contenido previo por si acaso
        contenedor.innerHTML = '';

        // 1. Generamos el HTML de todas las tarjetas
        let reseñasHTML = '';
        reseñas.forEach(r => {
            // Protección por si r.user es null (puede pasar si se borró el usuario)
            if (!r.user) return; 

            const iniciales = (r.user.name.charAt(0) + (r.user.surname ? r.user.surname.charAt(0) : '')).toUpperCase();
            const estrellas = '★'.repeat(r.rating) + '☆'.repeat(5 - r.rating);
            // Formatear fecha
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

        // 2. Insertamos el HTML (Duplicado para efecto infinito si usas CSS animation)
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

// 2. FUNCIÓN PARA ENVIAR (Corregida para Laravel Session)
async function enviarReseña(rating, comment) {
    try {
        // Obtenemos el token CSRF del meta tag (OBLIGATORIO en Laravel Web)
        const csrfToken = document.querySelector('meta[name="csrf-token"]');
        
        if (!csrfToken) {
            console.error("Error: No se encontró el meta tag csrf-token en el HTML");
            return;
        }

        const response = await fetch(API_URL_POST, { // Usamos la ruta WEB
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                // CAMBIO CLAVE: Usamos X-CSRF-TOKEN en vez de Authorization
                'X-CSRF-TOKEN': csrfToken.content 
            },
            body: JSON.stringify({ rating, comment })
        });

        if (response.ok) {
            // Si el servidor responde OK o Redirección
            alert('¡Gracias por tu opinión!');
            // Si estás en la misma página, recargamos las reseñas
            cargarReseñas(); 
            // O si prefieres recargar la página completa:
            // window.location.reload(); 
        } else {
            console.error("Error al enviar:", await response.text());
            alert("Hubo un error al guardar la reseña.");
        }
    } catch (error) {
        console.error("Error de red:", error);
    }
}

// 3. EL DETONADOR
document.addEventListener('DOMContentLoaded', () => {
    cargarReseñas();

    // Opcional: Si tienes el formulario en esta misma página, vincúlalo aquí
    const formResena = document.getElementById('review-form'); // Asegúrate que tu <form> tenga este ID
    if (formResena) {
        formResena.addEventListener('submit', (e) => {
            e.preventDefault();
            // Asumiendo que tienes inputs con id="rating" y "comment"
            const rating = document.getElementById('rating').value;
            const comment = document.getElementById('comment').value;
            enviarReseña(rating, comment);
        });
    }
});