<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureUserRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $role
     * @return mixed
     */
    public function handle(Request $request, Closure $next, string $role)
    {
        if (!$request->user() || $request->user()->role !== $role) {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Unauthorized. Requires ' . $role . ' role.'], 403);
            }
            
            // Redirect to appropriate dashboard based on actual role
            if (Auth::check()) {
                $userRole = $request->user()->role;
                if ($userRole === 'Admin') {
                    return redirect()->route('admin.dashboard');
                } elseif ($userRole === 'Member') {
                    return redirect()->route('member.dashboard');
                }
            }
            
            return redirect()->route('login');
        }

        return $next($request);
    }
}
