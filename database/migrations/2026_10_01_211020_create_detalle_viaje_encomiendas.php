<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('detalle_viaje_encomiendas', function (Blueprint $table) {
            // Entero (PK compuesta, FK): Vincula el manifiesto[cite: 4]
            $table->unsignedBigInteger('id_viaje');

            // Entero (PK compuesta, FK): El paquete asignado[cite: 4]
            $table->unsignedBigInteger('id_encomienda');

            // Varchar(50): Resultado de la entrega[cite: 4]
            $table->string('resultado_entrega', 50);

            // --- DEFINICIÓN DE LA LLAVE PRIMARIA COMPUESTA ---
            // Le indicamos a Laravel que la combinación de estos dos campos es única e identifica la fila
            $table->primary(['id_viaje', 'id_encomienda']);

            // --- DEFINICIÓN DE LLAVES FORÁNEAS ---[cite: 4]

            $table->foreign('id_viaje')
                ->references('id_viaje')
                ->on('manifiestos_viaje')
                ->onDelete('cascade') // Cascade: Si se borra el viaje, se borra su detalle[cite: 4]
                ->onUpdate('cascade');

            $table->foreign('id_encomienda')
                ->references('id_encomienda')
                ->on('encomiendas')
                ->onDelete('cascade') // Cascade: Si se borra la encomienda, se quita del viaje[cite: 4]
                ->onUpdate('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detalle_viaje_encomiendas');
    }
};
