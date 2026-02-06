<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Política de Privacidad - FitManager</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">

    <style>
        /* =========================================
           VARIABLES
           ========================================= */
        :root {
            --primary-blue: #2563EB; 
            --bg-light: #F3F4F6;
            --white: #ffffff;
            --text-dark: #1F2937;
            --text-muted: #6B7280;
            --border-radius: 16px;
            --shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Manrope', sans-serif;
        }

        body {
            background-color: var(--bg-light);
            color: var(--text-dark);
            min-height: 100vh;
            /* Centramos el contenido verticalmente si es poco texto, o dejamos fluir */
            display: flex;
            justify-content: center;
        }

        /* =========================================
           CONTENEDOR PRINCIPAL (Pantalla Completa)
           ========================================= */
        .main-content {
            width: 100%;
            max-width: 1000px; /* Ancho máximo para que no se estire demasiado en pantallas gigantes */
            padding: 2rem;
            margin: 0 auto; /* Centrado horizontal */
        }

        @media (max-width: 768px) {
            .main-content { padding: 1rem; }
        }

        /* Cabecera con botón atrás */
        .page-header {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 2rem;
        }

        .back-btn {
            background: var(--white);
            border: none;
            width: 45px; /* Un poco más grande para facilitar el clic */
            height: 45px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text-dark);
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
            cursor: pointer;
            text-decoration: none;
            transition: all 0.2s ease;
        }

        .back-btn:hover {
            transform: scale(1.1);
            color: var(--primary-blue);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }

        .page-title {
            font-size: 1.5rem;
            font-weight: 800;
            color: #111827;
        }

        /* Tarjeta de Privacidad */
        .privacy-card {
            background: var(--white);
            padding: 3rem; /* Más espacio interno */
            border-radius: var(--border-radius);
            box-shadow: var(--shadow);
            width: 100%;
        }

        .privacy-card h2 {
            color: var(--primary-blue);
            font-size: 1.25rem;
            margin-top: 2rem;
            margin-bottom: 1rem;
            font-weight: 700;
        }

        .privacy-card h2:first-child { margin-top: 0; }

        .privacy-card p {
            line-height: 1.8;
            color: var(--text-muted);
            margin-bottom: 1.2rem;
            font-size: 1rem;
        }

        .privacy-card ul {
            padding-left: 1.5rem;
            margin-bottom: 1.5rem;
            color: var(--text-muted);
            line-height: 1.8;
        }

        .privacy-card li { margin-bottom: 0.5rem; }

        .last-update {
            margin-top: 4rem;
            padding-top: 2rem;
            border-top: 1px solid #e5e7eb;
            font-size: 0.9rem;
            color: #9CA3AF;
            text-align: center;
            font-style: italic;
        }

        /* Ajustes para móvil */
        @media (max-width: 600px) {
            .privacy-card {
                padding: 1.5rem;
            }
            .page-title {
                font-size: 1.2rem;
            }
        }
    </style>
</head>
<body>

    <main class="main-content">
        
        <header class="page-header">
            <a href="/perfil" class="back-btn">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
            <h1 class="page-title">Política de Privacidad</h1>
        </header>

        <section class="privacy-card">
            <h2>1. Introducción</h2>
            <p>En FitManager, nos tomamos muy en serio tu privacidad. Este documento explica cómo recopilamos, utilizamos y protegemos tu información personal cuando utilizas nuestra aplicación y servicios.</p>

            <h2>2. Datos que recopilamos</h2>
            <p>Podemos recopilar información personal como tu nombre, dirección de correo electrónico, edad, peso, altura y objetivos de fitness. Estos datos son necesarios para generar tus planes de entrenamiento y nutrición personalizados.</p>

            <h2>3. Uso de la información</h2>
            <p>Utilizamos tus datos exclusivamente para:</p>
            <ul>
                <li>Proporcionar y mejorar nuestros servicios.</li>
                <li>Calcular tus métricas de salud (IMC, calorías diarias).</li>
                <li>Personalizar tu experiencia de usuario.</li>
            </ul>

            <h2>4. Seguridad de los datos</h2>
            <p>Implementamos medidas de seguridad técnicas y organizativas para proteger tus datos contra el acceso no autorizado, la alteración o la destrucción. Tus contraseñas se almacenan encriptadas.</p>

            <h2>5. Cookies</h2>
            <p>Utilizamos cookies para mantener tu sesión activa y recordar tus preferencias. Puedes configurar tu navegador para rechazar las cookies, pero algunas funciones de la app podrían no funcionar correctamente.</p>

            <h2>6. Contacto</h2>
            <p>Si tienes preguntas sobre esta política, puedes contactarnos en soporte@fitmanager.com.</p>

            <div class="last-update">
                Última actualización: Febrero 2026
            </div>
        </section>

    </main>

</body>
</html>