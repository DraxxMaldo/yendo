<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ActualizarEstablecimientoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nombre'           => ['required', 'string', 'max:100'],
            'id_distrito'      => ['required', 'integer', 'exists:distritos,id_distrito'],
            'direccion_exacta' => ['required', 'string'],
            'imagen'           => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
        ];
    }

    public function messages(): array
    {
        return [
            'nombre.required' => 'El nombre del establecimiento es obligatorio.',
            'nombre.string'   => 'El nombre del establecimiento debe ser texto válido.',
            'nombre.max'      => 'El nombre no puede exceder los 100 caracteres.',

            'id_distrito.required' => 'Debe seleccionar el distrito donde se ubica el establecimiento.',
            'id_distrito.integer'  => 'El formato del distrito seleccionado no es válido.',
            'id_distrito.exists'   => 'El distrito seleccionado no existe.',

            'direccion_exacta.required' => 'La dirección exacta es obligatoria.',

            'imagen.image' => 'El archivo adjunto debe ser una imagen válida.',
            'imagen.mimes' => 'Formato no permitido. Use jpeg, png, jpg o webp.',
            'imagen.max'   => 'La imagen no debe pesar más de 2MB.',
        ];
    }
}
