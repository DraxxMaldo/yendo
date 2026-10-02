<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UsuarioController;

Route::get('/', [HomeController::class, 'index'])->name('home');

// Ruta oara listar usuarios

Route::get('/usuarios', [UsuarioController::class, 'index'])->name('usuarios.index');
