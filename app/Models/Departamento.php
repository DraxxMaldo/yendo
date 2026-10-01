<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;

class Departamento extends Model
{
    protected $table = 'departamentos';
    protected $primaryKey = 'id_departamento';
    public $timestamps = false;

    protected $fillable = [
        'nombre_departamento',
    ];

    public static function validaciones($id = null): array
    {
        return [
            'nombre_departamento' => ['required', 'string', 'max:100'],
        ];
    }

    public static function rules(array $datos, $id = null): array
    {
        return Validator::make($datos, static::validaciones($id))->validate();
    }

    // RELACIONES
    public function municipios()
    {
        return $this->hasMany(Municipio::class, 'id_departamento', 'id_departamento');
    }

    // LECTURA
    public static function mostrarTodos(): Collection
    {
        return Departamento::orderBy('nombre_departamento', 'ASC')->get();
    }

    public static function contarTodos(): int
    {
        return Departamento::count();
    }

    // INSERTAR, MODIFICAR, ELIMINAR
    public static function insertar(array $datos): Departamento
    {
        return Departamento::create($datos);
    }

    public static function buscarXId(int $id): ?Departamento
    {
        return Departamento::find($id);
    }

    public static function actualizar(array $datos, int $id): bool
    {
        return Departamento::find($id)->update($datos);
    }

    public static function eliminar(int $id): bool
    {
        return Departamento::find($id)->delete();
    }
}
