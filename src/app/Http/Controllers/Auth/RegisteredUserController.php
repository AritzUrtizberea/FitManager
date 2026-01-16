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
    // 1. Validamos todos los datos que llegan de los 3 pasos
    $request->validate([
        'name' => 'required|string|max:255',
        'surname' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users',
        'password' => ['required', 'confirmed'],
        'phone' => 'nullable|string',
        'sexo' => 'required',
        'peso' => 'required|numeric',
        'altura' => 'required|numeric',
        'actividad' => 'required',
    ]);

    // 2. Insertamos en la tabla 'users'
    $user = User::create([
        'name' => $request->name,
        'surname' => $request->surname,
        'email' => $request->email,
        'password' => Hash::make($request->password),
    ]);

    // 3. Insertamos en la tabla 'profiles' usando la relación
    // Esto asume que tienes 'public function profile() { return $this->hasOne(Profile::class); }' en User.php
    $user->profile()->create([
        'phone' => $request->phone,
        'sexo' => $request->sexo,
        'peso' => $request->peso,
        'altura' => $request->altura,
        'actividad' => $request->actividad,
    ]);

    Auth::login($user);

    return view('home.home', ['user' => $user]);
}
}
