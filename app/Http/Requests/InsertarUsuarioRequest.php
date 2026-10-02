<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InsertarUsuarioRequest extends FormRequest
{
    /**
     * Determina si el usuario está autorizado a hacer esta petición.
     */
    public function authorize(): bool
    {
        return true; // Cambiar a true para permitir la petición
    }

    /**
     * Reglas de validación aplicadas a la petición.
     */
    public function rules(): array
    {
        return [
            // Reglas para PerfilPersona[cite: 11]
            'nombres'            => ['required', 'string', 'max:100'],
            'apellidos'          => ['required', 'string', 'max:100'],
            'telefono'           => ['required', 'string', 'max:20'],

            // Reglas para UsuarioCredencial[cite: 11]
            'correo_electronico' => ['required', 'email', 'max:150', 'unique:usuarios_credenciales,correo_electronico'],
            'contrasenha'        => ['required', 'string', 'min:8'],
            'id_rol'             => ['required', 'integer', 'exists:roles,id_rol'],
        ];
    }

    /**
     * Mensajes personalizados en español (opcional pero recomendado).
     */
    public function messages(): array
    {
        return [
            'correo_electronico.unique' => 'Este correo electrónico ya está registrado en el sistema.',
            'contrasenha.min' => 'La contraseña debe tener al menos 8 caracteres.',
            'id_rol.exists' => 'El rol seleccionado no es válido.',
        ];
    }
}
