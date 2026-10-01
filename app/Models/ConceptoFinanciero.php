<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ConceptoFinanciero extends Model
{
    protected $table = 'conceptos_financieros';
    protected $primaryKey = 'id_concepto';
    public $timestamps = false;

    protected $fillable = [
        'nombre_concepto',
        'tipo_movimiento',
    ];

    public static function validaciones($id = null): array
    {
        return [
            'nombre_concepto' => ['required', 'string', 'max:100'],
            'tipo_movimiento' => ['required', 'string', 'max:10', Rule::in(['Ingreso', 'Egreso'])],
        ];
    }

    public static function rules(array $datos, $id = null): array
    {
        return Validator::make($datos, static::validaciones($id))->validate();
    }

    // LECTURA
    public static function mostrarTodos(): Collection
    {
        return ConceptoFinanciero::orderBy('id_concepto', 'ASC')->get();
    }

    public static function contarTodos(): int
    {
        return ConceptoFinanciero::count();
    }

    // INSERTAR, MODIFICAR, ELIMINAR
    public static function insertar(array $datos): ConceptoFinanciero
    {
        return ConceptoFinanciero::create($datos);
    }

    public static function buscarXId(int $id): ?ConceptoFinanciero
    {
        return ConceptoFinanciero::find($id);
    }

    public static function actualizar(array $datos, int $id): bool
    {
        return ConceptoFinanciero::find($id)->update($datos);
    }

    public static function eliminar(int $id): bool
    {
        return ConceptoFinanciero::find($id)->delete();
    }
}
