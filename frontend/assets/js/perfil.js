document.addEventListener("DOMContentLoaded", () => {
    
    console.log("✅ JS Cargado: Listo para conectar con Laravel.");

    // --- 1. REFERENCIAS AL DOM ---
    const els = {
        modal: document.getElementById('modalLogout'),
        btnTrigger: document.getElementById('triggerLogout'),
        btnCancel: document.getElementById('cancelarLogout'),
        btnConfirm: document.getElementById('confirmarLogout'),
        
        name: document.getElementById('profile-name'),
        weight: document.getElementById('profile-weight'),
        height: document.getElementById('profile-height'),
        streak: document.getElementById('profile-streak'),
        
        // AÑADIDO: Referencia al campo de actividad
        activity: document.getElementById('profile-activity'), 

        avatarPlaceholder: document.getElementById('avatar-placeholder'),
        avatarImage: document.getElementById('avatar-image')
    };

    // Diccionario para traducir lo que viene de la BD a texto bonito
    const activityMap = {
            // Valores del Registro (Español)
            'baja': 'Sedentario (Poco ejercicio)',
            'ligera': 'Ligero (1-3 días)',
            'moderada': 'Moderado (3-5 días)',
            'alta': 'Fuerte (6-7 días)',

            // Valores de la Edición (Inglés) -> ¡ESTOS FALTABAN!
            'sedentary': 'Sedentario (Poco ejercicio)',
            'light': 'Ligero (1-3 días)',
            'moderate': 'Moderado (3-5 días)',
            'active': 'Activo (6-7 días)',
            'very_active': 'Muy Activo (Doble sesión)'
        };

    // --- 2. HTML DEL LOADER ---
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

    // --- 3. FUNCIONES DE CARGA ---
    const showLoaders = () => {
        if(els.name) els.name.innerHTML = getLoaderHTML(false);
        if(els.weight) els.weight.innerHTML = getLoaderHTML(true);
        if(els.height) els.height.innerHTML = getLoaderHTML(true);
        if(els.streak) els.streak.innerHTML = getLoaderHTML(true);
        if(els.activity) els.activity.innerHTML = getLoaderHTML(true); // AÑADIDO
    };

    const loadUserData = async () => {
        showLoaders(); 

        try {
            const response = await fetch('/api/user', {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                }
            });

            if (response.status === 401) {
                window.location.href = '/login';
                return;
            }

            if (response.ok) {
                const data = await response.json();
                console.log("📡 DATOS RECIBIDOS:", data); 

                // --- TEXTOS BÁSICOS ---
                if (els.name) els.name.textContent = data.name || 'Usuario';
                
                // --- DATOS DEL PERFIL ---
                if (data.profile) {
                    if (els.weight) els.weight.textContent = data.profile.weight || '--';
                    if (els.height) els.height.textContent = data.profile.height || '--';
                    if (els.streak) els.streak.textContent = data.profile.streak !== null ? data.profile.streak : '0';
                    
                    // AÑADIDO: Lógica para mostrar la actividad
                    if (els.activity) {
                        // Buscamos el valor en el diccionario, si no existe ponemos el original
                        const rawActivity = data.profile.activity_level; // Asegúrate que en BD se llama activity_level
                        els.activity.textContent = activityMap[rawActivity] || rawActivity || 'No definido';
                    }

                } else {
                    console.warn("⚠️ El usuario no tiene perfil creado todavía.");
                    if (els.streak) els.streak.textContent = '0';
                    if (els.activity) els.activity.textContent = '--';
                }

                // --- IMAGEN ---
                const photoPath = data.profile_photo_path; 
                if (photoPath) {
                    const imgUrl = `/storage/${photoPath}?t=${new Date().getTime()}`;
                    if (els.avatarImage) {
                        els.avatarImage.src = imgUrl;
                        els.avatarImage.onload = () => {
                            if(els.avatarPlaceholder) els.avatarPlaceholder.style.display = 'none';
                            els.avatarImage.style.display = 'block';
                        };
                        els.avatarImage.onerror = () => {
                            if(els.avatarPlaceholder) els.avatarPlaceholder.style.display = 'flex';
                            els.avatarImage.style.display = 'none';
                        };
                    }
                } else {
                    if(els.avatarPlaceholder) els.avatarPlaceholder.style.display = 'flex';
                    if(els.avatarImage) els.avatarImage.style.display = 'none';
                }
            }
        } catch (error) {
            console.error('Error cargando datos:', error);
            if (els.name) els.name.textContent = 'Error';
        }
    };

    // ... (RESTO DEL CÓDIGO IGUAL: MODAL LOGOUT, VALIDACIÓN FOTOS, ETC.) ...
    // --- 4. MODAL LOGOUT ---
    const showModal = () => { if(els.modal) els.modal.style.display = 'flex'; };
    const hideModal = () => { if(els.modal) els.modal.style.display = 'none'; };
    
    const performLogout = () => {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '/logout'; 
        const token = document.querySelector('meta[name="csrf-token"]');
        if(token) {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = '_token';
            input.value = token.content;
            form.appendChild(input);
        }
        document.body.appendChild(form);
        form.submit();
    };

    // --- 5. BLOQUEO DE FOTOS GIGANTES ---
    const fileInput = document.getElementById('avatar-input');
    if (fileInput) {
        fileInput.addEventListener('change', function() {
            if (this.files[0] && this.files[0].size > 1048576) { 
                alert("⚠️ La imagen es muy pesada (Máximo 1MB).");
                this.value = ''; 
            }
        });
    }

    document.addEventListener('click', function(e) {
        const input = document.getElementById('avatar-input');
        if (input && input.files && input.files[0] && input.files[0].size > 1048576) {
            const targetBtn = e.target.closest('button, input[type="submit"], .btn');
            if (targetBtn) {
                if (targetBtn.id !== 'cancelarLogout' && !targetBtn.classList.contains('btn-cancel')) {
                    e.preventDefault(); 
                    e.stopPropagation();
                    e.stopImmediatePropagation();
                    alert("⛔ NO SE PUEDE GUARDAR\n\nLa imagen supera el límite de 1MB.");
                    input.value = ''; 
                    return false;
                }
            }
        }
    }, true);


    // --- 6. EVENT LISTENERS ---
    if (els.btnTrigger) els.btnTrigger.addEventListener('click', showModal);
    if (els.btnCancel) els.btnCancel.addEventListener('click', hideModal);
    if (els.btnConfirm) els.btnConfirm.addEventListener('click', performLogout);
    if (els.modal) els.modal.addEventListener('click', (e) => {
        if (e.target === els.modal) hideModal();
    });

    // --- 7. INICIO ---
    loadUserData();
});