document.addEventListener('DOMContentLoaded', async () => {
    
    // 1. Fecha en español
    const dateOptions = { weekday: 'long', day: 'numeric', month: 'short' };
    const today = new Date();
    document.getElementById('date-display').textContent = today.toLocaleDateString('es-ES', dateOptions);

    // 2. Marcar Semana
    marcarDiaSemana();

    try {
        console.log("Iniciando carga de usuario...");
        const response = await fetch('/api/user', {
            headers: { 'Accept': 'application/json' }
        });

        if (response.ok) {
            const user = await response.json();
            console.log("Datos recibidos del servidor:", user); // IMPORTANTE: Mira esto en la consola (F12)

            // --- A. NOMBRE ---
            document.getElementById('user-name').textContent = `Hola, ${user.name || 'Atleta'}`;
            
            // --- B. PESO (SOLUCIÓN ROBUSTA) ---
            // Buscamos 'weight', 'peso' o 'currentWeight' por si acaso
            let pesoUsuario = user.weight || user.peso || user.currentWeight;
            
            if (pesoUsuario) {
                document.getElementById('user-weight').textContent = pesoUsuario;
            } else {
                document.getElementById('user-weight').textContent = "--";
                // Opcional: Avisar que falta peso
                console.warn("No se encontró el peso en el objeto user.");
            }

            // --- C. CALORÍAS ---
            calcularCalorias(user, pesoUsuario);
            
            // --- D. RUTINA ---
            actualizarRutina(today.getDay());

        } else if (response.status === 401) {
            window.location.href = '/login';
        }
    } catch (error) {
        console.error("Error cargando dashboard:", error);
    }
});

function calcularCalorias(user, peso) {
    // Valores por defecto si faltan datos para que no salga NaN
    const altura = user.height || user.altura || 175; 
    const edad = user.age || user.edad || 25;
    const genero = user.gender || user.sexo || 'male';
    
    // Si no hay peso, usamos uno promedio para el cálculo visual (pero mostramos -- en el texto)
    const pesoCalculo = peso || 75; 

    // Fórmula Mifflin-St Jeor
    let tdee = (10 * pesoCalculo) + (6.25 * altura) - (5 * edad);
    if (genero === 'male' || genero === 'hombre') tdee += 5;
    else tdee -= 161;

    // Factor actividad (sedentario/ligero por defecto)
    tdee = Math.round(tdee * 1.3);

    // Mostrar en pantalla
    const elementoMeta = document.getElementById('cal-goal');
    
    // Animación de conteo
    let start = 0;
    const duration = 1000;
    const step = timestamp => {
        if (!start) start = timestamp;
        const progress = Math.min((timestamp - start) / duration, 1);
        elementoMeta.textContent = Math.floor(progress * tdee);
        if (progress < 1) window.requestAnimationFrame(step);
    };
    window.requestAnimationFrame(step);

    // Llenar el círculo al 60% por defecto (simulación visual)
    setTimeout(() => {
        document.getElementById('cal-circle').setAttribute('stroke-dasharray', `60, 100`);
    }, 500);
}

function marcarDiaSemana() {
    const diasHTML = document.getElementById('week-days-container').children;
    const diaHoy = new Date().getDay(); // 0=Domingo, 1=Lunes
    
    // Ajuste: si es domingo (0), que sea el 7mo día visualmente o ignóralo
    const diaAjustado = diaHoy === 0 ? 7 : diaHoy;

    for (let i = 0; i < 5; i++) { // Solo L-V (índices 0-4)
        if (i + 1 < diaAjustado) diasHTML[i].classList.add('done');
        if (i + 1 === diaAjustado) diasHTML[i].classList.add('active');
    }
}

function actualizarRutina(dia) {
    const titulos = ["Descanso / Cardio", "Pecho & Tríceps", "Espalda & Bíceps", "Pierna & Glúteo", "Hombro & Abs", "Full Body", "Crossfit / HIIT"];
    const tags = ["Recuperación", "Fuerza A", "Fuerza B", "Leg Day", "Hipertrofia", "Quema Grasa", "Intensidad"];
    
    // dia va de 0 (domingo) a 6
    document.getElementById('workout-title').textContent = titulos[dia];
    document.getElementById('workout-tag').textContent = tags[dia];
}