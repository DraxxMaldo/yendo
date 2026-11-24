<?php

namespace Tests\Feature;

use App\Models\UsuarioCredencial;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class CuentaInactivaTest extends TestCase
{
    use RefreshDatabase;

    private const CORREO = 'cuenta@yendo.test';
    private const CONTRASENA = 'YendoTest123!';
    private const MENSAJE_ACCESO = 'No fue posible iniciar sesión. Verifica tus credenciales o contacta al administrador.';

    protected function setUp(): void
    {
        parent::setUp();

        // El modelo de credenciales utiliza el rol como llave foránea.
        DB::table('roles')->insert([
            'id_rol' => 3,
            'nombre_rol' => 'Basico',
            'descripcion' => 'Rol utilizado en las pruebas de autenticación.',
        ]);
    }

    public function test_una_cuenta_inactiva_no_puede_iniciar_sesion(): void
    {
        $this->crearUsuario(false);

        $response = $this->post(route('login.post'), [
            'correo' => self::CORREO,
            'clave' => self::CONTRASENA,
        ]);

        $response->assertRedirect(route('login'));
        $response->assertSessionHas('error', self::MENSAJE_ACCESO);
        $this->assertGuest();
    }

    public function test_una_cuenta_activa_puede_iniciar_sesion(): void
    {
        $usuario = $this->crearUsuario(true);

        $response = $this->post(route('login.post'), [
            'correo' => self::CORREO,
            'clave' => self::CONTRASENA,
        ]);

        $response->assertRedirect(route('home'));
        $response->assertSessionHas('success', '¡Bienvenido al sistema Yendo!');
        $this->assertAuthenticatedAs($usuario);
    }

    public function test_una_sesion_existente_se_cierra_si_la_cuenta_se_desactiva(): void
    {
        $usuario = $this->crearUsuario(true);

        $this->actingAs($usuario);

        // Simula que el administrador desactiva la cuenta desde otro flujo.
        $usuario->update(['estado_cuenta' => false]);

        $response = $this->get(route('perfil.index'));

        $response->assertRedirect(route('login'));
        $response->assertSessionHas('error', self::MENSAJE_ACCESO);
        $this->assertGuest();
    }

    private function crearUsuario(bool $estadoCuenta): UsuarioCredencial
    {
        return UsuarioCredencial::create([
            'correo_electronico' => self::CORREO,
            'contrasenha' => self::CONTRASENA,
            'id_rol' => 3,
            'estado_cuenta' => $estadoCuenta,
        ]);
    }
}
