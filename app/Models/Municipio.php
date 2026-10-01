<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;

class Municipio extends Model
{
    protected $table = 'municipios';
    protected $primaryKey = 'id_municipio';
    public $timestamps = false;

    protected $fillable = [
        'id_departamento',
        'nombre_municipio',
    ];

    protected $casts = [
        'id_departamento' => 'integer',
    ];

    public static function validaciones($id = null): array
    {
        return [
            'id_departamento' => ['required', 'integer', 'exists:departamentos,id_departamento'],
            'nombre_municipio' => ['required', 'string', 'max:100'],
        ];
    }

    public static function rules(array $datos, $id = null): array
    {
        return Validator::make($datos, static::validaciones($id))->validate();
    }

    // RELACIONES
    public function departamento()
    {
        return $this->belongsTo(Departamento::class, 'id_departamento', 'id_departamento');
    }

    // LECTURA
    public static function mostrarTodos(): Collection
    {
        return Municipio::with('departamento')->orderBy('nombre_municipio', 'ASC')->get();
    }

    public static function contarTodos(): int
    {
        return Municipio::count();
    }

    // INSERTAR, MODIFICAR, ELIMINAR
    public static function insertar(array $datos): Municipio
    {
        return Municipio::create($datos);
    }

    public static function buscarXId(int $id): ?Municipio
    {
        return Municipio::find($id);
    }

    public static function actualizar(array $datos, int $id): bool
    {
        return Municipio::find($id)->update($datos);
    }

    public static function eliminar(int $id): bool
    {
        return Municipio::find($id)->delete();
    }
}
