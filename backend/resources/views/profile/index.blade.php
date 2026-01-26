@extends('layouts.app')

@section('content')
<style>
    .btn-logout-clean {
        all: unset;
        width: 100%;
        display: block;
        cursor: pointer;
        background: white;
    }

    .logout-modal-overlay {
        position: fixed; /* Esto hace que flote sobre todo */
        top: 0; left: 0;
        width: 100%; height: 100%;
        background: rgba(0,0,0,0.5);
        display: none; /* Oculto al inicio */
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

<button type="button" id="triggerLogout" class="btn-logout-clean">
    <div style="display: flex; justify-content: space-between; padding: 18px 20px; border-bottom: 1px solid #eee;">
        <span style="color: #d32f2f; font-weight: bold;">Cerrar Sesión</span>
        <i class="ph ph-caret-right" style="color: #d32f2f;"></i>
    </div>
</button>

<div id="modalLogout" class="logout-modal-overlay">
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

    // Evitamos el error de "null" comprobando si el botón existe
    if (trigger) {
        trigger.onclick = () => modal.style.display = 'flex';
    }

    if (btnCancel) {
        btnCancel.onclick = () => modal.style.display = 'none';
    }

    if (btnConfirm) {
        btnConfirm.onclick = () => realForm.submit(); // Dispara la ruta /logout hacia Nginx
    }
});
</script>
@endsection