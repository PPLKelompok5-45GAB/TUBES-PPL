<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * Display login page.
     *
     * @return \Illuminate\View\View
     */
    public function show(): \Illuminate\View\View
    {
        return view('auth.login');
    }

    /**
     * Handle a login request.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function login(Request $request): \Illuminate\Http\RedirectResponse
    {
        $credentials = $request->only(['email', 'password']);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            // Always return RedirectResponse for PHPStan compliance
            /** @var \Illuminate\Http\RedirectResponse $redirect */
            $redirect = redirect()->intended('dashboard');
            return $redirect;
        }

        /** @var \Illuminate\Http\RedirectResponse $redirect */
        $redirect = back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
        return $redirect;
    }

    /**
     * Log the user out.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout(Request $request): \Illuminate\Http\RedirectResponse
    {
        Auth::logout();
        /** @var \Illuminate\Http\RedirectResponse $redirect */
        $redirect = redirect()->route('login');
        return $redirect;
    }
}
