<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('historial_movimientos', function (Blueprint $table) {
            // Entero (PK): Identificador de la acción
            $table->id('id_movimiento');

            // Entero (FK): El paquete que está siendo afectado[cite: 3]
            $table->unsignedBigInteger('id_encomienda');

            // Varchar(50): 'Ingresado', 'En Tránsito', etc.[cite: 3]
            $table->string('estado_fisico', 50);

            // Entero (FK): El empleado que ejecutó la acción[cite: 3]
            $table->unsignedBigInteger('id_usuario_gestor');

            // Entero (FK), NULL: Dónde ocurrió el evento (nulo si es en ruta)[cite: 3]
            // El modificador ->nullable() permite valores nulos[cite: 3]
            $table->unsignedBigInteger('id_establecimiento_actual')->nullable();

            // DateTime: Cronograma exacto del movimiento[cite: 3]
            $table->dateTime('fecha_hora');

            // --- DEFINICIÓN DE LLAVES FORÁNEAS ---[cite: 3]

            $table->foreign('id_encomienda')
                ->references('id_encomienda')
                ->on('encomiendas')
                ->onDelete('cascade') // Cascade: Si borras el paquete (por error), borra su historial[cite: 3]
                ->onUpdate('cascade');

            $table->foreign('id_usuario_gestor')
                ->references('id_usuario')
                ->on('usuarios_credenciales')
                ->onDelete('restrict') // Restrict: Prohíbe borrar un usuario si tiene historial de auditoría[cite: 3]
                ->onUpdate('cascade');

            $table->foreign('id_establecimiento_actual')
                ->references('id_establecimiento')
                ->on('establecimientos')
                ->onDelete('restrict')
                ->onUpdate('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('historial_movimientos');
    }
};
