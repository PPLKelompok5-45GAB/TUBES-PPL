<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

/**
 * Class ProfileController
 *
 * @package App\Http\Controllers
 */
class ProfileController extends Controller
{
    /**
     * Display the user's profile page.
     *
     * @return \Illuminate\View\View
     */
    public function show()
    {
        $user = auth()->user();
        if (!$user) {
            abort(403, 'Unauthorized');
        }
        return view('vendor.argon.profile', compact('user'));
    }

    /**
     * Update the user's profile.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        $user = auth()->user();
        if (!$user) {
            abort(403, 'Unauthorized');
        }
        $validated = $request->validate([
            'firstname' => ['nullable', 'string', 'max:255'],
            'lastname' => ['nullable', 'string', 'max:255'],
            'address' => ['nullable', 'string', 'max:255'],
            'city' => ['nullable', 'string', 'max:255'],
            'country' => ['nullable', 'string', 'max:255'],
            'postal' => ['nullable', 'string', 'max:255'],
            'about' => ['nullable', 'string', 'max:1000'],
        ]);
        $user->firstname = $validated['firstname'] ?? null;
        $user->lastname = $validated['lastname'] ?? null;
        $user->address = $validated['address'] ?? null;
        $user->city = $validated['city'] ?? null;
        $user->country = $validated['country'] ?? null;
        $user->postal = $validated['postal'] ?? null;
        $user->about = $validated['about'] ?? null;
        $user->save();
        return back()->with('status', 'Profile updated!');
    }
}
