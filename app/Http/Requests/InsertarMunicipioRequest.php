<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InsertarMunicipioRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'id_departamento'  => ['required', 'integer', 'exists:departamentos,id_departamento'],
            'nombre_municipio' => ['required', 'string', 'max:100'],
        ];
    }

    public function messages(): array
    {
        return [
            'id_departamento.required' => 'Debe seleccionar un departamento al cual pertenece el municipio.',
            'id_departamento.integer'  => 'El formato del departamento seleccionado no es válido.',
            'id_departamento.exists'   => 'El departamento seleccionado no existe en nuestros registros.',

            'nombre_municipio.required' => 'El nombre del municipio es obligatorio.',
            'nombre_municipio.string'   => 'El nombre del municipio debe ser texto válido.',
            'nombre_municipio.max'      => 'El nombre del municipio no puede exceder los 100 caracteres.',
        ];
    }
}
