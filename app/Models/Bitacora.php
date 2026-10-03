<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Bitacora extends Model
{
    protected $table = 'bitacoras';

    public const UPDATED_AT = null;

    // Campos permitidos para asignación masiva
    protected $fillable = [
        'usuario_id',
        'usuario_nombre',
        'evento',
        'modulo',
        'registro_id',
        'descripcion',
        'ip_address',
    ];

    // Casteo de fechas
    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
        ];
    }

    // Constantes de Eventos de Auditoría
    public const LOGIN_OK = 'LOGIN_OK';
    public const LOGIN_FALLIDO = 'LOGIN_FALLIDO';
    public const LOGOUT = 'LOGOUT';
    public const CREADO = 'CREADO';
    public const ACTUALIZADO = 'ACTUALIZADO';
    public const ELIMINADO = 'ELIMINADO';

    public function usuario()
    {
        return $this->belongsTo(UsuarioCredencial::class, 'usuario_id', 'id_usuario');
    }


    public static function registrar(string $evento, ?string $modulo = null, ?int $registro_id = null, ?string $descripcion = null, ?string $nombreIntento = null)
    {
        // Capturamos el usuario si existe sesión activa
        $usuario = Auth::user();

        // Si hay usuario logueado, usamos su nombre. Si no, usamos el intento de correo (para fallos).
        $nombreUsuario = $usuario ? ($usuario->perfil->nombres ?? 'Usuario Sistema') : $nombreIntento;

        return self::create([
            'usuario_id'     => $usuario ? $usuario->id_usuario : null,
            'usuario_nombre' => $nombreUsuario,
            'evento'         => $evento,
            'modulo'         => $modulo,
            'registro_id'    => $registro_id,
            'descripcion'    => $descripcion,
            'ip_address'     => request()->ip(),
        ]);
    }
}
