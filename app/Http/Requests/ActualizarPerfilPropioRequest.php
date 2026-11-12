<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ActualizarPerfilPropioRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nombres'        => ['required', 'string', 'max:100'],
            'apellidos'      => ['required', 'string', 'max:100'],
            'telefono'       => ['required', 'string', 'max:8'], // Máximo 8 caracteres según tu requerimiento
            'nombre_negocio' => ['nullable', 'string', 'max:150'],
        ];
    }

    public function messages(): array
    {
        return [
            'nombres.required'   => 'El campo de nombres es obligatorio.',
            'apellidos.required' => 'El campo de apellidos es obligatorio.',
            'telefono.required'  => 'El número de teléfono es obligatorio.',
            'telefono.max'       => 'El teléfono no debe exceder los 8 caracteres.',
        ];
    }
}
