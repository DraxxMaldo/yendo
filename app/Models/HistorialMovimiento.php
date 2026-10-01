<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class HistorialMovimiento extends Model
{
    protected $table = 'historial_movimientos';
    protected $primaryKey = 'id_movimiento';
    public $timestamps = false;

    protected $fillable = [
        'id_encomienda',
        'estado_fisico',
        'id_usuario_gestor',
        'id_establecimiento_actual',
        'fecha_hora',
    ];

    protected $casts = [
        'id_encomienda' => 'integer',
        'id_usuario_gestor' => 'integer',
        'id_establecimiento_actual' => 'integer',
        'fecha_hora' => 'datetime',
    ];

    public static function validaciones($id = null): array
    {
        return [
            'id_encomienda' => ['required', 'integer', 'exists:encomiendas,id_encomienda'],
            'estado_fisico' => [
                'required',
                'string',
                'max:50',
                Rule::in(['Ingresado', 'En Tránsito', 'Entregado', 'No Retirado']) // Lista de valores permitidos[cite: 3]
            ],
            'id_usuario_gestor' => ['required', 'integer', 'exists:usuarios_credenciales,id_usuario'],
            'id_establecimiento_actual' => ['nullable', 'integer', 'exists:establecimientos,id_establecimiento'],
            'fecha_hora' => ['required', 'date'],
        ];
    }

    public static function rules(array $datos, $id = null): array
    {
        return Validator::make($datos, static::validaciones($id))->validate();
    }

    // RELACIONES[cite: 5]
    public function encomienda()
    {
        return $this->belongsTo(Encomienda::class, 'id_encomienda', 'id_encomienda');
    }

    public function gestor()
    {
        return $this->belongsTo(UsuarioCredencial::class, 'id_usuario_gestor', 'id_usuario');
    }

    public function establecimiento()
    {
        return $this->belongsTo(Establecimiento::class, 'id_establecimiento_actual', 'id_establecimiento');
    }

    // LECTURA
    public static function mostrarTodos(): Collection
    {
        return HistorialMovimiento::with(['encomienda', 'gestor'])->orderBy('fecha_hora', 'DESC')->get();
    }

    public static function contarTodos(): int
    {
        return HistorialMovimiento::count();
    }

    // INSERTAR (Solo insertar, es Append-Only)
    public static function insertar(array $datos): HistorialMovimiento
    {
        return HistorialMovimiento::create($datos);
    }

    public static function buscarXId(int $id): ?HistorialMovimiento
    {
        return HistorialMovimiento::find($id);
    }

    // Actualizar y eliminar se bloquean intencionalmente a nivel de lógica de negocio por ser auditoría
    public static function actualizar(array $datos, int $id): bool
    {
        return false;
    }

    public static function eliminar(int $id): bool
    {
        return false;
    }
}
