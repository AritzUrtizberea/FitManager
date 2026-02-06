document.addEventListener('DOMContentLoaded', () => {
    // 1. Array con los IDs de tus 3 botones en orden
    const botones = ['btn-config', 'btn-start', 'btn-quick'];
    
    document.addEventListener('keydown', (e) => {
        const active = document.activeElement;
        
        // Si pulsamos Flecha ABAJO
        if (e.key === 'ArrowDown') {
            e.preventDefault();
            
            // Si el foco no está en ninguno, vamos al primero
            if (!botones.includes(active.id)) {
                document.getElementById(botones[0]).focus();
            } else {
                // Buscamos cuál es el actual y vamos al siguiente
                const currentIndex = botones.indexOf(active.id);
                if (currentIndex < botones.length - 1) {
                    document.getElementById(botones[currentIndex + 1]).focus();
                }
            }
        }

        // Si pulsamos Flecha ARRIBA
        else if (e.key === 'ArrowUp') {
            e.preventDefault();
            
            // Si el foco no está en ninguno, vamos al último
            if (!botones.includes(active.id)) {
                document.getElementById(botones[botones.length - 1]).focus();
            } else {
                // Buscamos cuál es el actual y vamos al anterior
                const currentIndex = botones.indexOf(active.id);
                if (currentIndex > 0) {
                    document.getElementById(botones[currentIndex - 1]).focus();
                }
            }
        }
    });

    // Opcional: Poner el foco en el primer botón al cargar la página
    // document.getElementById(botones[0]).focus();
});