<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\EstablecimientoController;
use App\Http\Controllers\DepartamentoController;
use App\Http\Controllers\MunicipioController;



Route::get('/', [HomeController::class, 'index'])->name('home');



// Ruta oara listar usuarios

Route::get('/usuarios', [UsuarioController::class, 'index'])->name('usuarios.index');
Route::post('/usuarios/insertar', [UsuarioController::class, 'insertarUsuario'])->name('usuarios.insertar');
// Actualizar usuario existente
Route::put('/usuarios/{id}', [UsuarioController::class, 'actualizar'])->name('usuarios.actualizar');



// Ruta de diseño para el Login
Route::get('/login', function () {return view('auth.login');})->name('login');


// Rutas de Autenticación
Route::get('/login', [AuthController::class, 'index'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


Route::patch('/usuarios/{id}/desactivar', [UsuarioController::class, 'desactivar'])->name('usuarios.desactivar');

Route::get('/bitacora', [\App\Http\Controllers\BitacoraController::class, 'index'])->middleware('auth')->name('bitacora');


// Rutas de Establecimientos

Route::get('/establecimientos', [EstablecimientoController::class, 'index'])->name('establecimientos.index');
// NUEVAS RUTAS POST PARA GUARDAR (Usando sus respectivos controladores)[cite: 14]
Route::post('/departamentos', [DepartamentoController::class, 'store'])->name('departamentos.store');
Route::post('/municipios', [MunicipioController::class, 'store'])->name('municipios.store');
Route::post('/establecimientos', [EstablecimientoController::class, 'store'])->name('establecimientos.store');



// Ruta para exportar establecimientos a Excel
Route::get('/establecimientos/exportar/excel', [\App\Http\Controllers\EstablecimientoController::class, 'exportarExcel'])->name('establecimientos.excel');

// Ruta para exportar en PDF
Route::get('/establecimientos/exportar/pdf', [\App\Http\Controllers\EstablecimientoController::class, 'exportarPdf'])->name('establecimientos.pdf');

