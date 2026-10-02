<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\AuthController;



Route::get('/', [HomeController::class, 'index'])->name('home');

// Ruta oara listar usuarios

Route::get('/usuarios', [UsuarioController::class, 'index'])->name('usuarios.index');
Route::post('/usuarios/insertar', [UsuarioController::class, 'insertarUsuario'])->name('usuarios.insertar');




// Ruta de diseño para el Login
Route::get('/login', function () {return view('auth.login');})->name('login');


// Rutas de Autenticación
Route::get('/login', [AuthController::class, 'index'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
