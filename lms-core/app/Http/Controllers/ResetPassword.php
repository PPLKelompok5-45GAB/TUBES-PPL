<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Notifications\ForgotPassword;
use Illuminate\Http\Request;
use Illuminate\Notifications\Notifiable;

class ResetPassword extends Controller
{
    use Notifiable;

    /**
     * Show the password reset form.
     *
     * @return \Illuminate\View\View
     */
    public function show(): \Illuminate\View\View
    {
        return view('auth.reset-password');
    }

    /**
     * Route notification for mail.
     *
     * @param \Illuminate\Http\Request $request
     * @return string|null
     */
    public function routeNotificationForMail(Request $request): ?string
    {
        $email = $request->input('email');
        return is_string($email) ? $email : null;
    }

    /**
     * Send the password reset email.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function send(Request $request): \Illuminate\Http\RedirectResponse
    {
        $tokenRaw = $request->input('token', '');
        $token = is_string($tokenRaw) ? $tokenRaw : '';
        $emailRaw = $request->input('email');
        $email = is_string($emailRaw) ? $emailRaw : '';
        if ($email === '') {
            return back()->withErrors(['email' => 'Invalid email address.']);
        }
        $user = User::where('email', '=', $email)->first();
        if (!$user || !method_exists($user, 'notify')) {
            return back()->withErrors(['email' => 'No user found with that email.']);
        }
        $user->notify(new ForgotPassword($token));
        return back()->with('succes', 'An email was send to your email address');
    }
}
