<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Escribir Reseña - FitManager</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
            --bg-color: #f0f2f5;
        }

        body {
            background-color: var(--bg-color);
            font-family: 'Inter', sans-serif;
            -webkit-font-smoothing: antialiased;
            padding-bottom: 80px;
        }

        /* --- TUS ESTILOS --- */
        .header-app { background: white; padding: 20px; position: sticky; top: 0; z-index: 100; box-shadow: 0 4px 20px rgba(0,0,0,0.03); display: flex; align-items: center; justify-content: space-between; }
        .btn-back { background: #f1f3f9; border: none; width: 40px; height: 40px; border-radius: 12px; display: flex; align-items: center; justify-content: center; color: #555; transition: 0.2s; text-decoration: none; }
        .card-premium { background: white; border-radius: 24px; border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.04); margin-bottom: 20px; overflow: hidden; position: relative; }
        .form-control-modern { background-color: #f9fafc; border: 2px solid #edf2f7; border-radius: 16px; padding: 16px; font-size: 16px; }
        .form-control-modern:focus { background-color: white; border-color: #4e73df; box-shadow: none; outline: none; }
        .star-rating { display: flex; flex-direction: row-reverse; justify-content: center; gap: 15px; margin: 10px 0; }
        .star-rating label { font-size: 2rem; color: #ddd; cursor: pointer; transition: color 0.2s; }
        .star-rating label:hover, .star-rating label:hover ~ label, .star-rating input:checked ~ label { color: #ffc107; }
        .star-rating input { display: none; }
        .btn-gradient { background: var(--primary-gradient); border: none; border-radius: 16px; padding: 16px; font-weight: 700; width: 100%; font-size: 1.1rem; box-shadow: 0 10px 20px rgba(78, 115, 223, 0.3); transition: transform 0.2s; }
        .btn-gradient:active { transform: scale(0.98); }
        .review-item { padding: 20px; border-bottom: 1px solid #f0f0f0; }
        .review-item:last-child { border-bottom: none; }
        .avatar-circle { width: 50px; height: 50px; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: bold; font-size: 1.2rem; margin-right: 15px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); }

        /* --- EFECTO BORROSO / CANDADO --- */
        .locked-blur {
            filter: blur(8px);
            pointer-events: none;
            user-select: none;
            opacity: 0.5;
        }

        .lock-overlay {
            position: absolute;
            top: 0; left: 0; width: 100%; height: 100%;
            display: none; /* Se activa con JS */
            flex-direction: column;
            align-items: center;
            justify-content: center;
            z-index: 10;
            background: rgba(255, 255, 255, 0.1);
        }
        
        .lock-icon-box {
            background: white;
            width: 80px; height: 80px;
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            margin-bottom: 15px;
        }
    </style>
</head>
<body>

    <header class="header-app">
        <a href="{{ route('home') }}" class="btn-back">
            <i class="ph ph-house" style="font-size: 1.2rem;"></i>
        </a>
        <h5 class="m-0 fw-bold text-dark">Tu Reseña</h5>
        <div style="width: 40px;"></div> 
    </header>

    <div class="container mt-4">
        
        <div class="row g-4">
            
            <div class="col-lg-4 col-md-12 order-lg-1 order-1">
                <div class="card-premium p-4 border border-primary border-2">
                    <h6 class="text-uppercase text-primary fw-bold mb-3" style="font-size: 0.8rem; letter-spacing: 1px;">Paso 1: Comparte tu opinión</h6>
                    
                    <form id="review-form">
                        <div class="text-center mb-4">
                            <div class="star-rating">
                                <input type="radio" id="star5" name="rating" value="5"><label for="star5" class="ph-fill ph-star"></label>
                                <input type="radio" id="star4" name="rating" value="4"><label for="star4" class="ph-fill ph-star"></label>
                                <input type="radio" id="star3" name="rating" value="3"><label for="star3" class="ph-fill ph-star"></label>
                                <input type="radio" id="star2" name="rating" value="2"><label for="star2" class="ph-fill ph-star"></label>
                                <input type="radio" id="star1" name="rating" value="1"><label for="star1" class="ph-fill ph-star"></label>
                            </div>
                            <small class="text-muted">¿Qué te parece la app?</small>
                        </div>

                        <div class="mb-4">
                            <textarea name="comment" class="form-control form-control-modern" rows="4" placeholder="Escribe aquí..." required></textarea>
                        </div>

                        <button type="submit" class="btn btn-primary btn-gradient text-white">
                            Desbloquear Reseñas <i class="ph-bold ph-lock-open ms-2"></i>
                        </button>
                    </form>
                </div>
            </div>

            <div class="col-lg-8 col-md-12 order-lg-2 order-2">
                <div class="d-flex justify-content-between align-items-center px-2 mb-3">
                    <h6 class="fw-bold text-dark m-0">Comunidad <span id="review-count" class="badge bg-primary ms-2 rounded-pill">0</span></h6>
                    <i id="header-lock-icon" class="ph-fill ph-lock-key text-muted" style="display:none"></i>
                </div>

                <div class="card-premium" style="min-height: 400px;">
                    
                    <div class="lock-overlay" id="lock-overlay">
                        <div class="lock-icon-box">
                            <i class="ph-fill ph-lock-key text-primary" style="font-size: 2.5rem;"></i>
                        </div>
                        <h5 class="fw-bold text-dark">Contenido Bloqueado</h5>
                        <p class="text-muted small text-center px-4">
                            Publica tu primera reseña para ver<br>lo que opinan los demás.
                        </p>
                    </div>

                    <div id="contenedor-reseñas">
                        <div class="p-5 text-center text-muted">
                            <div class="spinner-border text-primary" role="status"></div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script>
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
    </script>
</body>
</html>