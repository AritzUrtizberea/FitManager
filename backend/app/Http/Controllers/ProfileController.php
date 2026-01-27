<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function show(Request $request)
{
    $user = $request->user()->load('profile');
    return response()->json(['status' => 'success', 'user' => $user]);
}

    // Muestra el formulario de edición (GET /profile/edit)
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'status' => 'success',
            'user' => $request->user(),
        ]);
    }

    // Procesa el formulario (PUT /profile/edit)
    // ProfileController.php
    public function update(Request $request)
    {

        $user = $request->user();

        // 1. Validación estricta
        $validated = $request->validate([
            'phone'    => 'required|string',
            'weight'   => 'required|numeric',
            'height'   => 'required|integer',
            'activity' => 'required|string',
            'sex'      => 'required|string',
        ]);

        // 2. Guardar o Crear (Store/Update combinados)
        \App\Models\Profile::updateOrCreate(
            ['user_id' => $user->id], // Lo busca por esto
            $validated               // Guarda esto
        );

        return redirect()->back()->with('success', '¡Perfil guardado correctamente!');
    }
}