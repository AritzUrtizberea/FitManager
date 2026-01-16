<x-guest-layout>
    <div x-data="{ paso: 1 }" class="w-full">
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
                    </div>
                    <div>
                        <x-input-label for="surname" :value="__('Apellido')" />
                        <x-text-input id="surname" class="block mt-2 w-full" type="text" name="surname" :value="old('surname')" required />
                    </div>
                </div>

                <div style="margin-top: 30px !important;">
                    <x-input-label for="email" :value="__('Correo Electrónico')" />
                    <x-text-input id="email" class="block mt-2 w-full" type="email" name="email" :value="old('email')" required />
                </div>

                <div class="grid grid-cols-2" style="margin-top: 30px !important; display: grid !important; grid-template-columns: 1fr 1fr !important; column-gap: 20px !important;">
                    <div>
                        <x-input-label for="password" :value="__('Contraseña')" />
                        <x-text-input id="password" class="block mt-2 w-full" type="password" name="password" required />
                    </div>
                    <div>
                        <x-input-label for="password_confirmation" :value="__('Confirmar')" />
                        <x-text-input id="password_confirmation" class="block mt-2 w-full" type="password" name="password_confirmation" required />
                    </div>
                </div>

                <div style="margin-top: 50px !important;"> 
                    <button type="button" @click="paso = 2" class="w-full text-white font-bold py-2 rounded-[12px] h-[45px] transition-all active:scale-[0.98]" style="background-color: #185a94 !important;">
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
                        <select name="sex" class="block mt-2 w-full border-gray-300 focus:border-[#185a94] rounded-[12px] h-[42px]">
                            <option value="male">Hombre</option>
                            <option value="female">Mujer</option>
                        </select>
                    </div>
                    <div>
                        <x-input-label for="phone" :value="__('Teléfono')" />
                        <x-text-input id="phone" class="block mt-2 w-full" type="tel" name="phone" placeholder="600000000" />
                    </div>
                </div>

                <div class="grid grid-cols-2" style="margin-top: 30px !important; display: grid !important; grid-template-columns: 1fr 1fr !important; column-gap: 40px !important;">
                    <div>
                        <x-input-label for="weight" :value="__('Peso (kg)')" />
                        <x-text-input id="weight" name="weight" class="block mt-2 w-full" type="number" placeholder="75" />
                    </div>
                    <div>
                        <x-input-label for="height" :value="__('Altura (cm)')" />
                        <x-text-input id="height" name="height" class="block mt-2 w-full" type="number" placeholder="180" />
                    </div>
                </div>

                <div class="flex items-center w-full space-x-4" style="margin-top: 50px !important;">
                    <button type="button" @click="paso = 1" class="flex-1 text-[#616f89] font-bold py-2 rounded-[12px] h-[45px] border border-gray-300">ATRÁS</button>
                    <button type="button" @click="paso = 3" class="flex-1 text-white font-bold py-2 rounded-[12px] h-[45px]" style="background-color: #185a94 !important;">SIGUIENTE</button>
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
                            <input type="radio" name="activity" value="sedentary" class="text-[#185a94] focus:ring-[#185a94]" required>
                            <div class="ml-4">
                                <span class="block font-bold text-sm text-[#111318]">Poco o ningún ejercicio</span>
                                <span class="text-xs text-[#616f89] italic">Principalmente sentado (ej. oficina)</span>
                            </div>
                        </div>
                        <span class="text-2xl">🪑</span>
                    </label>

                    <label class="flex items-center justify-between p-4 border rounded-[12px] cursor-pointer hover:border-[#185a94] transition-all shadow-sm">
                        <div class="flex items-center">
                            <input type="radio" name="activity" value="light" class="text-[#185a94] focus:ring-[#185a94]">
                            <div class="ml-4">
                                <span class="block font-bold text-sm text-[#111318]">Ejercicio ligero</span>
                                <span class="text-xs text-[#616f89] italic">1-3 días a la semana</span>
                            </div>
                        </div>
                        <span class="text-2xl">🏡</span>
                    </label>

                    <label class="flex items-center justify-between p-4 border rounded-[12px] cursor-pointer hover:border-[#185a94] transition-all shadow-sm">
                        <div class="flex items-center">
                            <input type="radio" name="activity" value="moderate" class="text-[#185a94] focus:ring-[#185a94]">
                            <div class="ml-4">
                                <span class="block font-bold text-sm text-[#111318]">Moderado</span>
                                <span class="text-xs text-[#616f89] italic">3-5 días a la semana</span>
                            </div>
                        </div>
                        <span class="text-2xl">🏃</span>
                    </label>

                    <label class="flex items-center justify-between p-4 border rounded-[12px] cursor-pointer hover:border-[#185a94] transition-all shadow-sm">
                        <div class="flex items-center">
                            <input type="radio" name="activity" value="heavy" class="text-[#185a94] focus:ring-[#185a94]">
                            <div class="ml-4">
                                <span class="block font-bold text-sm text-[#111318]">Fuerte</span>
                                <span class="text-xs text-[#616f89] italic">6-7 días a la semana</span>
                            </div>
                        </div>
                        <span class="text-2xl">⚡</span>
                    </label>
                </div>

                <div style="margin-top: 50px !important;"> 
                    <button type="submit" class="w-full text-white font-bold py-2 rounded-[12px] h-[50px]" style="background-color: #185a94 !important;">
                        REGISTRARSE
                    </button>
                    <div style="margin-top: 20px !important; text-align: center;">
                        <button type="button" @click="paso = 2" class="text-[#616f89] text-sm underline font-bold">Volver al paso anterior</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</x-guest-layout>