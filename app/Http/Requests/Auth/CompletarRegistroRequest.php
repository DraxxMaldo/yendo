<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class CompletarRegistroRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            // Validamos que el correo oculto siga siendo válido y único.
            'correo_final' => ['required', 'email', 'unique:usuarios_credenciales,correo_electronico'],
            'nombres'      => ['required', 'string', 'max:100'],
            'apellidos'    => ['required', 'string', 'max:100'],
            // La regla 'confirmed' busca automáticamente el campo 'password_confirmation'.
            'password'     => ['required', 'string', 'min:8', 'confirmed'],
        ];
    }

    /**
     * La autorización debe existir en la sesión del servidor, pertenecer al
     * mismo correo enviado y no haber expirado.
     */
    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator) {
            $correo = strtolower(trim((string) $this->input('correo_final')));
            $verificacion = $this->session()->get('registro_correo_verificado');

            $autorizacionValida = is_array($verificacion)
                && isset($verificacion['correo'], $verificacion['expira'])
                && hash_equals((string) $verificacion['correo'], $correo)
                && (int) $verificacion['expira'] >= now()->timestamp;

            if (!$autorizacionValida) {
                $validator->errors()->add(
                    'correo_final',
                    'Debes verificar este correo antes de completar el registro. Solicita y confirma un nuevo código.'
                );
            }
        });
    }

    public function messages(): array
    {
        return [
            'correo_final.unique' => 'Este correo ya fue registrado durante el proceso.',
            'nombres.required'    => 'Los nombres son obligatorios.',
            'apellidos.required'  => 'Los apellidos son obligatorios.',
            'password.required'   => 'La contraseña es obligatoria.',
            'password.min'        => 'La contraseña debe tener al menos 8 caracteres.',
            'password.confirmed'  => 'Las contraseñas no coinciden.',
        ];
    }
}
