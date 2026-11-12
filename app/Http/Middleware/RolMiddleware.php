<?php

namespace App\Http\Middleware;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RolMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!auth()->check())
        {
            return redirect()->route('login');

        }
        /*if (!auth()->user()->activo)
        {
            auth()->logout();
            return redirect()->route('login')->with('error', 'Tu cuenta está inactiva.');

        }*/
        if (empty($roles))
        {
            return $next($request);

        }
        if (auth()->user()->tieneAlgunRol($roles))
        {
            return $next($request);

        }
        return redirect()->route('home')->with('error', 'Acceso denegado');
    }
}
