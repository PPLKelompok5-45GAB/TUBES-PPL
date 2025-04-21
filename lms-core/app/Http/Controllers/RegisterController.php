<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Handle user registration.
 */
class RegisterController extends Controller
{
    /**
     * Show the registration form.
     *
     * @return \Illuminate\View\View
     */
    public function create(): \Illuminate\View\View
    {
        return view('auth.register');
    }

    /**
     * Handle a registration request.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request): \Illuminate\Http\RedirectResponse
    {
        $validated = $request->validate([
            'username' => ['required', 'string', 'max:255', 'min:2'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:5', 'max:255'],
            'terms' => ['required'],
        ]);
        /** @var array<string, mixed> $validated */
        $user = User::create([
            'username' => isset($validated['username']) && is_string($validated['username']) ? $validated['username'] : '',
            'email' => isset($validated['email']) && is_string($validated['email']) ? $validated['email'] : '',
            'password' => isset($validated['password']) && is_string($validated['password']) ? bcrypt($validated['password']) : '',
        ]);
        Auth::login($user);

        return redirect()->route('dashboard');
    }
}
