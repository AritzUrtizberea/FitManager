<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
{
    // 1. Validamos usando los nombres exactos de tu HTML
    $request->validate([
        'name'      => 'required|string|max:255',
        'surname'   => 'required|string|max:255', // En tu HTML es 'surname'
        'email'     => 'required|string|email|max:255|unique:users',
        'password'  => ['required', 'confirmed'],
        'phone'     => 'nullable|string',
        'sexo'      => 'required', // En tu HTML es 'sexo'
        'peso'      => 'required|numeric', // En tu HTML es 'peso'
        'altura'    => 'required|numeric', // En tu HTML es 'altura'
        'actividad' => 'required', // En tu HTML es 'actividad'
    ]);

    // 2. Creamos el Usuario (Base de datos en Inglés)
    $user = User::create([
        'name'     => $request->name,
        'surname'  => $request->surname, 
        'email'    => $request->email,
        'password' => Hash::make($request->password),
    ]);

    // 3. Creamos el Perfil (Base de datos en Inglés)
    $user->profile()->create([
        'phone'    => $request->phone,
        'sex'      => $request->sexo,      // DB: sex <--- HTML: sexo
        'weight'   => $request->peso,      // DB: weight <--- HTML: peso
        'height'   => $request->altura,    // DB: height <--- HTML: altura
        'activity' => $request->actividad, // DB: activity <--- HTML: actividad
    ]);

    Auth::login($user);

    return redirect()->route('home');
}
}
