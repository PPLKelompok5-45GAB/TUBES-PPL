<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\User;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $role
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $role)
    {
        /** @var User|null $user */
        $user = Auth::user();
        Log::info('RoleMiddleware Debug', [
            'user' => $user,
            'expected_role' => $role,
            'actual_role' => $user->role ?? null,
        ]);
        // Enforce role authorization using dynamic attribute
        if (!$user || $user->role !== $role) {
            abort(403, 'Unauthorized');
        }
        return $next($request);
    }
}
