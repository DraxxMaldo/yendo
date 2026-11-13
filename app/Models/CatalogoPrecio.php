<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CatalogoPrecio extends Model
{
    protected $table = 'catalogo_precios';
    protected $primaryKey = 'id_precio';
    public $timestamps = false; // Manteniendo el estándar de tus tablas

    protected $fillable = [
        'id_usuario',
        'nombre_prenda',
        'precio',
        'estado',
    ];

    protected $casts = [
        'id_usuario' => 'integer',
        'precio' => 'decimal:2',
        'estado' => 'boolean',
    ];

    // RELACIÓN: Un precio pertenece a un usuario/afiliado (dueño de la empresa)
    public function usuario(): BelongsTo
    {
        return $this->belongsTo(UsuarioCredencial::class, 'id_usuario', 'id_usuario');
    }

    // RELACIÓN: Un precio puede estar asociado a múltiples encomiendas
    public function encomiendas(): HasMany
    {
        return $this->hasMany(Encomienda::class, 'id_precio', 'id_precio');
    }
}
