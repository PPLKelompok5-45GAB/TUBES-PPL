<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Validation\Rule;

class UserProfileController extends Controller
{
    /**
     * Show the user profile.
     *
     * @return \Illuminate\View\View
     */
    public function show(): \Illuminate\View\View
    {
        /** @var User|null $user */
        $user = Auth::user();
        if (!$user) {
            abort(403, 'Unauthorized');
        }
        return view('pages.user-profile', ['user' => $user]);
    }

    /**
     * Update the user profile.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request): \Illuminate\Http\RedirectResponse
    {
        /** @var User|null $user */
        $user = Auth::user();
        if (!$user) {
            abort(403, 'Unauthorized');
        }
        /** @var array<string, mixed> $validated */
        $validated = $request->validate([
            'username' => ['required', 'max:255', 'min:2'],
            'firstname' => ['max:100'],
            'lastname' => ['max:100'],
            'email' => ['required', 'email', 'max:255',  Rule::unique('users')->ignore($user->id)],
            'address' => ['max:100'],
            'city' => ['max:100'],
            'country' => ['max:100'],
            'postal' => ['max:100'],
            'about' => ['max:255'],
        ]);

        $user->update($validated);

        return back()->with('success', 'Profile succesfully updated');
    }
}
