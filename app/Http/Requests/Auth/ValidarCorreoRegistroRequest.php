<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class ValidarCorreoRegistroRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Permitimos que cualquier invitado haga esta validación
    }

    public function rules(): array
    {
        return [
            //  Verifica que el correo no esté repetido
            'correo' => ['required', 'email', 'unique:usuarios_credenciales,correo_electronico']
        ];
    }

    public function messages(): array
    {
        return [
            'correo.required' => 'El campo de correo electrónico es obligatorio.',
            'correo.email'    => 'Por favor, ingrese un formato de correo válido (ejemplo@correo.com).',
            'correo.unique'   => 'Este correo ya se encuentra registrado. Por favor, inicia sesión.',
        ];
    }
}
