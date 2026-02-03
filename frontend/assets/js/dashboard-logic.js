document.addEventListener('DOMContentLoaded', async () => {

    // --- 1. UTILS ---
    const updateSkeleton = (id, text) => {
        const el = document.getElementById(id);
        if (el) {
            el.textContent = text;
            el.classList.remove('skeleton', 'skeleton-text');
            el.style.width = 'auto';
            el.style.height = 'auto';
        }
    };

    // --- 2. POPUP VIDEO ---
    const modal = document.getElementById('video-modal');
    const openBtn = document.getElementById('video-trigger-btn');
    const closeBtn = document.getElementById('close-modal-btn');
    const videoPlayer = document.getElementById('popup-video');

    if (openBtn && modal) {
        openBtn.addEventListener('click', () => {
            modal.classList.add('open');
            if (videoPlayer) videoPlayer.play();
        });
        const cerrarModal = () => {
            modal.classList.remove('open');
            if (videoPlayer) { videoPlayer.pause(); videoPlayer.currentTime = 0; }
        };
        if (closeBtn) closeBtn.addEventListener('click', cerrarModal);
        modal.addEventListener('click', (e) => { if (e.target === modal) cerrarModal(); });
    }

    // --- 3. CARGA DE DATOS ---
    try {
        const res = await fetch('/api/user', { headers: { 'Accept': 'application/json' } });
        await new Promise(r => setTimeout(r, 600)); // Delay estético

        if (res.ok) {
            const rawData = await res.json();
            const user = rawData.data || rawData;

            // === CORRECCIÓN CLAVE AQUÍ ===
            // Los datos físicos están dentro del objeto 'profile'
            const profile = user.profile || {};

            console.log("Perfil físico detectado:", profile); // Para que confirmes en consola

            // --- A. FECHA ---
            const today = new Date();
            const fechaTexto = today.toLocaleDateString('es-ES', { weekday: 'long', day: 'numeric', month: 'long' });
            const fechaFinal = fechaTexto.charAt(0).toUpperCase() + fechaTexto.slice(1);
            updateSkeleton('date-display', fechaFinal);

            // --- B. INICIALES ---
            // Usamos name y surname tal cual salen en tu log
            const nombre = user.name || "Usuario";
            const apellido = user.surname || user.lastname || ""; // Tu log dice 'surname'
            let iniciales = "US";

            if (nombre && apellido) {
                iniciales = nombre.trim().charAt(0) + apellido.trim().charAt(0);
            } else {
                iniciales = nombre.substring(0, 2);
            }
            const headerInitials = document.getElementById('header-initials');
            if (headerInitials) headerInitials.textContent = iniciales.toUpperCase();

            // --- C. PESO, ALTURA Y CALORÍAS (BUSCANDO EN PROFILE) ---

            // 1. Buscamos en profile.weight, si no está, probamos user.weight, si no, 70 por defecto
            const peso = parseFloat(profile.weight || profile.peso || user.weight || 70);

            // 2. Lo mismo para altura
            const altura = parseFloat(profile.height || profile.altura || user.height || 175);

            // 3. Lo mismo para edad
            const edad = parseInt(profile.age || profile.edad || user.age || 25);

            // Actualizamos la pantalla
            updateSkeleton('user-weight', peso);

            // Fórmula de Calorías
            const calculoCalorias = Math.round((10 * peso) + (6.25 * altura) - (5 * edad) + 500);
            updateSkeleton('cal-goal', calculoCalorias);

            setTimeout(() => {
                const circle = document.getElementById('cal-circle');
                if (circle) circle.setAttribute('stroke-dasharray', `85, 100`);
            }, 100);

            // --- D. RUTINA ---
            const titulos = ["Descanso Activo", "Pecho & Tríceps", "Espalda & Bíceps", "Pierna & Glúteo", "Hombro & Abs", "Full Body", "Cardio"];
            const tituloHoy = titulos[today.getDay()] || "Entreno Libre";

            const workoutTitle = document.getElementById('workout-title');
            if (workoutTitle) {
                workoutTitle.textContent = tituloHoy;
                workoutTitle.classList.remove('text-loading');
            }

            // --- E. DÍAS DE LA SEMANA ---
            const daysContainer = document.getElementById('week-days-container');
            if (daysContainer) {
                const daysElements = daysContainer.children;
                let dayOfWeek = today.getDay();
                if (dayOfWeek === 0) dayOfWeek = 7;

                for (let i = 0; i < 7; i++) {
                    const diaVisual = i + 1;
                    daysElements[i].classList.remove('active', 'done');
                    if (diaVisual < dayOfWeek) daysElements[i].classList.add('done');
                    else if (diaVisual === dayOfWeek) daysElements[i].classList.add('active');
                }
            }

        } else {
            console.error("Error API:", res.status);
        }
    } catch (error) {
        console.error("Error JS:", error);
    }
});