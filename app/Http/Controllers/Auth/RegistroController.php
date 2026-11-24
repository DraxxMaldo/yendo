<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ValidarCorreoRegistroRequest;
use App\Mail\CodigoRegistroMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use App\Models\PerfilPersona;
use App\Http\Requests\Auth\CompletarRegistroRequest;
use App\Models\UsuarioCredencial;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;

class RegistroController extends Controller
{
    // Método para generar y enviar el código
    public function enviarCodigo(ValidarCorreoRegistroRequest $request)
    {
        $correo = strtolower(trim($request->correo));
        $claveCorreo = hash('sha256', $correo);
        $limiteEnvios = 'registro:envios:' . $claveCorreo . ':' . $request->ip();

        // Máximo 3 envíos por correo e IP cada 10 minutos.
        if (RateLimiter::tooManyAttempts($limiteEnvios, 3)) {
            return response()->json([
                'success' => false,
                'mensaje' => 'Has solicitado demasiados códigos. Inténtalo nuevamente más tarde.',
                'segundos_restantes' => RateLimiter::availableIn($limiteEnvios)
            ], 429);
        }

        RateLimiter::hit($limiteEnvios, 600);

        // Un nuevo envío invalida cualquier autorización anterior de esta sesión.
        $request->session()->forget('registro_correo_verificado');

        // Genera un código aleatorio criptográficamente seguro de 6 dígitos.
        $codigo = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        // Conserva la vigencia actual del código: 60 segundos.
        Cache::put('codigo_registro_' . $claveCorreo, $codigo, 60);

        Mail::to($correo)->send(new CodigoRegistroMail($codigo));

        return response()->json(['success' => true, 'mensaje' => 'Código enviado al correo.']);
    }

    // Método para verificar si el código ingresado coincide
    public function verificarCodigo(Request $request)
    {
        $datos = $request->validate([
            'correo' => 'required|email',
            'codigo' => 'required|digits:6'
        ]);

        $correo = strtolower(trim($datos['correo']));
        $claveCorreo = hash('sha256', $correo);
        $limiteIntentos = 'registro:verificaciones:' . hash('sha256', $correo . '|' . $request->ip());

        // Máximo 5 intentos de verificación por correo e IP cada 10 minutos.
        if (RateLimiter::tooManyAttempts($limiteIntentos, 5)) {
            return response()->json([
                'success' => false,
                'mensaje' => 'Has superado el número de intentos. Solicita un nuevo código más tarde.',
                'segundos_restantes' => RateLimiter::availableIn($limiteIntentos)
            ], 429);
        }

        $codigoGuardado = Cache::get('codigo_registro_' . $claveCorreo);

        if (!$codigoGuardado) {
            return response()->json(['success' => false, 'mensaje' => 'El código ha expirado. Por favor, solicita uno nuevo.'], 422);
        }

        RateLimiter::hit($limiteIntentos, 600);

        if (!hash_equals((string) $codigoGuardado, (string) $datos['codigo'])) {
            return response()->json(['success' => false, 'mensaje' => 'El código es incorrecto.'], 422);
        }

        // El código se consume y se crea una autorización temporal ligada a esta sesión y correo.
        Cache::forget('codigo_registro_' . $claveCorreo);
        RateLimiter::clear($limiteIntentos);

        $request->session()->put('registro_correo_verificado', [
            'correo' => $correo,
            'expira' => now()->addMinutes(10)->timestamp
        ]);

        return response()->json(['success' => true]);
    }

    // Guarda los datos finales únicamente si esta sesión verificó el mismo correo.
    public function finalizarRegistro(CompletarRegistroRequest $request)
    {
        try {
            DB::beginTransaction();

            $usuario = UsuarioCredencial::create([
                'correo_electronico' => strtolower(trim($request->correo_final)),
                'contrasenha'        => Hash::make($request->password),
                'id_rol'             => 3,
                'estado_cuenta'      => 1
            ]);

            PerfilPersona::create([
                'id_usuario'     => $usuario->id_usuario,
                'nombres'        => $request->nombres,
                'apellidos'      => $request->apellidos,
                'telefono'       => '00000000',
                'nombre_negocio' => 'Por definir'
            ]);

            DB::commit();

            // Se consume la autorización solo después de confirmar la transacción.
            $request->session()->forget('registro_correo_verificado');

            return response()->json([
                'success' => true,
                'mensaje' => '¡Cuenta creada con éxito! Ahora puedes iniciar sesión.'
            ]);

        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'mensaje' => 'No fue posible completar el registro. Inténtalo nuevamente.'
            ], 500);
        }
    }
}
