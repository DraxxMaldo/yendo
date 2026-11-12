<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VerificarCodigoPasswordRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'codigo' => ['required', 'digits:6']
        ];
    }

    public function messages(): array
    {
        return [
            'codigo.required' => 'El código de verificación es obligatorio.',
            'codigo.digits' => 'El código debe contener exactamente 6 dígitos.'
        ];
    }
}
