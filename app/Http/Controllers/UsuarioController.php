<?php

namespace App\Http\Controllers;

use App\Models\UsuarioCredencial;
use App\Models\Rol;
use Illuminate\Http\Request;
use App\Http\Requests\InsertarUsuarioRequest;
use App\Http\Requests\ActualizarUsuarioRequest;
use Illuminate\Support\Facades\DB;

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
        // 1. Obtenemos los datos limpios y validados del FormRequest
        $datos = $request->validated();

        // 2. Transacción para asegurar que ambas tablas se guarden juntas
        DB::transaction(function () use ($datos) {

            // Insertamos la credencial
            $credencial = UsuarioCredencial::create([
                'correo_electronico' => $datos['correo_electronico'],
                'contrasenha'        => bcrypt($datos['contrasenha']),
                'id_rol'             => $datos['id_rol'],
                'estado_cuenta'      => true,
            ]);

            // Usamos la relación perfil() para insertar los datos biográficos
            $credencial->perfil()->create([
                'nombres'   => $datos['nombres'],
                'apellidos' => $datos['apellidos'],
                'telefono'  => $datos['telefono'],
            ]);
        });

        return redirect()->route('usuarios.index')
            ->with('success', "Usuario {$datos['nombres']} ingresado correctamente");
    }


    public function desactivar($id)
    {
        \App\Models\UsuarioCredencial::actualizar(['estado_cuenta' => false], $id);

        // Retornamos a la vista anterior disparando tu Toast de éxito
        return back()->with('success', 'El usuario ha sido desactivado exitosamente.');
    }


    public function actualizar(ActualizarUsuarioRequest $request, $id)
    {
        // 1. Obtenemos solo los datos que pasaron la validación
        $datos = $request->validated();

        // 2. Preparamos los datos para la tabla usuarios_credenciales
        $datosCredencial = [
            'correo_electronico' => $datos['correo_electronico'],
            'id_rol'             => $datos['id_rol'],
        ];

        // Solo encriptamos y actualizamos la contraseña si el usuario escribió una nueva
        if (!empty($datos['contrasenha'])) {
            $datosCredencial['contrasenha'] = Hash::make($datos['contrasenha']);
        }

        // Usamos tu método estático para actualizar la credencial
        UsuarioCredencial::actualizar($datosCredencial, $id);

        // 3. Preparamos y actualizamos los datos para la tabla perfiles_personas
        $datosPerfil = [
            'nombres'   => $datos['nombres'],
            'apellidos' => $datos['apellidos'],
            'telefono'  => $datos['telefono'],
        ];

        // Buscamos al usuario y actualizamos su relación (Perfil)
        $usuario = UsuarioCredencial::buscarXId($id);
        $usuario->perfil()->update($datosPerfil);

        // 4. Redirigimos de vuelta con el Toast de éxito
        return back()->with('success', 'Los datos del usuario se han actualizado correctamente.');
    }

}
