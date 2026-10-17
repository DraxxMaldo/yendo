<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InsertarEstablecimientoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nombre'           => ['required', 'string', 'max:100'],
            'id_municipio'     => ['required', 'integer', 'exists:municipios,id_municipio'],
            'direccion_exacta' => ['required', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'nombre.required' => 'El nombre del establecimiento es obligatorio.',
            'nombre.string'   => 'El nombre del establecimiento debe ser texto válido.',
            'nombre.max'      => 'El nombre del establecimiento no puede exceder los 100 caracteres.',

            'id_municipio.required' => 'Debe seleccionar el municipio donde se ubica el establecimiento.',
            'id_municipio.integer'  => 'El formato del municipio seleccionado no es válido.',
            'id_municipio.exists'   => 'El municipio seleccionado no existe en nuestros registros.',

            'direccion_exacta.required' => 'La dirección exacta es obligatoria para ubicar la sucursal.',
            'direccion_exacta.string'   => 'La dirección exacta debe ser texto válido.',
        ];
    }
}
