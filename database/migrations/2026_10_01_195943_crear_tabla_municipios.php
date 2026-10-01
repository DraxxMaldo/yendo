<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('municipios', function (Blueprint $table) {
            // Entero (PK)[cite: 2]
            $table->id('id_municipio');

            // Entero (FK): Vincula el municipio a su departamento
            // Usamos unsignedBigInteger para que coincida con el tipo de la PK padre[cite: 2]
            $table->unsignedBigInteger('id_departamento');

            // Varchar(100): Nombre del municipio[cite: 2]
            $table->string('nombre_municipio', 100);

            // Definición explícita de la relación (Clave foránea)[cite: 2]
            $table->foreign('id_departamento')
                ->references('id_departamento')
                ->on('departamentos')
                ->onDelete('restrict') // Restrict: No permite eliminar un departamento si tiene municipios[cite: 2]
                ->onUpdate('cascade'); // Cascade: Actualiza la referencia si el ID cambia[cite: 2]
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('municipios');
    }
};
