<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;

class TipoEnvio extends Model
{
    protected $table = 'tipos_envio';
    protected $primaryKey = 'id_tipo_envio';
    public $timestamps = false;

    protected $fillable = [
        'nombre_tipo',
    ];

    public static function validaciones($id = null): array
    {
        return [
            'nombre_tipo' => ['required', 'string', 'max:50'],
        ];
    }

    public static function rules(array $datos, $id = null): array
    {
        return Validator::make($datos, static::validaciones($id))->validate();
    }

    // LECTURA
    public static function mostrarTodos(): Collection
    {
        return TipoEnvio::orderBy('id_tipo_envio', 'ASC')->get();
    }

    public static function contarTodos(): int
    {
        return TipoEnvio::count();
    }

    // INSERTAR, MODIFICAR, ELIMINAR
    public static function insertar(array $datos): TipoEnvio
    {
        return TipoEnvio::create($datos);
    }

    public static function buscarXId(int $id): ?TipoEnvio
    {
        return TipoEnvio::find($id);
    }

    public static function actualizar(array $datos, int $id): bool
    {
        return TipoEnvio::find($id)->update($datos);
    }

    public static function eliminar(int $id): bool
    {
        return TipoEnvio::find($id)->delete();
    }
}
