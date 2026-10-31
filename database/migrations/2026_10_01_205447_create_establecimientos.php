<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('establecimientos', function (Blueprint $table) {
            // Entero (PK)[cite: 2]
            $table->id('id_establecimiento');

            // Varchar(100): Nombre de la sucursal[cite: 2]
            $table->string('nombre', 100);

            // Entero (FK): Relación con municipios[cite: 2]
            $table->unsignedBigInteger('id_distrito');

            // Texto: Dirección exacta[cite: 2]
            $table->text('direccion_exacta');

            // Definición de la Llave Foránea[cite: 2]
            $table->foreign('id_distrito')
                ->references('id_distrito')
                ->on('distritos')
                ->onDelete('restrict') // Restrict: No borrar un municipio si tiene sucursales[cite: 2]
                ->onUpdate('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('establecimientos');
    }
};
