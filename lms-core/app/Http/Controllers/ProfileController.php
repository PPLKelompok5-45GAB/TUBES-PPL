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
            'name' => ['required', 'string', 'max:255'],
        ]);
        $user->name = $validated['name'];
        $user->save();
        return back()->with('status', 'Profile updated!');
    }
}
