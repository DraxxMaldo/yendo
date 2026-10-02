<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ActualizarUsuarioRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        // Obtenemos el ID del usuario que viene en la ruta (ej. /usuarios/{id})
        $idUsuario = $this->route('id');

        return [
            // Reglas para PerfilPersona
            'nombres'            => ['required', 'string', 'max:100'],
            'apellidos'          => ['required', 'string', 'max:100'],
            'telefono'           => ['required', 'string', 'max:20'],

            // Reglas para UsuarioCredencial
            'correo_electronico' => [
                'required',
                'email',
                'max:150',
                // Ignoramos el correo del usuario actual para que no dé error de duplicado[cite: 11]
                Rule::unique('usuarios_credenciales', 'correo_electronico')->ignore($idUsuario, 'id_usuario')
            ],
            // La contraseña es opcional al actualizar ('nullable')[cite: 11]
            'contrasenha'        => ['nullable', 'string', 'min:8'],
            'id_rol'             => ['required', 'integer', 'exists:roles,id_rol'],
        ];
    }

    public function messages(): array
    {
        return [
            'nombres.required' => 'El campo de nombres es obligatorio.',
            'apellidos.required' => 'El campo de apellidos es obligatorio.',
            'telefono.required' => 'El número de teléfono es obligatorio.',
            'correo_electronico.required' => 'Debe proporcionar un correo electrónico.',
            'correo_electronico.unique'   => 'Este correo electrónico ya está registrado por otro usuario.',
            'contrasenha.min'      => 'Si decide cambiar la contraseña, debe tener al menos 8 caracteres.',
            'id_rol.required' => 'Debe seleccionar un rol.',
            'id_rol.exists'   => 'El rol seleccionado no es válido.',
        ];
    }
}
