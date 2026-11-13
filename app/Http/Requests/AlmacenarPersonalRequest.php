<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AlmacenarPersonalRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'id_rol' => ['required', 'integer', 'in:4,5,6'], // 4: Cajero, 5: Logística, 6: Repartidor
            'correo_electronico' => ['required', 'string', 'email', 'max:150', 'unique:usuarios_credenciales,correo_electronico'],
            'id_establecimiento' => ['required', 'integer', 'exists:establecimientos,id_establecimiento'],
        ];
    }

    public function messages(): array
    {
        return [
            'id_rol.required' => 'Debe seleccionar un rol para el personal.',
            'id_rol.in' => 'El rol seleccionado no es válido.',
            'correo_electronico.required' => 'El correo electrónico es obligatorio.',
            'correo_electronico.email' => 'Debe proporcionar una dirección de correo válida.',
            'correo_electronico.unique' => 'Este correo electrónico ya se encuentra registrado en el sistema.',
            'id_establecimiento.required' => 'Debe seleccionar un establecimiento asociado.',
            'id_establecimiento.exists' => 'El establecimiento seleccionado no es válido.',
        ];
    }
}
