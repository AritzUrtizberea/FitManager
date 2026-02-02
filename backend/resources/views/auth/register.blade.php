<x-guest-layout>
    <a href="{{ url('/') }}" 
       style="position: absolute; top: 20px; left: 20px; background-color: white; border: 1px solid #d1d5db; padding: 10px; border-radius: 50%; display: flex; align-items: center; justify-content: center; text-decoration: none; box-shadow: 0 2px 5px rgba(0,0,0,0.05); z-index: 50; width: 45px; height: 45px;">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#374151" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M19 12H5M12 19l-7-7 7-7"/>
        </svg>
    </a>
    {{-- Lógica de validación con Alpine.js (TU CÓDIGO SIGUE IGUAL DEBAJO) --}}
    <div x-data="{
            paso: 1, 
            error: false, 
            mensajeError: '',
            
            // Función para validar el Paso 1 (Datos de cuenta)
            validarPaso1() {
                let name = document.getElementById('name').value;
                let surname = document.getElementById('surname').value;
                let email = document.getElementById('email').value;
                let pass = document.getElementById('password').value;
                let passC = document.getElementById('password_confirmation').value;

                // 1. Comprobar vacíos
                if (!name || !surname || !email || !pass || !passC) {
                    this.mensajeError = '⚠️ Por favor, rellena todos los campos obligatorios.';
                    this.error = true;
                    return false;
                }
                // 2. Comprobar contraseñas
                if (pass !== passC) {
                    this.mensajeError = '⚠️ Las contraseñas no coinciden.';
                    this.error = true;
                    return false;
                }
                
                // Si todo está bien:
                this.error = false;
                return true;
            },

            // Función para validar el Paso 2 (Datos físicos)
            validarPaso2() {
                let phone = document.getElementById('phone').value;
                let weight = document.getElementById('weight').value;
                let height = document.getElementById('height').value;

                if (!phone || !weight || !height) {
                    this.mensajeError = '⚠️ Completa tus datos físicos para seguir.';
                    this.error = true;
                    return false;
                }
                this.error = false;
                return true;
            }
         }" class="w-full">

        <form method="POST" action="{{ route('register') }}" class="w-full">
            @csrf

            <div x-show="paso === 1" x-transition:enter.duration.300ms>
                <br><h2 class="text-2xl font-bold text-[#111318] mb-4">Crear Cuenta</h2>

                <div class="flex items-center justify-between w-full mb-10 px-2">
                    <div class="flex items-center justify-center rounded-full text-white font-bold" style="background-color: #71a43d !important; width: 32px; height: 32px; flex: none; display: flex;">1</div>
                    <div class="flex-1 h-1 bg-gray-200 mx-2"></div>
                    <div class="flex items-center justify-center rounded-full text-white font-bold" style="background-color: #185a94 !important; width: 32px; height: 32px; flex: none; display: flex;">2</div>
                    <div class="flex-1 h-1 bg-gray-200 mx-2"></div>
                    <div class="flex items-center justify-center rounded-full text-white font-bold" style="background-color: #185a94 !important; width: 32px; height: 32px; flex: none; display: flex;">3</div>
                </div>

                <p class="text-[14px] text-[#616f89] mb-8 font-medium" style="margin-top: 30px !important;">Paso 1: Datos de Cuenta</p>

                <div class="grid grid-cols-2" style="display: grid !important; grid-template-columns: 1fr 1fr !important; column-gap: 20px !important;">
                    <div>
                        <x-input-label for="name" :value="__('Nombre')" />
                        <x-text-input id="name" class="block mt-2 w-full" type="text" name="name" :value="old('name')" required autofocus />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>
                    <div>
                        <x-input-label for="surname" :value="__('Apellido')" />
                        <x-text-input id="surname" class="block mt-2 w-full" type="text" name="surname" :value="old('surname')" required />
                        <x-input-error :messages="$errors->get('surname')" class="mt-2" />
                    </div>
                </div>

                <div style="margin-top: 30px !important;">
                    <x-input-label for="email" :value="__('Correo Electrónico')" />
                    <x-text-input id="email" class="block mt-2 w-full" type="email" name="email" :value="old('email')" required />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <div class="grid grid-cols-2" style="margin-top: 30px !important; display: grid !important; grid-template-columns: 1fr 1fr !important; column-gap: 20px !important;">
                    <div>
                        <x-input-label for="password" :value="__('Contraseña')" />
                        <x-text-input id="password" class="block mt-2 w-full" type="password" name="password" required />
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>
                    <div>
                        <x-input-label for="password_confirmation" :value="__('Confirmar')" />
                        <x-text-input id="password_confirmation" class="block mt-2 w-full" type="password" name="password_confirmation" required />
                        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                    </div>
                </div>

                <div class="mt-6 text-center border-t border-gray-100 pt-4">
                    <span class="text-sm text-gray-600">¿Ya tienes cuenta?</span>
                    <a href="{{ route('login') }}" class="ml-1 text-sm text-indigo-600 hover:text-indigo-900 font-bold hover:underline">Inicia sesión</a>
                </div>

                <div x-show="error" class="mt-4 p-3 bg-red-50 text-red-600 text-sm rounded-lg border border-red-200 text-center font-bold" x-text="mensajeError" style="display: none;"></div>

                <div style="margin-top: 20px !important;"> 
                    <button type="button" 
                            @click="if(validarPaso1()){ paso = 2 }" 
                            class="w-full text-white font-bold py-2 rounded-[12px] h-[45px] transition-all active:scale-[0.98]" 
                            style="background-color: #185a94 !important;">
                        SIGUIENTE
                    </button>
                </div>
            </div>

            <div x-show="paso === 2" x-cloak x-transition:enter.duration.300ms>
                <h2 class="text-2xl font-bold text-[#111318] mb-4">Perfil Fitness</h2>

                <div class="flex items-center justify-between w-full mb-10 px-2">
                    <div class="flex items-center justify-center rounded-full text-white font-bold" style="background-color: #71a43d !important; width: 32px; height: 32px; flex: none; display: flex;">1</div>
                    <div class="flex-1 h-1" style="background-color: #71a43d !important;"></div>
                    <div class="flex items-center justify-center rounded-full text-white font-bold" style="background-color: #71a43d !important; width: 32px; height: 32px; flex: none; display: flex;">2</div>
                    <div class="flex-1 h-1 bg-gray-200 mx-2"></div>
                    <div class="flex items-center justify-center rounded-full text-white font-bold" style="background-color: #185a94 !important; width: 32px; height: 32px; flex: none; display: flex;">3</div>
                </div>

                <p class="text-[14px] text-[#616f89] mb-8 font-medium" style="margin-top: 40px !important;">Paso 2: Datos Físicos</p>

                <div class="grid grid-cols-2" style="display: grid !important; grid-template-columns: 1fr 1fr !important; column-gap: 20px !important;">
                    <div>
                        <x-input-label for="sex" :value="__('Sexo')" />
                        <select name="sex" id="sex" class="block mt-2 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                            <option value="Hombre" {{ old('sex') == 'Hombre' ? 'selected' : '' }}>Hombre</option>
                            <option value="Mujer" {{ old('sex') == 'Mujer' ? 'selected' : '' }}>Mujer</option>    
                        </select>
                        <x-input-error :messages="$errors->get('sex')" class="mt-2" />
                    </div>
                    <div>
                        <x-input-label for="phone" :value="__('Teléfono')" />
                        <x-text-input id="phone" class="block mt-2 w-full" type="tel" name="phone" :value="old('phone')" placeholder="600000000" required />
                        <x-input-error :messages="$errors->get('phone')" class="mt-2" />
                    </div>
                </div>

                <div class="grid grid-cols-2" style="margin-top: 30px !important; display: grid !important; grid-template-columns: 1fr 1fr !important; column-gap: 40px !important;">
                    <div>
                        <x-input-label for="weight" :value="__('Peso (kg)')" />
                        <x-text-input id="weight" name="weight" :value="old('weight')" class="block mt-2 w-full" type="number" step="0.1" placeholder="75" required />
                        <x-input-error :messages="$errors->get('weight')" class="mt-2" />
                    </div>
                    <div>
                        <x-input-label for="height" :value="__('Altura (cm)')" />
                        <x-text-input id="height" name="height" :value="old('height')" class="block mt-2 w-full" type="number" placeholder="180" required />
                        <x-input-error :messages="$errors->get('height')" class="mt-2" />
                    </div>
                </div>

                 <div x-show="error" class="mt-4 p-3 bg-red-50 text-red-600 text-sm rounded-lg border border-red-200 text-center font-bold" x-text="mensajeError" style="display: none;"></div>

                <div class="flex items-center w-full space-x-4" style="margin-top: 30px !important;">
                    <button type="button" @click="paso = 1; error = false" class="flex-1 text-[#616f89] font-bold py-2 rounded-[12px] h-[45px] border border-gray-300 hover:bg-gray-50">ATRÁS</button>
                    
                    <button type="button" @click="if(validarPaso2()){ paso = 3 }" class="flex-1 text-white font-bold py-2 rounded-[12px] h-[45px]" style="background-color: #185a94 !important;">SIGUIENTE</button>
                </div>
            </div>

            <div x-show="paso === 3" x-cloak x-transition:enter.duration.300ms>
                <h2 class="text-2xl font-bold text-[#111318] mb-4">Nivel de Actividad</h2>

                <div class="flex items-center justify-between w-full mb-10 px-2">
                    <div class="flex items-center justify-center rounded-full text-white font-bold" style="background-color: #71a43d !important; width: 32px; height: 32px; flex: none; display: flex;">1</div>
                    <div class="flex-1 h-1" style="background-color: #71a43d !important;"></div>
                    <div class="flex items-center justify-center rounded-full text-white font-bold" style="background-color: #71a43d !important; width: 32px; height: 32px; flex: none; display: flex;">2</div>
                    <div class="flex-1 h-1" style="background-color: #71a43d !important;"></div>
                    <div class="flex items-center justify-center rounded-full text-white font-bold" style="background-color: #71a43d !important; width: 32px; height: 32px; flex: none; display: flex;">3</div>
                </div>

                <div class="space-y-6" style="margin-top: 30px !important;">
                    
                    <label class="flex items-center justify-between p-4 border rounded-[12px] cursor-pointer hover:border-[#185a94] transition-all shadow-sm">
                        <div class="flex items-center">
                            <input type="radio" name="activity" value="baja" class="text-[#185a94] focus:ring-[#185a94]" required>
                            <div class="ml-4">
                                <span class="block font-bold text-sm text-[#111318]">Poco o ningún ejercicio</span>
                                <span class="text-xs text-[#616f89] italic">Principalmente sentado (ej. oficina)</span>
                            </div>
                        </div>
                        <span class="text-2xl">🪑</span>
                    </label>

                    <label class="flex items-center justify-between p-4 border rounded-[12px] cursor-pointer hover:border-[#185a94] transition-all shadow-sm">
                        <div class="flex items-center">
                            <input type="radio" name="activity" value="ligera" class="text-[#185a94] focus:ring-[#185a94]">
                            <div class="ml-4">
                                <span class="block font-bold text-sm text-[#111318]">Ejercicio ligero</span>
                                <span class="text-xs text-[#616f89] italic">1-3 días a la semana</span>
                            </div>
                        </div>
                        <span class="text-2xl">🏡</span>
                    </label>

                    <label class="flex items-center justify-between p-4 border rounded-[12px] cursor-pointer hover:border-[#185a94] transition-all shadow-sm">
                        <div class="flex items-center">
                            <input type="radio" name="activity" value="moderada" class="text-[#185a94] focus:ring-[#185a94]">
                            <div class="ml-4">
                                <span class="block font-bold text-sm text-[#111318]">Moderado</span>
                                <span class="text-xs text-[#616f89] italic">3-5 días a la semana</span>
                            </div>
                        </div>
                        <span class="text-2xl">🏃</span>
                    </label>

                    <label class="flex items-center justify-between p-4 border rounded-[12px] cursor-pointer hover:border-[#185a94] transition-all shadow-sm">
                        <div class="flex items-center">
                            <input type="radio" name="activity" value="alta" class="text-[#185a94] focus:ring-[#185a94]">
                            <div class="ml-4">
                                <span class="block font-bold text-sm text-[#111318]">Fuerte</span>
                                <span class="text-xs text-[#616f89] italic">6-7 días a la semana</span>
                            </div>
                        </div>
                        <span class="text-2xl">⚡</span>
                    </label>
                    <x-input-error :messages="$errors->get('activity')" class="mt-2" />
                </div>

                <div style="margin-top: 50px !important;"> 
                    <button type="submit" class="w-full text-white font-bold py-2 rounded-[12px] h-[50px]" style="background-color: #185a94 !important;">
                        REGISTRARSE
                    </button>
                    <div style="margin-top: 20px !important; text-align: center;">
                        <button type="button" @click="paso = 2; error = false" class="text-[#616f89] text-sm underline font-bold">Volver al paso anterior</button>
                    </div>
                </div>
            </div>

        </form>
    </div>
</x-guest-layout>