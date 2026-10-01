<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;

class Rol extends Model
{
    protected $table = 'roles';
    protected $primaryKey = 'id_rol';
    public $timestamps = false; // Desactivado si no usaste $table->timestamps() en la migración

    protected $fillable = [
        'nombre_rol',
        'descripcion',
    ];

    public static function validaciones($id = null): array
    {
        return [
            'nombre_rol' => ['required', 'string', 'max:50'],
            'descripcion' => ['required', 'string', 'max:255'],
        ];
    }

    public static function rules(array $datos, $id = null): array
    {
        return Validator::make($datos, static::validaciones($id))->validate();
    }

    // RELACIONES
    public function usuarios()
    {
        return $this->hasMany(UsuarioCredencial::class, 'id_rol', 'id_rol');
    }

    // LECTURA
    public static function mostrarTodos(): Collection
    {
        return Rol::orderBy('id_rol', 'ASC')->get();
    }

    public static function contarTodos(): int
    {
        return Rol::count();
    }

    // INSERTAR
    public static function insertar(array $datos): Rol
    {
        return Rol::create($datos);
    }

    // MODIFICAR
    public static function buscarXId(int $id): ?Rol
    {
        return Rol::find($id);
    }

    public static function actualizar(array $datos, int $id): bool
    {
        return Rol::find($id)->update($datos);
    }

    // ELIMINAR
    public static function eliminar(int $id): bool
    {
        return Rol::find($id)->delete();
    }
}
