<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use App\Models\Bitacora;
//Importaciones para mandar correo electronico
use App\Models\UsuarioCredencial;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\NuevaContrasenaMail;

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


            Bitacora::registrar(Bitacora::LOGIN_OK, 'Autenticación', null, 'Inicio de sesión exitoso');

            return redirect()->intended(route('home'))->with('success', '¡Bienvenido al sistema Yendo!');
        }

        // ---> 1.a. REGISTRO LOGIN_FALLIDO:
        Bitacora::registrar(Bitacora::LOGIN_FALLIDO, 'Autenticación', null, 'Credenciales incorrectas', $request->correo);

        // Si falla, disparamos el Toast de error que ya tienes configurado
        return redirect()->route('login')->with('error', 'Las credenciales proporcionadas son incorrectas.');
    }

    // 3. Cerrar sesión
    public function logout(Request $request)
    {
        // ---> 1.c LOGOUT
        Bitacora::registrar(Bitacora::LOGOUT, 'Autenticación', null, 'Cierre de sesión manual');

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('info', '¡Has cerrado sesión correctamente!');
    }


    public function enviarNuevaContrasena(Request $request)
    {
        // 1. Validar que el correo venga en el formulario y EXISTA en la tabla
        $request->validate([
            'correo' => 'required|email|exists:usuarios_credenciales,correo_electronico'
        ], [
            'correo.required' => 'El correo es obligatorio.',
            'correo.email' => 'Ingresa un formato de correo válido.',
            'correo.exists' => 'Este correo no está registrado en nuestro sistema.'
        ]);

        // 2. Buscar al usuario
        $usuario = UsuarioCredencial::where('correo_electronico', $request->correo)->first();

        // 3. Generar nueva contraseña aleatoria (Ejemplo: 8 caracteres alfanuméricos)
        $nuevaContrasenaPlain = Str::random(8);

        // 4. Actualizar la contraseña en la base de datos (Hasheada por seguridad)
        $usuario->update([
            'contrasenha' => Hash::make($nuevaContrasenaPlain)
        ]);

        // 5. Enviar el correo con la contraseña en texto plano
        Mail::to($usuario->correo_electronico)->send(new NuevaContrasenaMail($nuevaContrasenaPlain));

        // 6. Retornar a la vista con mensaje de éxito
        return redirect()->route('login')->with('success', 'Las indicaciones y tu nueva contraseña se enviaron correctamente.');
    }



}
