<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Zona extends Model
{
    protected $table = 'zonas';
    protected $primaryKey = 'id_zona';
    public $timestamps = false;
    protected $fillable = ['nombre_zona'];

    public function departamentos()
    {
        return $this->hasMany(Departamento::class, 'id_zona', 'id_zona');
    }
}
