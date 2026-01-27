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

    // En RegisteredUserController.php
    public function store(Request $request): RedirectResponse
    {
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

        $user = User::create([
            'name'     => $request->name,
            'surname'  => $request->surname,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // USA ESTA FORMA QUE ES MÁS SEGURA:
        $profile = new \App\Models\Profile();
        $profile->user_id  = $user->id;
        $profile->phone    = $request->phone;
        $profile->sex      = $request->sex;
        $profile->weight   = $request->weight;
        $profile->height   = $request->height;
        $profile->activity = $request->activity;
        $profile->save(); // Forzamos el guardado físico

        Auth::login($user);
        return redirect('/home');
    }
    }
