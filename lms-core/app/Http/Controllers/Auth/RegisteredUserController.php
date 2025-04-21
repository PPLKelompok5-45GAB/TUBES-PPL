<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\RedirectResponse;
use Illuminate\Contracts\View\View;
use Illuminate\Contracts\View\Factory;

class RegisteredUserController extends Controller
{
    /**
     * Show the registration form.
     *
     * @return View|Factory
     */
    public function create(): View|Factory
    {
        return view('auth.register');
    }

    /**
     * Handle registration request.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        $attributes = $request->validate([
            'username' => 'required|string|max:255|min:2',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:5|max:255',
            'role' => 'required|in:Member,Admin',
        ]);
        /** @var array{username: string, email: string, password: string, role: string} $attributes */
        DB::transaction(function () use ($attributes) {
            $user = new User();
            $user->username = $attributes['username'];
            $user->email = $attributes['email'];
            $user->password = $attributes['password']; // Let the mutator hash it
            $user->role = $attributes['role'];
            $user->save();

            if ($user->role === 'Member') {
                \App\Models\Member::create([
                    'name' => $user->username,
                    'email' => $user->email,
                    'status' => 'active',
                    'membership_date' => now(),
                ]);
            } elseif ($user->role === 'Admin') {
                \App\Models\Admin::create([
                    'admin_id' => $user->id,
                    'name' => $user->username,
                    'email' => $user->email,
                    'phone' => '', // or $request->input('phone') if available
                    'address' => '', // or $request->input('address') if available
                    'status' => 'active',
                ]);
            }

            Auth::login($user);
        });
        return Redirect::to('/dashboard');
    }
}
