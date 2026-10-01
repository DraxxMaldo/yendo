<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class Encomienda extends Model
{
    protected $table = 'encomiendas';
    protected $primaryKey = 'id_encomienda';
    public $timestamps = false;

    protected $fillable = [
        'codigo_rastreo',
        'id_usuario_remitente',
        'id_establecimiento_origen',
        'id_establecimiento_destino',
        'id_tipo_envio',
        'es_pago_contraentrega',
        'valor_declarado_producto',
        'fecha_ingreso',
    ];

    protected $casts = [
        'id_usuario_remitente' => 'integer',
        'id_establecimiento_origen' => 'integer',
        'id_establecimiento_destino' => 'integer',
        'id_tipo_envio' => 'integer',
        'es_pago_contraentrega' => 'boolean',
        'valor_declarado_producto' => 'decimal:2', // Casting a decimal[cite: 3]
        'fecha_ingreso' => 'datetime',
    ];

    public static function validaciones($id = null): array
    {
        return [
            'codigo_rastreo' => [
                'required',
                'string',
                'max:50',
                Rule::unique('encomiendas', 'codigo_rastreo')->ignore($id, 'id_encomienda')
            ],
            'id_usuario_remitente' => ['required', 'integer', 'exists:usuarios_credenciales,id_usuario'],
            'id_establecimiento_origen' => ['required', 'integer', 'exists:establecimientos,id_establecimiento'],
            'id_establecimiento_destino' => ['nullable', 'integer', 'exists:establecimientos,id_establecimiento'],
            'id_tipo_envio' => ['required', 'integer', 'exists:tipos_envio,id_tipo_envio'],
            'es_pago_contraentrega' => ['required', 'boolean'],
            'valor_declarado_producto' => ['required', 'numeric', 'min:0'], // Validación numérica[cite: 3]
            'fecha_ingreso' => ['required', 'date'], // Validación de fecha[cite: 3]
        ];
    }

    public static function rules(array $datos, $id = null): array
    {
        return Validator::make($datos, static::validaciones($id))->validate();
    }

    // RELACIONES[cite: 5]
    public function remitente()
    {
        return $this->belongsTo(UsuarioCredencial::class, 'id_usuario_remitente', 'id_usuario');
    }

    public function origen()
    {
        return $this->belongsTo(Establecimiento::class, 'id_establecimiento_origen', 'id_establecimiento');
    }

    public function destino()
    {
        return $this->belongsTo(Establecimiento::class, 'id_establecimiento_destino', 'id_establecimiento');
    }

    public function tipoEnvio()
    {
        return $this->belongsTo(TipoEnvio::class, 'id_tipo_envio', 'id_tipo_envio');
    }

    public function historialMovimientos()
    {
        return $this->hasMany(HistorialMovimiento::class, 'id_encomienda', 'id_encomienda');
    }

    // LECTURA
    public static function mostrarTodos(): Collection
    {
        return Encomienda::with(['remitente', 'origen', 'destino', 'tipoEnvio'])->orderBy('fecha_ingreso', 'DESC')->get();
    }

    public static function contarTodos(): int
    {
        return Encomienda::count();
    }

    // INSERTAR, MODIFICAR, ELIMINAR
    public static function insertar(array $datos): Encomienda
    {
        return Encomienda::create($datos);
    }

    public static function buscarXId(int $id): ?Encomienda
    {
        return Encomienda::find($id);
    }

    public static function actualizar(array $datos, int $id): bool
    {
        return Encomienda::find($id)->update($datos);
    }

    public static function eliminar(int $id): bool
    {
        return Encomienda::find($id)->delete();
    }
}
