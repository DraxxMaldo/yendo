<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\RegistroController;
use App\Http\Controllers\DepartamentoController;
use App\Http\Controllers\EstablecimientoController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MunicipioController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\PerfilController;
use Illuminate\Support\Facades\Route;


Route::get('/', [HomeController::class, 'index'])->name('home');

//Rutas para registrarse
Route::post('/registro/enviar-codigo', [RegistroController::class, 'enviarCodigo'])->name('registro.enviar_codigo');
Route::post('/registro/verificar-codigo', [RegistroController::class, 'verificarCodigo'])->name('registro.verificar_codigo');
Route::post('/registro/finalizar', [RegistroController::class, 'finalizarRegistro'])->name('registro.finalizar');

// Ruta oara listar usuarios
Route::get('/usuarios', [UsuarioController::class, 'index'])->name('usuarios.index');
Route::post('/usuarios/insertar', [UsuarioController::class, 'insertarUsuario'])->name('usuarios.insertar');
// Actualizar usuario existente
Route::put('/usuarios/{id}', [UsuarioController::class, 'actualizar'])->name('usuarios.actualizar');



// Ruta para el Login
Route::get('/login', function () {return view('auth.login');})->name('login');
//Ruta para vista de register
route::get( '/registro', function () {return view('auth.registro');})->name('registro');


// Rutas de Autenticación
Route::get('/login', [AuthController::class, 'index'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


// RUTAS DE USUARIO
//Ruta para desactivar un usuario
Route::patch('/usuarios/{id}/desactivar', [UsuarioController::class, 'desactivar'])->name('usuarios.desactivar');

// Ruta para Reactivar Usuario
Route::patch('/usuarios/{id}/activar', [App\Http\Controllers\UsuarioController::class, 'activar'])->name('usuarios.activar');

Route::get('/bitacora', [\App\Http\Controllers\BitacoraController::class, 'index'])->middleware('auth')->name('bitacora');


// Rutas de Establecimientos

Route::get('/establecimientos', [EstablecimientoController::class, 'index'])->name('establecimientos.index');
// NUEVAS RUTAS POST PARA GUARDAR (Usando sus respectivos controladores)[cite: 14]
Route::post('/departamentos', [DepartamentoController::class, 'store'])->name('departamentos.store');
Route::post('/municipios', [MunicipioController::class, 'store'])->name('municipios.store');
Route::post('/establecimientos', [EstablecimientoController::class, 'store'])->name('establecimientos.store');







// Ruta para exportar establecimientos a Excel
Route::get('/establecimientos/exportar/excel', [EstablecimientoController::class, 'exportarExcel'])->name('establecimientos.excel');

// Ruta para exportar en PDF
Route::get('/establecimientos/exportar/pdf', [EstablecimientoController::class, 'exportarPdf'])->name('establecimientos.pdf');

//Ruta para actualizar Establecimiento
Route::put('/establecimientos/{id}', [EstablecimientoController::class, 'update'])->name('establecimientos.update');


// Ruta para rediccionar a vista de recuperar contraseña

Route::get('/recuperar-contrasena', function () {return view('Auth.recuperar-contrasenha');})->name('password.request');
Route::post('/recuperar-contrasena', [\App\Http\Controllers\Auth\AuthController::class, 'enviarNuevaContrasena'])->name('password.email');



Route::middleware(['auth'])->group(function () {


    Route::get('/perfil', [PerfilController::class, 'index'])->name('perfil.index');

    //Ruta para actualizar foto de perfil
    Route::post('/perfil/actualizar-foto', [PerfilController::class, 'actualizarFoto'])->name('perfil.actualizar_foto');
    //Ruta oara actualizar información en la vista de perfil
    Route::put('/perfil/actualizar-informacion', [PerfilController::class, 'actualizarInformacion'])->name('perfil.actualizar_informacion');
});
