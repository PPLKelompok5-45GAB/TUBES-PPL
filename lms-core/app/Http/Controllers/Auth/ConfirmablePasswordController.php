<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\RedirectResponse;
use Illuminate\Contracts\View\View;
use Illuminate\Contracts\View\Factory;

/**
 * ConfirmablePasswordController
 *
 * @package App\Http\Controllers\Auth
 */
class ConfirmablePasswordController extends Controller
{
    /**
     * Show the password confirmation view.
     *
     * @return View|Factory
     */
    public function show(): View|Factory
    {
        return view('auth.confirm-password');
    }

    /**
     * Confirm the user's password.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => 'required',
        ]);

        $password = $request->input('password');
        $user = Auth::user();
        /** @var \App\Models\User $user */
        $email = $user->email;
        if (! Auth::validate(['email' => $email, 'password' => $password])) {
            return Redirect::back()->withErrors(['password' => 'Password does not match our records.']);
        }

        $request->session()->put('auth.password_confirmed_at', time());

        return Redirect::intended();
    }
}
