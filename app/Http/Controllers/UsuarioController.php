<?php

namespace App\Http\Controllers;

use App\Models\UsuarioCredencial;
use App\Models\Rol;
use Illuminate\Http\Request;

class UsuarioController extends Controller
{
    /**
     * Listado general de usuarios.
     */
    public function index()
    {
        // Traemos las credenciales junto con su perfil (1 a 1) y su rol (N a 1)[cite: 6, 7]
        $usuarios = UsuarioCredencial::with(['perfil', 'rol'])
            ->orderBy('id_usuario', 'DESC')
            ->get();

        // Traemos los roles para llenar el <select> del modal "Registrar Nuevo Usuario"
        $roles = Rol::orderBy('nombre_rol', 'ASC')->get();

        // Retornamos la vista ubicada en resources/views/Usuarios/usuarios.blade.php
        // y le pasamos las variables usando compact()
        return view('Usuarios.usuarios', compact('usuarios', 'roles'));
    }
}
