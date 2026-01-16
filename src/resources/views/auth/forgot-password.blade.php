<x-guest-layout>
    <div class="mb-4 text-sm text-[#616f89]">
        {{ __('¿Olvidaste tu contraseña? No hay problema. Solo dinos tu dirección de correo electrónico y te enviaremos un enlace para restablecerla que te permitirá elegir una nueva.') }}
    </div>

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <div>
            <x-input-label for="email" :value="__('Correo Electrónico')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="flex items-center justify-center mt-6">
            <x-primary-button class="w-full">
                {{ __('Enviar enlace de restablecimiento') }}
            </x-primary-button>
        </div>
        
        <div class="flex justify-center mt-4">
            <a href="{{ route('login') }}" class="underline text-sm text-[#616f89] hover:text-[#111318]">
                Volver al inicio de sesión
            </a>
        </div>
    </form>
</x-guest-layout>