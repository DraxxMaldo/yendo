<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class PerfilPersona extends Model
{
    protected $table = 'perfiles_personas';
    protected $primaryKey = 'id_usuario';
    public $incrementing = false; // Importante porque la PK es heredada (FK)
    protected $keyType = 'int';
    public $timestamps = false;

    protected $fillable = [
        'id_usuario',
        'nombres',
        'apellidos',
        'telefono',
    ];

    public static function validaciones($id = null): array
    {
        return [
            'id_usuario' => [
                $id ? 'nullable' : 'required',
                'integer',
                'exists:usuarios_credenciales,id_usuario',
                Rule::unique('perfiles_personas', 'id_usuario')->ignore($id, 'id_usuario')
            ],
            'nombres' => ['required', 'string', 'max:100'],
            'apellidos' => ['required', 'string', 'max:100'],
            'telefono' => ['required', 'string', 'max:20'],
        ];
    }

    public static function rules(array $datos, $id = null): array
    {
        return Validator::make($datos, static::validaciones($id))->validate();
    }

    // RELACIONES
    public function credenciales()
    {
        return $this->belongsTo(UsuarioCredencial::class, 'id_usuario', 'id_usuario');
    }

    // LECTURA
    public static function mostrarTodos(): Collection
    {
        return PerfilPersona::orderBy('apellidos', 'ASC')->get();
    }

    public static function contarTodos(): int
    {
        return PerfilPersona::count();
    }

    // INSERTAR, MODIFICAR, ELIMINAR
    public static function insertar(array $datos): PerfilPersona
    {
        return PerfilPersona::create($datos);
    }

    public static function buscarXId(int $id): ?PerfilPersona
    {
        return PerfilPersona::find($id);
    }

    public static function actualizar(array $datos, int $id): bool
    {
        return PerfilPersona::find($id)->update($datos);
    }

    public static function eliminar(int $id): bool
    {
        return PerfilPersona::find($id)->delete();
    }
}
