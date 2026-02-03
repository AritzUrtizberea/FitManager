document.addEventListener('DOMContentLoaded', async () => {
            const contenedor = document.getElementById('contenedor-reseñas');
            const overlay = document.getElementById('lock-overlay');
            const reviewForm = document.getElementById('review-form');
            const countBadge = document.getElementById('review-count');

            // --- 1. FUNCIÓN PARA CARGAR Y BLOQUEAR/DESBLOQUEAR ---
            async function loadReviews() {
                try {
                    // Usamos la ruta que definimos en el Controller para JSON
                    const response = await fetch('/reviews/list'); 
                    const data = await response.json();

                    const reviews = data.reviews;
                    const isLocked = data.locked; // El controlador nos dice si bloquear o no
                    const currentUserId = data.current_user_id;

                    // APLICAR ESTADO VISUAL
                    if (isLocked) {
                        contenedor.classList.add('locked-blur');
                        overlay.style.display = 'flex';
                        document.getElementById('header-lock-icon').style.display = 'block';
                    } else {
                        contenedor.classList.remove('locked-blur');
                        overlay.style.display = 'none';
                        document.getElementById('header-lock-icon').style.display = 'none';
                    }

                    // PINTAR RESEÑAS
                    contenedor.innerHTML = '';
                    
                    if (reviews.length === 0 && !isLocked) {
                        contenedor.innerHTML = '<div class="p-5 text-center text-muted">Aún no hay reseñas. ¡Sé el primero!</div>';
                        return;
                    }

                    reviews.forEach(r => {
                        const userName = r.user ? r.user.name : 'Usuario';
                        const inicial = userName.charAt(0).toUpperCase();
                        const colors = ['#4e73df', '#1cc88a', '#36b9cc', '#f6c23e'];
                        const bgAvatar = colors[Math.floor(Math.random() * colors.length)];
                        
                        // Generar estrellas HTML
                        let stars = '';
                        for(let i=1; i<=5; i++) {
                            stars += (i <= r.rating) 
                                ? '<i class="ph-fill ph-star text-warning"></i>' 
                                : '<i class="ph ph-star text-muted" style="opacity:0.3"></i>';
                        }

                        // Botón borrar (solo si es mía y está desbloqueado)
                        let deleteBtn = '';
                        if (r.user_id === currentUserId && !isLocked) {
                             deleteBtn = `<button onclick="borrarResena(${r.id})" class="btn btn-sm text-danger border-0 p-0 ms-2"><i class="ph ph-trash"></i></button>`;
                        }

                        const html = `
                            <div class="review-item">
                                <div class="d-flex align-items-start">
                                    <div class="avatar-circle flex-shrink-0" style="background: ${bgAvatar}">
                                        ${inicial}
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <h6 class="mb-0 fw-bold text-dark">${userName} ${deleteBtn}</h6>
                                            <small class="text-muted" style="font-size:0.75rem">Hace poco</small>
                                        </div>
                                        <div class="text-warning mb-1" style="font-size: 0.9rem;">${stars}</div>
                                        <p class="text-secondary mb-0 small" style="line-height: 1.5;">${r.comment}</p>
                                    </div>
                                </div>
                            </div>
                        `;
                        contenedor.innerHTML += html;
                    });
                    
                    countBadge.innerText = isLocked ? '?' : reviews.length;

                } catch (error) {
                    console.error("Error cargando:", error);
                }
            }

            // --- 2. ENVIAR FORMULARIO (SIN RECARGAR PANTALLA BLANCA) ---
            if(reviewForm) {
                reviewForm.addEventListener('submit', async (e) => {
                    e.preventDefault(); // <--- ESTO EVITA LA PANTALLA BLANCA JSON

                    const rating = document.querySelector('input[name="rating"]:checked');
                    const comment = document.querySelector('textarea[name="comment"]');
                    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

                    if (!rating) { alert('¡Por favor selecciona las estrellas!'); return; }

                    try {
                        const res = await fetch('/reviews', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': csrfToken
                            },
                            body: JSON.stringify({
                                rating: rating.value,
                                comment: comment.value
                            })
                        });

                        if (res.ok) {
                            alert('¡Reseña publicada con éxito!');
                            reviewForm.reset(); // Limpia el formulario
                            // MAGIA: Recargamos la página para que el backend detecte que ya tienes reseña y desbloquee todo
                            window.location.reload(); 
                        } else {
                            alert('Hubo un error al guardar.');
                        }
                    } catch (error) {
                        console.error(error);
                        alert('Error de conexión');
                    }
                });
            }

            // --- 3. BORRAR RESEÑA ---
            window.borrarResena = async (id) => {
                if(!confirm('¿Borrar tu reseña? Se volverá a bloquear el contenido.')) return;
                
                const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
                try {
                    const res = await fetch(`/reviews/${id}`, {
                        method: 'DELETE',
                        headers: { 'X-CSRF-TOKEN': csrfToken }
                    });
                    if(res.ok) window.location.reload(); // Recarga para volver a bloquear
                } catch(e) { console.error(e); }
            }

            // Arrancamos
            loadReviews();
        });