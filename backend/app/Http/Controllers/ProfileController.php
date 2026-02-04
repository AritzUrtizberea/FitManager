<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Carbon\Carbon; // <--- 1. IMPORTANTE: Necesario para las fechas

class ProfileController extends Controller
{
    // Esta función la tenías, la dejamos por si acaso, pero la importante es getUserData
    public function show(Request $request)
    {
        $user = $request->user()->load('profile');
        return response()->json(['status' => 'success', 'user' => $user]);
    }

    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'status' => 'success',
            'user' => $request->user(),
        ]);
    }

    /**
     * 2. NUEVA FUNCIÓN VITAL: Calcula la racha y devuelve los datos
     * Esta es la que llama tu ruta /api/user
     */
    public function getUserData(Request $request)
    {
        $user = $request->user();
        $profile = $user->profile;

        if ($profile) {
            $hoy = Carbon::now();
            
            // Si es null, asumimos fecha antigua para que entre en el reset
            $ultimoRegistro = $profile->last_streak_at ? Carbon::parse($profile->last_streak_at) : null;

            // CASO A: Resetear (Si es null O hace más de un día)
            if (!$ultimoRegistro || $ultimoRegistro->diffInDays($hoy) > 1) {
                // Solo guardamos si no es hoy
                if (!$ultimoRegistro || !$ultimoRegistro->isToday()) {
                    $profile->update([
                        'streak' => 1,
                        'last_streak_at' => $hoy
                    ]);
                }
            }
            // CASO B: Sumar (Si fue ayer)
            elseif ($ultimoRegistro->isYesterday()) {
                $profile->increment('streak');
                $profile->update(['last_streak_at' => $hoy]);
            }
            // CASO C: Si es hoy, no hacemos nada.
        }

        // Devolvemos el JSON con el perfil actualizado
        return $user->load('profile');
    }

    // Procesa el formulario (PUT /profile/edit)
    public function update(Request $request)
    {
        $user = $request->user();

        // 1. Validación
       // 1. Validación con TODOS los mensajes
        $validated = $request->validate([
            'phone'    => 'nullable|string|max:20',
            'weight'   => 'required|numeric|min:20|max:300', // Pongo min 20kg por lógica
            'height'   => 'required|integer|min:50|max:300', // Aquí está el min:50
            'activity' => 'required|string',
            'sex'      => 'required|string',
            'avatar'   => 'nullable|image|max:5120',
        ], [
            // --- MENSAJES PERSONALIZADOS ---
            
            // PESO
            'weight.required' => '¡Oye! El peso es obligatorio.',
            'weight.numeric'  => 'El peso debe ser un número válido.',
            'weight.min'      => 'El peso debe ser de al menos 20 kg.',    // <--- NUEVO
            'weight.max'      => 'El peso no puede superar los 300 kg.',   // <--- NUEVO

            // ALTURA (Aquí es donde te fallaba)
            'height.required' => 'La altura es obligatoria.',
            'height.integer'  => 'La altura debe ser un número entero (cm).',
            'height.min'      => 'La altura debe ser de al menos 50 cm.',  // <--- ¡ESTO ARREGLA TU ERROR!
            'height.max'      => 'La altura no puede superar los 300 cm.', // <--- NUEVO

            // OTROS
            'activity.required' => 'Debes seleccionar tu nivel de actividad.',
            'sex.required'    => 'Por favor, selecciona tu sexo.',
            'avatar.image'    => 'El archivo debe ser una imagen (jpg, png, etc).',
            'avatar.max'      => 'La imagen pesa mucho (máximo 5MB).',
        ]);

        // 2. GESTIÓN DE LA FOTO
        if ($request->hasFile('avatar')) {
            if ($user->profile_photo_path) {
                Storage::disk('public')->delete($user->profile_photo_path);
            }
            $path = $request->file('avatar')->store('avatars', 'public');
            $user->profile_photo_path = $path;
            $user->save();
        }

        // 3. Guardar el resto de datos en la tabla profiles
        $profileOnlyData = [
            'phone' => $request->phone,
            'weight' => $request->weight,
            'height' => $request->height,
            'activity' => $request->activity,
            'sex' => $request->sex,
        ];

        \App\Models\Profile::updateOrCreate(
            ['user_id' => $user->id],
            $profileOnlyData
        );

        return redirect('/perfil')->with('success', '¡Perfil actualizado con éxito!');
    }
}