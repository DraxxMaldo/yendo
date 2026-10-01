<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;

class Establecimiento extends Model
{
    protected $table = 'establecimientos';
    protected $primaryKey = 'id_establecimiento';
    public $timestamps = false;

    protected $fillable = [
        'nombre',
        'id_municipio',
        'direccion_exacta',
    ];

    protected $casts = [
        'id_municipio' => 'integer',
    ];

    public static function validaciones($id = null): array
    {
        return [
            'nombre' => ['required', 'string', 'max:100'],
            'id_municipio' => ['required', 'integer', 'exists:municipios,id_municipio'],
            'direccion_exacta' => ['required', 'string'],
        ];
    }

    public static function rules(array $datos, $id = null): array
    {
        return Validator::make($datos, static::validaciones($id))->validate();
    }

    // RELACIONES
    public function municipio()
    {
        return $this->belongsTo(Municipio::class, 'id_municipio', 'id_municipio');
    }

    public function empleados()
    {
        return $this->hasMany(EmpleadoSucursal::class, 'id_establecimiento', 'id_establecimiento');
    }

    // LECTURA
    public static function mostrarTodos(): Collection
    {
        return Establecimiento::with('municipio')->orderBy('nombre', 'ASC')->get();
    }

    public static function contarTodos(): int
    {
        return Establecimiento::count();
    }

    // INSERTAR, MODIFICAR, ELIMINAR
    public static function insertar(array $datos): Establecimiento
    {
        return Establecimiento::create($datos);
    }

    public static function buscarXId(int $id): ?Establecimiento
    {
        return Establecimiento::find($id);
    }

    public static function actualizar(array $datos, int $id): bool
    {
        return Establecimiento::find($id)->update($datos);
    }

    public static function eliminar(int $id): bool
    {
        return Establecimiento::find($id)->delete();
    }
}
