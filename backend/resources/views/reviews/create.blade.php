<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Reseñas - FitManager</title>
    
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
            padding-bottom: 80px; /* Espacio para scroll en móvil */
        }

        /* --- ESTILOS MOBILE FIRST --- */
        
        .header-app {
            background: white;
            padding: 20px;
            position: sticky;
            top: 0;
            z-index: 100;
            box-shadow: 0 4px 20px rgba(0,0,0,0.03);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .btn-back {
            background: #f1f3f9;
            border: none;
            width: 40px;
            height: 40px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #555;
            transition: 0.2s;
            text-decoration: none;
        }
        
        .btn-back:active { transform: scale(0.95); }

        .card-premium {
            background: white;
            border-radius: 24px; /* Bordes más redondos estilo iOS */
            border: none;
            box-shadow: 0 10px 30px rgba(0,0,0,0.04);
            margin-bottom: 20px;
            overflow: hidden;
        }

        /* Inputs grandes para dedos */
        .form-control-modern {
            background-color: #f9fafc;
            border: 2px solid #edf2f7;
            border-radius: 16px;
            padding: 16px;
            font-size: 16px; /* Evita zoom en iPhone */
        }
        
        .form-control-modern:focus {
            background-color: white;
            border-color: #4e73df;
            box-shadow: none;
            outline: none;
        }

        /* Estrellas grandes y táctiles */
        .star-rating {
            display: flex;
            flex-direction: row-reverse;
            justify-content: center;
            gap: 15px; /* Más espacio para tocar */
            margin: 10px 0;
        }
        .star-rating label { 
            font-size: 2rem; 
            color: #ddd;
            cursor: pointer;
        }
        .star-rating label:hover,
        .star-rating label:hover ~ label,
        .star-rating input:checked ~ label {
            color: #ffc107;
        }
        .star-rating input { display: none; }

        /* Botón grande flotante o fijo abajo */
        .btn-gradient {
            background: var(--primary-gradient);
            border: none;
            border-radius: 16px;
            padding: 16px;
            font-weight: 700;
            width: 100%;
            font-size: 1.1rem;
            box-shadow: 0 10px 20px rgba(78, 115, 223, 0.3);
        }
        .btn-gradient:active { transform: scale(0.98); }

        /* Lista de reseñas limpia */
        .review-item {
            padding: 20px;
            border-bottom: 1px solid #f0f0f0;
        }
        .review-item:last-child { border-bottom: none; }

        .avatar-circle {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 1.2rem;
            margin-right: 15px;
        }
    </style>
</head>
<body>

    <header class="header-app">
        <a href="{{ url('/perfil') }}" class="btn-back">
            <i class="ph ph-caret-left" style="font-size: 1.2rem;"></i>
        </a>
        <h5 class="m-0 fw-bold text-dark">Mis Reseñas</h5>
        <div style="width: 40px;"></div> </header>

    <div class="container mt-4">
        <div class="row g-4">
            
            <div class="col-lg-4 col-md-12 order-lg-1 order-1">
                <div class="card-premium p-4">
                    <h6 class="text-uppercase text-muted fw-bold mb-3" style="font-size: 0.8rem; letter-spacing: 1px;">Tu experiencia</h6>
                    
                    @if(session('success'))
                        <div class="alert alert-success rounded-4 border-0 mb-3 d-flex align-items-center">
                            <i class="ph ph-check-circle me-2 fs-5"></i>
                            <div>{{ session('success') }}</div>
                        </div>
                    @endif

                    <form action="{{ route('reviews.store') }}" method="POST">
                        @csrf
                        
                        <div class="text-center mb-4">
                            <div class="star-rating">
                                <input type="radio" id="star5" name="rating" value="5"><label for="star5" class="ph-fill ph-star"></label>
                                <input type="radio" id="star4" name="rating" value="4"><label for="star4" class="ph-fill ph-star"></label>
                                <input type="radio" id="star3" name="rating" value="3"><label for="star3" class="ph-fill ph-star"></label>
                                <input type="radio" id="star2" name="rating" value="2"><label for="star2" class="ph-fill ph-star"></label>
                                <input type="radio" id="star1" name="rating" value="1"><label for="star1" class="ph-fill ph-star"></label>
                            </div>
                            <small class="text-muted">Toca las estrellas para calificar</small>
                             @error('rating') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-4">
                            <textarea name="comment" class="form-control form-control-modern" rows="4" placeholder="Escribe tu opinión aquí..." required></textarea>
                             @error('comment') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>

                        <button type="submit" class="btn btn-primary btn-gradient text-white">
                            Publicar Reseña
                        </button>
                    </form>
                </div>
            </div>

            <div class="col-lg-8 col-md-12 order-lg-2 order-2">
                <div class="d-flex justify-content-between align-items-center px-2 mb-3">
                    <h6 class="fw-bold text-dark m-0">Comentarios recientes</h6>
                    <span class="badge bg-primary rounded-pill" id="total-reviews">0</span>
                </div>

                <div class="card-premium">
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
            const totalCounter = document.getElementById('total-reviews');

            try {
                const response = await fetch('/api/reviews');
                const reviews = await response.json();

                contenedor.innerHTML = ''; 
                totalCounter.innerText = reviews.length;

                if (reviews.length === 0) {
                    contenedor.innerHTML = `
                        <div class="text-center p-5">
                            <i class="ph ph-chats-teardrop text-muted" style="font-size: 3rem;"></i>
                            <p class="text-muted mt-2">Sé el primero en opinar.</p>
                        </div>`;
                    return;
                }

                reviews.forEach(r => {
                    const nombre = r.user ? r.user.name : 'Usuario';
                    const inicial = nombre.charAt(0).toUpperCase();
                    // Formato fecha corto para móvil
                    const fecha = new Date(r.created_at).toLocaleDateString('es-ES');
                    
                    let estrellasHtml = '';
                    for(let i=0; i<5; i++) {
                        estrellasHtml += (i < r.rating) 
                            ? '<i class="ph-fill ph-star text-warning"></i>' 
                            : '<i class="ph ph-star text-muted" style="opacity: 0.3"></i>';
                    }

                    const colores = ['#FF6B6B', '#4ECDC4', '#45B7D1', '#96CEB4', '#FFEEAD'];
                    const colorRandom = colores[Math.floor(Math.random() * colores.length)];

                    const item = `
                        <div class="review-item">
                            <div class="d-flex align-items-start">
                                <div class="avatar-circle flex-shrink-0" style="background: ${colorRandom}">
                                    ${inicial}
                                </div>
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <h6 class="mb-0 fw-bold text-dark">${nombre}</h6>
                                        <span class="text-muted small">${fecha}</span>
                                    </div>
                                    <div class="text-warning mb-1" style="font-size: 0.9rem;">
                                        ${estrellasHtml}
                                    </div>
                                    <p class="text-secondary mb-0 small" style="line-height: 1.5;">${r.comment}</p>
                                </div>
                            </div>
                        </div>
                    `;
                    contenedor.innerHTML += item;
                });

            } catch (error) {
                console.error(error);
                contenedor.innerHTML = '<div class="p-4 text-center text-danger">Error de conexión</div>';
            }
        });
    </script>

</body>
</html>