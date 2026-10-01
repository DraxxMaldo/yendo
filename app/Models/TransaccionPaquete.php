<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;

class TransaccionPaquete extends Model
{
    protected $table = 'transacciones_paquetes';
    protected $primaryKey = 'id_transaccion';
    public $timestamps = false;

    protected $fillable = [
        'id_encomienda',
        'id_concepto',
        'monto',
        'fecha_transaccion',
        'id_usuario_cajero',
    ];

    protected $casts = [
        'id_encomienda' => 'integer',
        'id_concepto' => 'integer',
        'monto' => 'decimal:2', // Casting a decimal[cite: 3]
        'fecha_transaccion' => 'datetime',
        'id_usuario_cajero' => 'integer',
    ];

    public static function validaciones($id = null): array
    {
        return [
            'id_encomienda' => ['required', 'integer', 'exists:encomiendas,id_encomienda'],
            'id_concepto' => ['required', 'integer', 'exists:conceptos_financieros,id_concepto'],
            'monto' => ['required', 'numeric', 'min:0'],
            'fecha_transaccion' => ['required', 'date'],
            'id_usuario_cajero' => ['required', 'integer', 'exists:usuarios_credenciales,id_usuario'],
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

    public function concepto()
    {
        return $this->belongsTo(ConceptoFinanciero::class, 'id_concepto', 'id_concepto');
    }

    public function cajero()
    {
        return $this->belongsTo(UsuarioCredencial::class, 'id_usuario_cajero', 'id_usuario');
    }

    // LECTURA
    public static function mostrarTodos(): Collection
    {
        return TransaccionPaquete::with(['encomienda', 'concepto', 'cajero'])
            ->orderBy('fecha_transaccion', 'DESC')
            ->get();
    }

    public static function contarTodos(): int
    {
        return TransaccionPaquete::count();
    }

    // INSERTAR
    public static function insertar(array $datos): TransaccionPaquete
    {
        return TransaccionPaquete::create($datos);
    }

    public static function buscarXId(int $id): ?TransaccionPaquete
    {
        return TransaccionPaquete::find($id);
    }

    public static function actualizar(array $datos, int $id): bool
    {
        return TransaccionPaquete::find($id)->update($datos);
    }

    public static function eliminar(int $id): bool
    {
        return TransaccionPaquete::find($id)->delete();
    }
}
