@extends('layouts.app')

@section('content')
<style>
    /* Estilos para el Logout (Lo que ya tenías) */
    .btn-logout-clean {
        all: unset;
        width: 100%;
        display: block;
        cursor: pointer;
        background: white;
    }

    .logout-modal-overlay {
        position: fixed;
        top: 0; left: 0;
        width: 100%; height: 100%;
        background: rgba(0,0,0,0.5);
        display: none;
        justify-content: center;
        align-items: center;
        z-index: 9999;
    }

    .logout-modal-box {
        background: white;
        padding: 30px;
        border-radius: 15px;
        width: 80%;
        max-width: 320px;
        text-align: center;
    }
</style>

<div class="container py-4">
    
    <div class="profile-header text-center mb-5">
        <div class="avatar-container" style="width: 105px; height: 105px; margin: 0 auto 15px; position: relative;">
            
            @if(auth()->user()->profile_photo_url)
                <img src="{{ auth()->user()->profile_photo_url }}?v={{ time() }}" 
                     alt="Foto de perfil"
                     style="
                        width: 100%;
                        height: 100%;
                        border-radius: 50%;
                        object-fit: cover;
                        box-shadow: 0 8px 20px rgba(59, 130, 246, 0.15);
                        border: 4px solid white;
                        background-color: #f1f5f9;
                     ">
            @else
                <div class="d-flex align-items-center justify-content-center bg-light text-primary fw-bold" 
                     style="
                        width: 100%;
                        height: 100%;
                        border-radius: 50%;
                        font-size: 40px; 
                        border: 4px solid white;
                        box-shadow: 0 8px 20px rgba(59, 130, 246, 0.15);
                     ">
                    {{ substr(auth()->user()->name, 0, 1) }}
                </div>
            @endif
        </div>

        <h5 class="fw-bold m-0">{{ auth()->user()->name }}</h5>
        <p class="text-muted fw-medium small m-0">{{ auth()->user()->email }}</p>
        
        <a href="{{ route('profile.edit') }}" class="btn btn-sm btn-outline-primary mt-3 rounded-pill px-4">
            Editar Perfil
        </a>
    </div>

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <button type="button" id="triggerLogout" class="btn-logout-clean">
            <div style="display: flex; justify-content: space-between; padding: 18px 20px; border-bottom: 1px solid #eee;">
                <span style="color: #d32f2f; font-weight: bold;">Cerrar Sesión</span>
                <i class="ph ph-caret-right" style="color: #d32f2f;"></i>
            </div>
        </button>
    </div>

</div> <div id="modalLogout" class="logout-modal-overlay">
    <div class="logout-modal-box">
        <h3 style="margin-top:0;">¿Cerrar Sesión?</h3>
        <p style="color: #666;">¿Estás seguro de que quieres salir?</p>
        <div style="display: flex; gap: 10px; margin-top: 20px;">
            <button id="cancelarLogout" style="flex:1; padding:12px; border:1px solid #ddd; background:none; border-radius:8px;">CANCELAR</button>
            <button id="confirmarLogout" style="flex:1; padding:12px; background:#d32f2f; color:white; border:none; border-radius:8px; font-weight:bold;">SÍ, SALIR</button>
        </div>
    </div>
</div>

<form id="form-logout-back" action="{{ route('logout') }}" method="POST" style="display: none;">
    @csrf
</form>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const trigger = document.getElementById('triggerLogout');
    const modal = document.getElementById('modalLogout');
    const btnCancel = document.getElementById('cancelarLogout');
    const btnConfirm = document.getElementById('confirmarLogout');
    const realForm = document.getElementById('form-logout-back');

    if (trigger) {
        trigger.onclick = () => modal.style.display = 'flex';
    }

    if (btnCancel) {
        btnCancel.onclick = () => modal.style.display = 'none';
    }

    if (btnConfirm) {
        btnConfirm.onclick = () => realForm.submit();
    }
});
</script>
@endsection