<?php

namespace App\Http\Controllers;

use App\Models\UsuarioCredencial;
use App\Models\Rol;
use Illuminate\Http\Request;
use App\Http\Requests\InsertarUsuarioRequest;
use App\Http\Requests\ActualizarUsuarioRequest;
use Illuminate\Support\Facades\DB;
use App\Models\Bitacora;



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

    public function insertarUsuario(InsertarUsuarioRequest $request)
    {
        $datos = $request->validated();

        $credencial = DB::transaction(function () use ($datos) {
            $credencial = UsuarioCredencial::create([
                'correo_electronico' => $datos['correo_electronico'],
                'contrasenha'        => bcrypt($datos['contrasenha']),
                'id_rol'             => $datos['id_rol'],
                'estado_cuenta'      => true,
            ]);

            $credencial->perfil()->create([
                'nombres'   => $datos['nombres'],
                'apellidos' => $datos['apellidos'],
                'telefono'  => $datos['telefono'],
            ]);

            return $credencial;
        });
        // ---> REGISTRO DE AUDITORÍA: 1.a. CREADO - C
        Bitacora::registrar(
            Bitacora::CREADO,
            'Catálogo de Usuarios',
            $credencial->id_usuario,
            "Usuario creado: {$datos['correo_electronico']}"
        );

        return redirect()->route('usuarios.index')
            ->with('success', "Usuario {$datos['nombres']} ingresado correctamente");
    }


    public function desactivar($id)
    {
        \App\Models\UsuarioCredencial::actualizar(['estado_cuenta' => false], $id);

        $usuario = \App\Models\UsuarioCredencial::buscarXId($id);

        // ---> REGISTRO DE AUDITORÍA: 1.c. ELIMINADO (Desactivado)
        Bitacora::registrar(
            Bitacora::ELIMINADO,
            'Catálogo de Usuarios',
            $id,
            "Usuario desactivado del sistema: {$usuario->correo_electronico}"
        );

        return back()->with('success', 'El usuario ha sido desactivado exitosamente.');
    }


    public function actualizar(ActualizarUsuarioRequest $request, $id)
    {
        $datos = $request->validated();

        $datosCredencial = [
            'correo_electronico' => $datos['correo_electronico'],
            'id_rol'             => $datos['id_rol'],
        ];
        if (!empty($datos['contrasenha'])) {
            $datosCredencial['contrasenha'] = \Illuminate\Support\Facades\Hash::make($datos['contrasenha']);
        }
        UsuarioCredencial::actualizar($datosCredencial, $id);
        $datosPerfil = [
            'nombres'   => $datos['nombres'],
            'apellidos' => $datos['apellidos'],
            'telefono'  => $datos['telefono'],
        ];
        $usuario = UsuarioCredencial::buscarXId($id);
        $usuario->perfil()->update($datosPerfil);
        // ---> REGISTRO DE AUDITORÍA: 1.b. ACTUALIZADO
        Bitacora::registrar(
            Bitacora::ACTUALIZADO,
            'Catálogo de Usuarios',
            $id,
            "Usuario actualizado: {$datos['correo_electronico']}"
        );
        return back()->with('success', 'Los datos del usuario se han actualizado correctamente.');
    }

}
