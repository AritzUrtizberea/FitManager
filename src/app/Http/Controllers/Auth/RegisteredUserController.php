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
    // 1. Validación (Nombres internos en inglés)
    $request->validate([
        'name'      => 'required|string|max:255',
        'surname'   => 'required|string|max:255',
        'email'     => 'required|string|email|max:255|unique:users',
        'password'  => ['required', 'confirmed'],
        'phone'     => 'nullable|string',
        'sex'       => 'required',
        'weight'    => 'required|numeric',
        'height'    => 'required|numeric',
        'activity'  => 'required',
    ]);

    // 2. Crear Usuario
    $user = User::create([
        'name'     => $request->name,
        'surname'  => $request->surname,
        'email'    => $request->email,
        'password' => Hash::make($request->password),
    ]);

    // 3. Crear Perfil (Todo directo en inglés)
    $user->profile()->create([
        'phone'    => $request->phone,
        'sex'      => $request->sex,
        'weight'   => $request->weight,
        'height'   => $request->height,
        'activity' => $request->activity,
    ]);

    Auth::login($user);
    return redirect()->route('home');
}
}
