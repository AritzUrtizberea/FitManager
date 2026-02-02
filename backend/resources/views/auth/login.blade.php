<x-guest-layout>
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div>
            <x-input-label for="email" :value="__('Correo Electrónico')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="password" :value="__('Contraseña')" />
            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="current-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="flex flex-col items-center justify-center mt-4">
            <x-primary-button class="mb-6">
                Iniciar Sesión
            </x-primary-button>

            <div class="mt-6 text-center border-t border-gray-100 pt-4">
                <span class="text-sm text-gray-600">¿No tienes cuenta?</span>
                <a href="{{ route('register') }}" class="ml-1 text-sm text-indigo-600 hover:text-indigo-900 font-bold hover:underline">
                    Regístrate aquí
                </a>
            </div>

        </div>
    </form>
</x-guest-layout>