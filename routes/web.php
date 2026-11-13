<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\RegistroController;
use App\Http\Controllers\DepartamentoController;
use App\Http\Controllers\EstablecimientoController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MunicipioController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\PerfilController;
use App\Http\Controllers\BitacoraController;
use App\Http\Controllers\DestinoController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SolicitudAfiliacionController;
use App\Http\Controllers\EmpleadoSucursalController;

// Definición de roles del sistema tal como lo maneja tu equipo
$roles = ["admin", "Afiliado", "Basico"];

// ==========================================
// 1. RUTAS PÚBLICAS (Accesibles para todos)
// ==========================================
Route::get('/', [HomeController::class, 'index'])->name('home');

// Registro de usuarios paso a paso
Route::post('/registro/enviar-codigo', [RegistroController::class, 'enviarCodigo'])->name('registro.enviar_codigo');
Route::post('/registro/verificar-codigo', [RegistroController::class, 'verificarCodigo'])->name('registro.verificar_codigo');
Route::post('/registro/finalizar', [RegistroController::class, 'finalizarRegistro'])->name('registro.finalizar');
Route::get('/registro', function () { return view('auth.registro'); })->name('registro');

// Autenticación
Route::get('/login', [AuthController::class, 'index'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Recuperación de contraseña
Route::get('/recuperar-contrasena', function () { return view('Auth.recuperar-contrasenha'); })->name('password.request');
Route::post('/recuperar-contrasena', [AuthController::class, 'enviarNuevaContrasena'])->name('password.email');

// Ruta pública para ver los destinos
Route::get('/destinos', [DestinoController::class, 'index'])->name('destinos');


// ==========================================
// 2. RUTAS PARA CUALQUIER USUARIO AUTENTICADO
// ==========================================
Route::middleware(['auth'])->group(function () {
    Route::prefix('perfil')->name('perfil.')->group(function () {
        Route::get('/', [PerfilController::class, 'index'])->name('index');
        Route::post('/actualizar-foto', [PerfilController::class, 'actualizarFoto'])->name('actualizar_foto');
        Route::put('/actualizar-informacion', [PerfilController::class, 'actualizarInformacion'])->name('actualizar_informacion');
    });

    // Rutas para el cambio de contraseña seguro en formato SPA por pasos
    Route::middleware(['auth'])->prefix('perfil/seguridad')->name('perfil.seguridad.')->group(function () {
        Route::get('/cambiar-password', [PerfilController::class, 'vistaCambiarPassword'])->name('index');
        Route::post('/enviar-codigo', [PerfilController::class, 'enviarCodigoPassword'])->name('enviar_codigo');
        Route::post('/verificar-codigo', [PerfilController::class, 'verificarCodigoPassword'])->name('verificar_codigo');
        Route::post('/actualizar-final', [PerfilController::class, 'actualizarPasswordFinal'])->name('actualizar_final');
    });

    // Ruta para que el usuario básico envíe su solicitud
    Route::post('/perfil/solicitar-afiliacion', [SolicitudAfiliacionController::class, 'store'])->name('perfil.solicitar_afiliacion');
});


// ==========================================
// 3. RUTAS EXCLUSIVAS PARA ADMINISTRADORES ($roles[0])
// ==========================================
Route::middleware(['auth', "rol:{$roles[0]}"])->group(function () {

    // --- Módulo de Usuarios ---
    Route::prefix('usuarios')->name('usuarios.')->group(function () {
        Route::get('/', [UsuarioController::class, 'index'])->name('index');
        Route::post('/insertar', [UsuarioController::class, 'insertarUsuario'])->name('insertar');
        Route::put('/{id}', [UsuarioController::class, 'actualizar'])->name('actualizar');
        Route::patch('/{id}/desactivar', [UsuarioController::class, 'desactivar'])->name('desactivar');
        Route::patch('/{id}/activar', [UsuarioController::class, 'activar'])->name('activar');
    });

    // --- Módulo de Auditoría / Bitácora ---
    Route::get('/bitacora', [BitacoraController::class, 'index'])->name('bitacora');



    Route::post('/departamentos', [DepartamentoController::class, 'store'])->name('departamentos.store');
    Route::post('/municipios', [MunicipioController::class, 'store'])->name('municipios.store');



    Route::get('/admin/solicitudes-afiliacion', [SolicitudAfiliacionController::class, 'indexAdmin'])->name('admin.solicitudes_afiliacion');
    Route::post('/admin/solicitudes-afiliacion/{id}/estado', [SolicitudAfiliacionController::class, 'cambiarEstado'])->name('admin.solicitudes.estado');

});


// ==========================================
// 4. RUTAS EXCLUSIVAS PARA AFILIADOS ($roles[1])
// ==========================================
Route::middleware(['auth', "rol:{$roles[1]}"])->group(function () {

    Route::middleware(['auth'])->group(function () {
        Route::get('/empleados-sucursal', [EmpleadoSucursalController::class, 'index'])->name('empleados.index');
        Route::get('/empleados-sucursal', [EmpleadoSucursalController::class, 'index'])->name('empleados.index');
        Route::post('/empleados-sucursal', [EmpleadoSucursalController::class, 'store'])->name('empleados.store');



    });
});

// ==========================================
// 5. RUTAS PARA ADMINISTRADORES Y AFILIADOS ($roles[0] y $roles[1])
// ==========================================
Route::middleware(['auth', "rol:{$roles[0]},{$roles[1]}"])->group(function () {
    // --- Módulo de Establecimientos, Departamentos y Municipios ---
    Route::prefix('establecimientos')->name('establecimientos.')->group(function () {
        Route::get('/', [EstablecimientoController::class, 'index'])->name('index');
        Route::put('/{id}', [EstablecimientoController::class, 'update'])->name('update');
        Route::get('/exportar/excel', [EstablecimientoController::class, 'exportarExcel'])->name('excel');
        Route::get('/exportar/pdf', [EstablecimientoController::class, 'exportarPdf'])->name('pdf');
    });

    Route::post('/establecimientos', [EstablecimientoController::class, 'store'])->name('establecimientos.store');

});
