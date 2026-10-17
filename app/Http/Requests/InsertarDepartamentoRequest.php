<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InsertarDepartamentoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nombre_departamento' => ['required', 'string', 'max:100'],
        ];
    }

    public function messages(): array
    {
        return [
            'nombre_departamento.required' => 'El nombre del departamento es obligatorio.',
            'nombre_departamento.string'   => 'El nombre del departamento debe ser texto válido.',
            'nombre_departamento.max'      => 'El nombre del departamento no puede exceder los 100 caracteres.',
        ];
    }
}
