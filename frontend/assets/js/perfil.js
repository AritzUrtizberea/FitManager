document.addEventListener("DOMContentLoaded", () => {
    
    console.log("✅ JS Cargado: Animaciones + Validación activas.");

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

        avatarPlaceholder: document.getElementById('avatar-placeholder'),
        avatarImage: document.getElementById('avatar-image')
    };

    // --- 2. HTML DEL LOADER (RESTAURADO) ---
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

    // --- 3. FUNCIONES DE CARGA (RESTAURADO) ---
    const showLoaders = () => {
        if(els.name) els.name.innerHTML = getLoaderHTML(false);
        if(els.weight) els.weight.innerHTML = getLoaderHTML(true);
        if(els.height) els.height.innerHTML = getLoaderHTML(true);
        if(els.streak) els.streak.innerHTML = getLoaderHTML(true);
    };

    const loadUserData = async () => {
        showLoaders(); // Mostramos las bolitas al empezar

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

                // --- TEXTOS ---
                if (els.name) els.name.textContent = data.name || 'Usuario';
                
                if (data.profile) {
                    if (els.weight) els.weight.textContent = data.profile.weight || '--';
                    if (els.height) els.height.textContent = data.profile.height || '--';
                    if (els.streak) els.streak.textContent = data.profile.streak || '0';
                }

                // --- IMAGEN ---
                const photoPath = data.profile_photo_path; 

                if (photoPath) {
                    // Timestamp para evitar caché antigua de la imagen
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
            console.error('Error:', error);
            if (els.name) els.name.textContent = 'Error';
        }
    };

    // --- 4. MODAL LOGOUT ---
    const showModal = () => { if(els.modal) els.modal.style.display = 'flex'; };
    const hideModal = () => { if(els.modal) els.modal.style.display = 'none'; };
    
    const performLogout = () => {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '/api/logout';
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

    // ============================================================
    // --- 5. BLOQUEO DE FOTOS GIGANTES (EL "GUARDAESPALDAS") ---
    // ============================================================
    
    // VALIDACIÓN A: Aviso inmediato al elegir archivo
    const fileInput = document.getElementById('avatar-input');
    if (fileInput) {
        fileInput.addEventListener('change', function() {
            if (this.files[0] && this.files[0].size > 1048576) { // 1MB
                alert("⚠️ La imagen es muy pesada (Máximo 1MB).");
                this.value = ''; // Borramos para intentar evitar el envío
            }
        });
    }

    // VALIDACIÓN B: Bloqueo TOTAL del clic "Guardar" (Fase de Captura)
    document.addEventListener('click', function(e) {
        const input = document.getElementById('avatar-input');
        
        // Si hay una imagen seleccionada Y pesa más de 1MB...
        if (input && input.files && input.files[0] && input.files[0].size > 1048576) {
            
            // Comprobamos si el clic fue en un botón
            const targetBtn = e.target.closest('button, input[type="submit"], .btn');

            if (targetBtn) {
                // Ignoramos si es el botón de cancelar
                if (targetBtn.id !== 'cancelarLogout' && !targetBtn.classList.contains('btn-cancel')) {
                    
                    // ¡ALTO AHÍ!
                    e.preventDefault(); 
                    e.stopPropagation();
                    e.stopImmediatePropagation();

                    alert("⛔ NO SE PUEDE GUARDAR\n\nLa imagen supera el límite de 1MB.\nPor favor, elige una más pequeña.");
                    
                    input.value = ''; // Borramos la imagen culpable
                    return false;
                }
            }
        }
    }, true); // <--- El 'true' es la clave para interceptar el clic antes que nadie.


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