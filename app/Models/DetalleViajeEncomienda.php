<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class DetalleViajeEncomienda extends Model
{
    protected $table = 'detalle_viaje_encomiendas';
    public $incrementing = false; // Importante para llaves compuestas
    public $timestamps = false;

    protected $fillable = [
        'id_viaje',
        'id_encomienda',
        'resultado_entrega',
    ];

    protected $casts = [
        'id_viaje' => 'integer',
        'id_encomienda' => 'integer',
    ];

    public static function validaciones($id = null): array
    {
        return [
            'id_viaje' => ['required', 'integer', 'exists:manifiestos_viaje,id_viaje'],
            'id_encomienda' => ['required', 'integer', 'exists:encomiendas,id_encomienda'],
            'resultado_entrega' => [
                'required',
                'string',
                'max:50',
                Rule::in(['Entregado con éxito', 'Devuelto a Bodega', 'Dañado', 'Pendiente'])
            ],
        ];
    }

    public static function rules(array $datos, $id = null): array
    {
        return Validator::make($datos, static::validaciones($id))->validate();
    }

    // RELACIONES[cite: 5]
    public function viaje()
    {
        return $this->belongsTo(ManifiestoViaje::class, 'id_viaje', 'id_viaje');
    }

    public function encomienda()
    {
        return $this->belongsTo(Encomienda::class, 'id_encomienda', 'id_encomienda');
    }

    // LECTURA
    public static function mostrarTodos(): Collection
    {
        return DetalleViajeEncomienda::with(['viaje', 'encomienda'])->get();
    }

    public static function contarTodos(): int
    {
        return DetalleViajeEncomienda::count();
    }

    // INSERTAR
    public static function insertar(array $datos): DetalleViajeEncomienda
    {
        return DetalleViajeEncomienda::create($datos);
    }

    // BÚSQUEDA Y ACTUALIZACIÓN COMPUESTA
    public static function buscarXCompuesta(int $id_viaje, int $id_encomienda): ?DetalleViajeEncomienda
    {
        return DetalleViajeEncomienda::where('id_viaje', $id_viaje)
            ->where('id_encomienda', $id_encomienda)
            ->first();
    }

    public static function actualizarCompuesta(array $datos, int $id_viaje, int $id_encomienda): bool
    {
        return DetalleViajeEncomienda::where('id_viaje', $id_viaje)
            ->where('id_encomienda', $id_encomienda)
            ->update($datos);
    }

    public static function eliminarCompuesta(int $id_viaje, int $id_encomienda): bool
    {
        return DetalleViajeEncomienda::where('id_viaje', $id_viaje)
            ->where('id_encomienda', $id_encomienda)
            ->delete();
    }
}
