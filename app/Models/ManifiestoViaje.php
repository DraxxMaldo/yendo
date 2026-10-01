<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ManifiestoViaje extends Model
{
    protected $table = 'manifiestos_viaje';
    protected $primaryKey = 'id_viaje';
    public $timestamps = false;

    protected $fillable = [
        'id_usuario_repartidor',
        'placa_vehiculo',
        'fecha_salida',
        'estado_viaje',
    ];

    protected $casts = [
        'id_usuario_repartidor' => 'integer',
        'fecha_salida' => 'datetime',
    ];

    public static function validaciones($id = null): array
    {
        return [
            'id_usuario_repartidor' => ['required', 'integer', 'exists:usuarios_credenciales,id_usuario'],
            'placa_vehiculo' => ['required', 'string', 'max:20'],
            'fecha_salida' => ['nullable', 'date'],
            'estado_viaje' => [
                'required',
                'string',
                'max:50',
                Rule::in(['Programado', 'En Curso', 'Finalizado'])
            ],
        ];
    }

    public static function rules(array $datos, $id = null): array
    {
        return Validator::make($datos, static::validaciones($id))->validate();
    }

    // RELACIONES[cite: 5]
    public function repartidor()
    {
        return $this->belongsTo(UsuarioCredencial::class, 'id_usuario_repartidor', 'id_usuario');
    }

    public function detallesViaje()
    {
        return $this->hasMany(DetalleViajeEncomienda::class, 'id_viaje', 'id_viaje');
    }

    // LECTURA
    public static function mostrarTodos(): Collection
    {
        return ManifiestoViaje::with('repartidor')->orderBy('id_viaje', 'DESC')->get();
    }

    public static function contarTodos(): int
    {
        return ManifiestoViaje::count();
    }

    // INSERTAR, MODIFICAR, ELIMINAR
    public static function insertar(array $datos): ManifiestoViaje
    {
        return ManifiestoViaje::create($datos);
    }

    public static function buscarXId(int $id): ?ManifiestoViaje
    {
        return ManifiestoViaje::find($id);
    }

    public static function actualizar(array $datos, int $id): bool
    {
        return ManifiestoViaje::find($id)->update($datos);
    }

    public static function eliminar(int $id): bool
    {
        return ManifiestoViaje::find($id)->delete();
    }
}
