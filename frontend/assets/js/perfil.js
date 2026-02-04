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
        streak: document.getElementById('profile-streak'), // <--- AQUÍ VA EL FUEGO 🔥

        avatarPlaceholder: document.getElementById('avatar-placeholder'),
        avatarImage: document.getElementById('avatar-image')
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
    };

    const loadUserData = async () => {
        showLoaders(); 

        try {
            // AHORA ESTA RUTA DEVUELVE EL USUARIO + PERFIL (Gracias a tu cambio en web.php)
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

                // --- CHIVATO PARA VER SI LLEGA LA RACHA ---
                console.log("📡 DATOS RECIBIDOS DEL BACKEND:", data); 

                // --- TEXTOS ---
                if (els.name) els.name.textContent = data.name || 'Usuario';
                
                // AQUÍ ES DONDE OCURRE LA MAGIA
                if (data.profile) {
                    console.log("🔥 Racha detectada:", data.profile.streak);
                    
                    if (els.weight) els.weight.textContent = data.profile.weight || '--';
                    if (els.height) els.height.textContent = data.profile.height || '--';
                    // Pintamos la racha (si es null pone 0)
                    if (els.streak) els.streak.textContent = data.profile.streak !== null ? data.profile.streak : '0';
                } else {
                    console.warn("⚠️ El usuario no tiene perfil creado todavía.");
                    if (els.streak) els.streak.textContent = '0';
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

    // --- 4. MODAL LOGOUT ---
    const showModal = () => { if(els.modal) els.modal.style.display = 'flex'; };
    const hideModal = () => { if(els.modal) els.modal.style.display = 'none'; };
    
    const performLogout = () => {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '/logout'; // Normalmente es /logout en web, o /api/logout si es SPA pura
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
    // --- 5. BLOQUEO DE FOTOS GIGANTES ---
    // ============================================================
    
    // VALIDACIÓN A: Aviso inmediato
    const fileInput = document.getElementById('avatar-input');
    if (fileInput) {
        fileInput.addEventListener('change', function() {
            if (this.files[0] && this.files[0].size > 1048576) { // 1MB
                alert("⚠️ La imagen es muy pesada (Máximo 1MB).");
                this.value = ''; 
            }
        });
    }

    // VALIDACIÓN B: Bloqueo de subida
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