<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage; // Importante para borrar fotos viejas si quieres
use Illuminate\View\View;

class ProfileController extends Controller
{
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

    // Procesa el formulario (PUT /profile/edit)
public function update(Request $request)
    {
        $user = $request->user();

        // 1. Validación (Incluimos 'avatar')
        $validated = $request->validate([
            'phone'    => 'nullable|string',
            'weight'   => 'required|numeric',
            'height'   => 'required|integer',
            'activity' => 'required|string',
            'sex'      => 'required|string',
            'avatar'   => 'nullable|image|max:5120', // Máx 5MB
        ]);

        // 2. GESTIÓN DE LA FOTO (Esto es lo nuevo)
        if ($request->hasFile('avatar')) {
            // (Opcional) Borrar foto vieja si existe
            if ($user->profile_photo_path) {
                Storage::disk('public')->delete($user->profile_photo_path);
            }

            // Guardar nueva foto en carpeta 'avatars' dentro de storage/app/public
            $path = $request->file('avatar')->store('avatars', 'public');

            // Guardar la ruta en la tabla users
            $user->profile_photo_path = $path;
            $user->save();
        }

        // 3. Guardar el resto de datos en la tabla profiles
        // Quitamos 'avatar' porque eso no va en la tabla profiles
        $profileData = $request->except(['avatar', '_token', '_method']);
        
        // Importante: Asegúrate de que $profileData tenga los campos correctos para Profile
        // A veces $request trae cosas extra, filtraremos solo lo validado para el perfil:
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