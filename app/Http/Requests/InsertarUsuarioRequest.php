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
        return true;
    }

    /**
     * Reglas de validación aplicadas a la petición.
     */
    public function rules(): array
    {
        return [
            // Reglas para PerfilPersona
            'nombres'            => ['required', 'string', 'max:100'],
            'apellidos'          => ['required', 'string', 'max:100'],
            'telefono'           => ['required', 'string', 'max:20'],

            // Reglas para UsuarioCredencial
            'correo_electronico' => ['required', 'email', 'max:150', 'unique:usuarios_credenciales,correo_electronico'],
            'contrasenha'        => ['required', 'string', 'min:8'],
            'id_rol'             => ['required', 'integer', 'exists:roles,id_rol'],
        ];
    }

    /**
     * Mensajes personalizados en español para cada regla de validación.
     */
    public function messages(): array
    {
        return [
            // Mensajes para 'nombres'
            'nombres.required' => 'El campo de nombres es obligatorio.',
            'nombres.string'   => 'Los nombres deben ser texto válido.',
            'nombres.max'      => 'Los nombres no pueden exceder los 100 caracteres.',

            // Mensajes para 'apellidos'
            'apellidos.required' => 'El campo de apellidos es obligatorio.',
            'apellidos.string'   => 'Los apellidos deben ser texto válido.',
            'apellidos.max'      => 'Los apellidos no pueden exceder los 100 caracteres.',

            // Mensajes para 'telefono'
            'telefono.required' => 'El número de teléfono es obligatorio.',
            'telefono.string'   => 'El teléfono debe tener un formato de texto válido.',
            'telefono.max'      => 'El teléfono no puede exceder los 20 caracteres.',

            // Mensajes para 'correo_electronico'
            'correo_electronico.required' => 'Debe proporcionar un correo electrónico.',
            'correo_electronico.email'    => 'El correo electrónico debe tener un formato válido (ejemplo@dominio.com).',
            'correo_electronico.max'      => 'El correo electrónico no puede exceder los 150 caracteres.',
            'correo_electronico.unique'   => 'Este correo electrónico ya está registrado en el sistema.',

            // Mensajes para 'contrasenha'
            'contrasenha.required' => 'La contraseña es obligatoria.',
            'contrasenha.string'   => 'La contraseña debe tener un formato válido.',
            'contrasenha.min'      => 'La contraseña debe tener al menos 8 caracteres por seguridad.',

            // Mensajes para 'id_rol'
            'id_rol.required' => 'Debe seleccionar un rol para el usuario.',
        ];
    }
}
