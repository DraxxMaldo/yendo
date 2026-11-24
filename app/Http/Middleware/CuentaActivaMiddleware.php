<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CuentaActivaMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $usuario = Auth::user();

        // Si una cuenta fue desactivada, se cierra cualquier sesión que siga abierta.
        if ($usuario && !$usuario->estado_cuenta) {
            Auth::logout();

            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')->with(
                'error',
                'No fue posible iniciar sesión. Verifica tus credenciales o contacta al administrador.'
            );
        }

        return $next($request);
    }
}
