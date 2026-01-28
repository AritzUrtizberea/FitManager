document.addEventListener("DOMContentLoaded", () => {
    
    // --- 1. REFERENCIAS AL DOM ---
    const els = {
        modal: document.getElementById('modalLogout'),
        btnTrigger: document.getElementById('triggerLogout'),
        btnCancel: document.getElementById('cancelarLogout'),
        btnConfirm: document.getElementById('confirmarLogout'),
        
        // Elementos donde cargaremos datos
        name: document.getElementById('profile-name'),
        weight: document.getElementById('profile-weight'),
        height: document.getElementById('profile-height'),
        streak: document.getElementById('profile-streak')
    };

    // --- 2. HTML DEL LOADER ---
    // Creamos dos versiones: normal y mini
    const getLoaderHTML = (isMini = false) => {
        const miniClass = isMini ? 'mini' : '';
        return `
            <div class="fm-loader-container ${miniClass}">
                <div class="fm-dot"></div>
                <div class="fm-dot"></div>
                <div class="fm-dot"></div>
            </div>
        `;
    };

    // --- 3. FUNCIONES ---

    // Mostrar loaders en todos los campos antes de cargar
    const showLoaders = () => {
        if(els.name) els.name.innerHTML = getLoaderHTML(false); // Loader normal
        if(els.weight) els.weight.innerHTML = getLoaderHTML(true); // Loader mini
        if(els.height) els.height.innerHTML = getLoaderHTML(true); // Loader mini
        if(els.streak) els.streak.innerHTML = getLoaderHTML(true); // Loader mini
    };

    const loadUserData = async () => {
        // 1. Mostrar animación inmediatamente
        showLoaders();

        try {
            // Simulamos un pequeño retraso para que se aprecie la animación (opcional, puedes quitarlo)
            // await new Promise(r => setTimeout(r, 800)); 

            const response = await fetch('/api/user', {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                }
            });

            if (response.status === 401) {
                window.location.href = '/login';
                return;
            }

            if (response.ok) {
                const data = await response.json();

                // 2. Reemplazar animación con datos reales
                // Usamos textContent para limpiar el HTML del loader
                if (els.name) els.name.textContent = data.name || 'Usuario';
                
                if (data.profile) {
                    if (els.weight) els.weight.textContent = data.profile.weight || '--';
                    if (els.height) els.height.textContent = data.profile.height || '--';
                    
                    // Lógica para la Racha (Streak)
                    // Si no tienes la racha funcional, mostrará 0
                    if (els.streak) els.streak.textContent = data.profile.streak || '0';
                }
            }
        } catch (error) {
            console.error('Error cargando datos:', error);
            // En caso de error, quitamos el loader y ponemos guiones
            if (els.name) els.name.textContent = 'Error';
            if (els.weight) els.weight.textContent = '--';
        }
    };

    // Lógica del Modal (igual que antes)
    const showModal = () => { if(els.modal) els.modal.style.display = 'flex'; };
    const hideModal = () => { if(els.modal) els.modal.style.display = 'none'; };
    const performLogout = () => {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '/api/logout';
        document.body.appendChild(form);
        form.submit();
    };

    // --- 4. EVENT LISTENERS ---
    if (els.btnTrigger) els.btnTrigger.addEventListener('click', showModal);
    if (els.btnCancel) els.btnCancel.addEventListener('click', hideModal);
    if (els.btnConfirm) els.btnConfirm.addEventListener('click', performLogout);
    if (els.modal) els.modal.addEventListener('click', (e) => {
        if (e.target === els.modal) hideModal();
    });

    // --- 5. INICIALIZAR ---
    loadUserData();
});