<x-guest-layout>
    {{-- CORRECCIÓN 1: Botón de atrás accesible --}}
    <a href="{{ url('/') }}" 
       aria-label="Volver al inicio"
       style="position: absolute; top: 20px; left: 20px; background-color: white; border: 1px solid #d1d5db; padding: 10px; border-radius: 50%; display: flex; align-items: center; justify-content: center; text-decoration: none; box-shadow: 0 2px 5px rgba(0,0,0,0.05); z-index: 50; width: 45px; height: 45px;">
        <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#374151" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M19 12H5M12 19l-7-7 7-7"/>
        </svg>
    </a>

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="w-full">
        @csrf

        {{-- TÍTULO AÑADIDO: Para consistencia con el Registro --}}
        <br><h2 class="text-2xl font-bold text-[#111318] mb-8">Iniciar Sesión</h2>

        <div>
            <x-input-label for="email" :value="__('Correo Electrónico')" />
            <x-text-input id="email" class="block mt-2 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="mt-6">
            <x-input-label for="password" :value="__('Contraseña')" />
            <x-text-input id="password" class="block mt-2 w-full" type="password" name="password" required autocomplete="current-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        {{-- BOTÓN ESTILIZADO: Coherente con la página de registro --}}
        <div style="margin-top: 30px !important;">
            <button type="submit" 
                    class="w-full text-white font-bold py-2 rounded-[12px] h-[45px] transition-all active:scale-[0.98]" 
                    style="background-color: #185a94 !important;">
                INICIAR SESIÓN
            </button>
        </div>

        <div class="mt-6 text-center border-t border-gray-100 pt-4">
            <span class="text-sm text-gray-600">¿No tienes cuenta?</span>
            <a href="{{ route('register') }}" class="ml-1 text-sm text-indigo-600 hover:text-indigo-900 font-bold hover:underline">
                Regístrate aquí
            </a>
        </div>

    </form>
</x-guest-layout>