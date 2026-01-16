<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FitManager - Editar Perfil</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="{{ asset('css/edit.css') }}">
</head>
<body>

    <main class="container fm-edit-container" x-data="{ sex: '{{ Auth::user()->profile->sex ?? 'male' }}' }">
        
        @if (session('status') === 'profile-updated')
            <div class="card-panel green lighten-4 green-text text-darken-4 center-align" style="border-radius: 12px; border: 1px solid #c8e6c9; margin-top: 20px;">
                <i class="ph ph-check-circle" style="vertical-align: middle; font-size: 20px;"></i>
                <b>¡Éxito!</b> Tus cambios se han guardado correctamente.
            </div>
        @endif

        @if ($errors->any())
            <div class="card-panel red lighten-4 red-text" style="border-radius: 12px; border: 1px solid #ffcdd2;">
                <b>Por favor, corrige los siguientes errores:</b>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <h4 class="center-align edit-main-title">Editar Perfil</h4>

        <div class="edit-card">
            <div class="edit-card-header">Información Personal</div>
            
            <div class="edit-card-content">
                <form method="POST" action="{{ route('profile.update') }}" novalidate>
                    @csrf
                    @method('patch')

                    <input type="hidden" name="name" value="{{ Auth::user()->name }}">
                    <input type="hidden" name="email" value="{{ Auth::user()->email }}">

                    <label class="fm-label-exact">Teléfono</label>
                    <div class="fm-input-wrapper-exact">
                        <i class="ph ph-phone"></i>
                        <input type="tel" name="phone" value="{{ old('phone', Auth::user()->profile->phone ?? '') }}" class="fm-input-field-exact">
                    </div>

                    <div class="row" style="margin-bottom: 0;">
                        <div class="col s6" style="padding-left: 0;">
                            <label class="fm-label-exact">Peso</label>
                            <div class="input-with-unit">
                                <input type="number" step="0.1" name="weight" value="{{ old('weight', Auth::user()->profile->weight ?? '') }}" class="fm-input-field-exact no-icon">
                                <span class="unit">kg</span>
                            </div>
                        </div>
                        <div class="col s6" style="padding-right: 0;">
                            <label class="fm-label-exact">Altura</label>
                            <div class="input-with-unit">
                                <input type="number" name="height" value="{{ old('height', Auth::user()->profile->height ?? '') }}" class="fm-input-field-exact no-icon">
                                <span class="unit">cm</span>
                            </div>
                        </div>
                    </div>

                    <div class="row" style="margin-bottom: 0;">
                        <div class="col s12" style="padding: 0;">
                            <label class="fm-label-exact">Actividad Física</label>
                            <div class="input-with-unit">
                                <select name="activity" class="browser-default fm-input-field-exact no-icon">
                                    @php $act = Auth::user()->profile->activity ?? ''; @endphp
                                    <option value="sedentary" {{ $act == 'sedentary' ? 'selected' : '' }}>Sedentaria (Baja)</option>
                                    <option value="light" {{ $act == 'light' ? 'selected' : '' }}>Ligera</option>
                                    <option value="moderate" {{ $act == 'moderate' ? 'selected' : '' }}>Moderada</option>
                                    <option value="heavy" {{ $act == 'heavy' ? 'selected' : '' }}>Alta (Fuerte)</option>
                                </select>
                                <span class="unit-select"><i class="ph ph-caret-down"></i></span>
                            </div>
                        </div>
                    </div>

                    <label class="fm-label-exact">Sexo</label>
                    <div class="gender-row">
                        <input type="hidden" name="sex" :value="sex">
                        
                        <div class="gender-btn-group">
                            <div class="g-btn" :class="sex === 'male' ? 'active' : ''" @click="sex = 'male'">Hombre</div>
                            <div class="g-btn" :class="sex === 'female' ? 'active' : ''" @click="sex = 'female'">Mujer</div>
                        </div>
                        <div class="gender-icons">
                            <i class="ph ph-gender-male" :style="sex === 'male' ? 'color: #1d64a1' : ''"></i>
                            <i class="ph ph-gender-female" :style="sex === 'female' ? 'color: #1d64a1' : ''"></i>
                        </div>
                    </div>

                    <button type="submit" class="btn waves-effect waves-light fm-btn-green-exact">
                        Guardar Cambios
                    </button>
                </form>
            </div>
        </div>
    </main>

    <footer class="fm-bottom-nav white">
        <a href="{{ route('home') }}" class="nav-item">
            <i class="ph ph-house"></i>
            <span>Casa</span>
        </a>
        <div class="nav-item">
            <i class="ph ph-unite"></i>
            <span>Nutricion</span>
        </div>
        <div class="nav-item">
            <i class="ph ph-barbell"></i>
            <span>Entrenamiento</span>
        </div>
        <a href="{{ route('perfil') }}" class="nav-item active">
            <i class="ph-fill ph-user"></i>
            <span>Perfil</span>
        </a>
    </footer>

</body>
</html>