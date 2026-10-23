<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Distrito extends Model
{
    protected $table = 'distritos';
    protected $primaryKey = 'id_distrito';
    public $timestamps = false;
    protected $fillable = ['id_municipio', 'nombre_distrito'];

    public function municipio()
    {
        return $this->belongsTo(Municipio::class, 'id_municipio', 'id_municipio');
    }
}
