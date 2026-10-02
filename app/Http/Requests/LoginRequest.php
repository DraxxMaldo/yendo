<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'correo' => ['required', 'email'],
            'clave'  => ['required', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'correo.required' => 'El correo electrónico es obligatorio para ingresar.',
            'correo.email'    => 'Ingresa un formato de correo válido.',
            'clave.required'  => 'Debes ingresar tu contraseña.',
        ];
    }
}
