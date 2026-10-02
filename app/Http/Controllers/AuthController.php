<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;

class AuthController extends Controller
{
    // 1. Mostrar la vista (Si ya está logueado, lo manda al home)
    public function index()
    {
        if (Auth::check()) {
            return redirect()->route('home');
        }
        return view('auth.login');
    }

    // 2. Procesar el inicio de sesión
    public function login(LoginRequest $request)
    {
        // Mapeamos los campos del formulario a lo que espera Laravel
        // NOTA: Para la contraseña, la llave SIEMPRE debe llamarse 'password' en Auth::attempt
        $credenciales = [
            'correo_electronico' => $request->correo,
            'password'           => $request->clave
        ];

        // Auth::attempt verifica automáticamente el correo y hace el Hash::check de la clave
        if (Auth::attempt($credenciales)) {
            $request->session()->regenerate();
            return redirect()->intended(route('home'))->with('success', '¡Bienvenido al sistema Yendo!');
        }

        // Si falla, disparamos el Toast de error que ya tienes configurado
        return redirect()->route('login')->with('error', 'Las credenciales proporcionadas son incorrectas.');
    }

    // 3. Cerrar sesión
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('info', '¡Has cerrado sesión correctamente!');
    }
}
