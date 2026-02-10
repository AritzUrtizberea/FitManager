document.addEventListener('DOMContentLoaded', async () => {
    // 1. Referencias a todos los elementos del DOM
    const contenedor = document.getElementById('contenedor-reseñas');
    const overlay = document.getElementById('lock-overlay');
    const reviewForm = document.getElementById('review-form');
    const countBadge = document.getElementById('review-count');
    const headerLockIcon = document.getElementById('header-lock-icon');

    // --- FUNCIÓN PRINCIPAL DE CARGA ---
    async function loadReviews() {
        try {
            // CAMBIO CLAVE: Usamos la ruta API pública para que no pida login en el Index
            const response = await fetch('/api/reviews'); 
            
            if (!response.ok) throw new Error("Error al conectar con la API");

            const data = await response.json();

            // ADAPTADOR INTELIGENTE:
            // Si la respuesta viene de la API pública, a veces es solo un array.
            // Si viene del controlador interno, trae .locked y .current_user_id.
            // Aquí gestionamos los dos casos para que no falle nunca.
            const reviews = Array.isArray(data) ? data : (data.reviews || []);
            const isLocked = data.locked || false; // Si no viene el dato, asumimos NO bloqueado
            const currentUserId = data.current_user_id || null; // Si no hay login, es null

            // APLICAR ESTADO VISUAL DE BLOQUEO (Solo si existen los elementos)
            if (contenedor && overlay) {
                if (isLocked) {
                    contenedor.classList.add('locked-blur');
                    overlay.style.display = 'flex';
                    if(headerLockIcon) headerLockIcon.style.display = 'block';
                } else {
                    contenedor.classList.remove('locked-blur');
                    overlay.style.display = 'none';
                    if(headerLockIcon) headerLockIcon.style.display = 'none';
                }
            }

            // PINTAR RESEÑAS
            if (!contenedor) return;
            contenedor.innerHTML = '';
            
            if (reviews.length === 0 && !isLocked) {
                contenedor.innerHTML = '<div class="p-5 text-center text-muted" style="color:white;">Aún no hay reseñas. ¡Sé el primero!</div>';
                return;
            }

            let htmlAcumulado = '';

            reviews.forEach(r => {
                // Protección por si el usuario fue borrado
                const userName = r.user ? (r.user.name + ' ' + (r.user.surname || '')) : 'Usuario Anónimo';
                const inicial = userName.charAt(0).toUpperCase();
                
                // Colores aleatorios para el avatar
                const colors = ['#4e73df', '#1cc88a', '#36b9cc', '#f6c23e'];
                const bgAvatar = colors[Math.floor(Math.random() * colors.length)];
                
                // Generar estrellas
                let stars = '';
                for(let i=1; i<=5; i++) {
                    // Nota: He puesto fa-solid fa-star porque vi que usas FontAwesome,
                    // si usas otra librería cámbialo aquí.
                    stars += (i <= r.rating) 
                        ? '<i class="fa-solid fa-star" style="color: #f6c23e;"></i>' 
                        : '<i class="fa-regular fa-star" style="color: #ccc;"></i>';
                }

                // Botón borrar (solo si es mía, no está bloqueado y tengo ID de usuario)
                let deleteBtn = '';
                if (currentUserId && r.user_id === currentUserId && !isLocked) {
                     deleteBtn = `<button onclick="borrarResena(${r.id})" class="btn btn-sm text-danger border-0 p-0 ms-2" style="background:none; cursor:pointer; color:red;"><i class="fa-solid fa-trash"></i></button>`;
                }

                // HTML de la tarjeta
                htmlAcumulado += `
                    <div class="review-card" style="min-width: 300px; margin: 0 15px; background: #2a2a2a; padding: 20px; border-radius: 15px;">
                        <div class="review-header" style="display: flex; align-items: center; margin-bottom: 15px;">
                            <div class="avatar" style="width: 40px; height: 40px; background: ${bgAvatar}; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: bold; margin-right: 10px;">
                                ${inicial}
                            </div>
                            <div style="flex-grow: 1;">
                                <div style="display: flex; justify-content: space-between; align-items: center;">
                                    <h4 style="margin: 0; color: white; font-size: 1rem;">${userName} ${deleteBtn}</h4>
                                </div>
                                <div class="stars">${stars}</div>
                            </div>
                        </div>
                        <p class="review-text" style="color: #ccc;">"${r.comment}"</p>
                    </div>
                `;
            });

            // Insertamos HTML (Duplicado para efecto carrusel infinito)
            contenedor.innerHTML = `
                <div class="carousel-track" style="display: flex;">
                    ${htmlAcumulado}
                    ${htmlAcumulado} 
                </div>
            `;
            
            // Actualizar contador si existe
            if(countBadge) {
                countBadge.innerText = isLocked ? '?' : reviews.length;
            }

        } catch (error) {
            console.error("Error cargando reseñas:", error);
            if(contenedor) contenedor.innerHTML = '<p style="text-align:center; color:white;">No se pudieron cargar las reseñas.</p>';
        }
    }

    // --- 2. ENVIAR FORMULARIO (Protegido para que no falle si no existe el form) ---
    if(reviewForm) {
        reviewForm.addEventListener('submit', async (e) => {
            e.preventDefault(); 

            const rating = document.querySelector('input[name="rating"]:checked');
            const comment = document.querySelector('textarea[name="comment"]');
            
            // Buscamos el token CSRF con seguridad
            const csrfMeta = document.querySelector('meta[name="csrf-token"]');
            if (!csrfMeta) {
                alert("Error de seguridad: No se encuentra el token CSRF.");
                return;
            }

            if (!rating) { alert('¡Por favor selecciona las estrellas!'); return; }

            try {
                // Para GUARDAR usamos la ruta WEB (que tiene sesión y CSRF)
                const res = await fetch('/reviews', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfMeta.content
                    },
                    body: JSON.stringify({
                        rating: rating.value,
                        comment: comment.value
                    })
                });

                if (res.ok) {
                    alert('¡Reseña publicada con éxito!');
                    reviewForm.reset(); 
                    window.location.reload(); 
                } else {
                    const errorText = await res.text();
                    console.error("Error servidor:", errorText);
                    alert('Hubo un error al guardar. Asegúrate de haber iniciado sesión.');
                }
            } catch (error) {
                console.error(error);
                alert('Error de conexión');
            }
        });
    }

    // Arrancamos la carga
    loadReviews();
});

// --- 3. BORRAR RESEÑA (Función Global fuera del DOMContentLoaded) ---
window.borrarResena = async (id) => {
    if(!confirm('¿Borrar tu reseña?')) return;
    
    const csrfMeta = document.querySelector('meta[name="csrf-token"]');
    if (!csrfMeta) return;

    try {
        const res = await fetch(`/reviews/${id}`, {
            method: 'DELETE',
            headers: { 
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfMeta.content 
            }
        });
        if(res.ok) window.location.reload(); 
    } catch(e) { console.error(e); }
}