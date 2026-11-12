<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SolicitudAfiliacion extends Model
{
    use HasFactory;

    protected $table = 'solicitud_afiliaciones';
    protected $primaryKey = 'id_solicitud';

    protected $fillable = [
        'id_usuario',
        'estado',
        'motivo_rechazo',
    ];

    public function usuario()
    {
        // Apuntamos al modelo correcto que me compartiste
        return $this->belongsTo(UsuarioCredencial::class, 'id_usuario', 'id_usuario');
    }
}
