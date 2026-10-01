<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('manifiestos_viaje', function (Blueprint $table) {
            // Entero (PK): Identificador de la ruta
            $table->id('id_viaje');

            // Entero (FK): El empleado responsable
            $table->unsignedBigInteger('id_usuario_repartidor');

            // Varchar(20): Identificación del transporte
            $table->string('placa_vehiculo', 20);

            // DateTime, NULL: Modificador nullable() para permitir valor vacío hasta que inicie la ruta[cite: 4]
            $table->dateTime('fecha_salida')->nullable();

            // Varchar(50): 'Programado', 'En Curso', 'Finalizado'[cite: 4]
            $table->string('estado_viaje', 50);

            // Definición de la Llave Foránea[cite: 4]
            $table->foreign('id_usuario_repartidor')
                ->references('id_usuario')
                ->on('usuarios_credenciales')
                ->onDelete('restrict') // Restrict: evita borrar a un repartidor con viajes asignados[cite: 4]
                ->onUpdate('cascade'); // Cascade: actualiza en cascada[cite: 4]
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('manifiestos_viaje');
    }
};
