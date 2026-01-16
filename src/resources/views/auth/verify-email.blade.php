<x-guest-layout>
    <div class="mb-4 text-sm text-[#616f89]">
        {{ __('¡Gracias por registrarte! Antes de comenzar, ¿podrías verificar tu dirección de correo haciendo clic en el enlace que te acabamos de enviar? Si no recibiste el correo, te enviaremos otro con gusto.') }}
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="mb-4 font-medium text-sm text-[#71a43d]">
            {{ __('Se ha enviado un nuevo enlace de verificación a la dirección de correo que proporcionaste durante el registro.') }}
        </div>
    @endif

    <div class="mt-4 flex flex-col items-center justify-center space-y-4">
        <form method="POST" action="{{ route('verification.send') }}" class="w-full">
            @csrf
            <div>
                <x-primary-button>
                    Reenviar Correo de Verificación
                </x-primary-button>
            </div>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="underline text-sm text-[#616f89] hover:text-[#111318] rounded-md focus:outline-none">
                Cerrar Sesión
            </button>
        </form>
    </div>
</x-guest-layout>