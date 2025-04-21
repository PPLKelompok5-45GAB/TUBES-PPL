<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @param string|null ...$guards
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next, ...$guards): Response
    {
        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                // Ensure return type is Symfony Response
                $redirect = \redirect('/dashboard');
                if (method_exists($redirect, 'toResponse')) {
                    /** @var \Symfony\Component\HttpFoundation\Response $resp */
                    $resp = $redirect->toResponse($request);
                    return $resp;
                }
                // Fallback for other redirect types
                return new Response('', 302, ['Location' => '/dashboard']);
            }
        }
        $resp = $next($request);
        if ($resp instanceof Response) {
            return $resp;
        }
        // Fallback: return empty Response if not already a Response
        return new Response('');
    }
}
