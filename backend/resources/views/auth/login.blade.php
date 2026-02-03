<x-guest-layout>
    <a href="{{ url('/') }}" 
       style="position: absolute; top: 20px; left: 20px; background-color: white; border: 1px solid #d1d5db; padding: 10px; border-radius: 50%; display: flex; align-items: center; justify-content: center; text-decoration: none; box-shadow: 0 2px 5px rgba(0,0,0,0.05); z-index: 50; width: 45px; height: 45px;">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#374151" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M19 12H5M12 19l-7-7 7-7"/>
        </svg>
    </a>
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