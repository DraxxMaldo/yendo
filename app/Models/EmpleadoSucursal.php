<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class EmpleadoSucursal extends Model
{
    protected $table = 'empleados_sucursal';
    protected $primaryKey = 'id_usuario';
    public $incrementing = false;
    protected $keyType = 'int';
    public $timestamps = false;

    protected $fillable = [
        'id_usuario',
        'id_establecimiento',
    ];

    protected $casts = [
        'id_usuario' => 'integer',
        'id_establecimiento' => 'integer',
    ];

    public static function validaciones($id = null): array
    {
        return [
            'id_usuario' => [
                $id ? 'nullable' : 'required',
                'integer',
                'exists:usuarios_credenciales,id_usuario',
                Rule::unique('empleados_sucursal', 'id_usuario')->ignore($id, 'id_usuario')
            ],
            'id_establecimiento' => ['required', 'integer', 'exists:establecimientos,id_establecimiento'],
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

    public function establecimiento()
    {
        return $this->belongsTo(Establecimiento::class, 'id_establecimiento', 'id_establecimiento');
    }

    // LECTURA
    public static function mostrarTodos(): Collection
    {
        return EmpleadoSucursal::with(['credenciales', 'establecimiento'])->get();
    }

    public static function contarTodos(): int
    {
        return EmpleadoSucursal::count();
    }

    // INSERTAR, MODIFICAR, ELIMINAR
    public static function insertar(array $datos): EmpleadoSucursal
    {
        return EmpleadoSucursal::create($datos);
    }

    public static function buscarXId(int $id): ?EmpleadoSucursal
    {
        return EmpleadoSucursal::find($id);
    }

    public static function actualizar(array $datos, int $id): bool
    {
        return EmpleadoSucursal::find($id)->update($datos);
    }

    public static function eliminar(int $id): bool
    {
        return EmpleadoSucursal::find($id)->delete();
    }
}
