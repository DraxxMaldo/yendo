<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PerfilPersona extends Model
{
    protected $table = 'perfiles_personas';
    protected $primaryKey = 'id_usuario';
    public $incrementing = false; // Llave primaria foránea de usuarios_credenciales
    protected $keyType = 'int';
    public $timestamps = false;

    protected $fillable = [
        'id_usuario',
        'nombres',
        'apellidos',
        'telefono',
        'nombre_negocio',
        'imagen'
    ];

    // RELACIÓN
    public function credenciales()
    {
        return $this->belongsTo(UsuarioCredencial::class, 'id_usuario', 'id_usuario');
    }
}
