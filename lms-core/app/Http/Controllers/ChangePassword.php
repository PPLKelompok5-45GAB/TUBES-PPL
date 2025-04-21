<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Controller for changing a user's password.
 */
class ChangePassword extends Controller
{
    /**
     * The user instance.
     *
     * @var \App\Models\User
     */
    protected \App\Models\User $user;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        Auth::logout();

        $idRaw = request()->id;
        if (!is_numeric($idRaw)) {
            abort(400, 'Invalid user ID');
        }
        /** @var int $id */
        $id = intval($idRaw);
        $this->user = User::findOrFail($id);
    }

    /**
     * Show the change password form.
     *
     * @return \Illuminate\View\View
     */
    public function show()
    {
        return view('auth.change-password');
    }

    /**
     * Update the user's password.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        /** @var array<string, mixed> $attributes */
        $attributes = $request->validate([
            'email' => ['required'],
            'password' => ['required', 'min:5'],
            'confirm-password' => ['same:password'],
        ]);

        /** @var string $operator */
        $operator = '=';
        $existingUser = User::where('email', $operator, $attributes['email'])->first();
        if ($existingUser) {
            $existingUser->update([
                'password' => $attributes['password'],
            ]);

            /** @var \Illuminate\Http\RedirectResponse $resp */
            $resp = redirect('login');
            return $resp;
        } else {
            return back()->with('error', 'Your email does not match the email who requested the password change');
        }
    }

    /**
     * Change the user's password.
     *
     * @param  int  $id
     * @param  string  $newPassword
     * @return \Illuminate\Http\RedirectResponse
     */
    public function change(int $id, string $newPassword): \Illuminate\Http\RedirectResponse
    {
        $user = \App\Models\User::findOrFail($id);
        $user->password = bcrypt($newPassword);
        $user->save();
        /** @var \Illuminate\Http\RedirectResponse $resp */
        $resp = redirect()->route('profile')->with('success', 'Password changed successfully.');
        return $resp;
    }

    /**
     * Update the user's password.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updatePassword(Request $request)
    {
        /** @var array<string, mixed> $attributes */
        $attributes = $request->only(['email', 'password']);
        $this->user->update($attributes);
        /** @var \Illuminate\Http\RedirectResponse $resp */
        $resp = redirect()->route('password.change')->with('status', 'Password updated successfully.');
        return $resp;
    }
}
