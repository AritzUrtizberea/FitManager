<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        // Cambiamos 'profile.edit' por 'configuration.edit'
        return view('configuration.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        
        // Esto guarda Nombre y Email
        $user->fill($request->validated());
        $user->save();

        // Esto guarda los datos de peso, altura, etc.
        // Usamos $request->all() o los nombres directos para asegurar que entran
        $user->profile()->updateOrCreate(
            ['user_id' => $user->id],
            [
                'phone'    => $request->phone,
                'sex'      => $request->sex,
                'weight'   => $request->weight,
                'height'   => $request->height,
                'activity' => $request->activity,
            ]
        );

        return redirect()->route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}