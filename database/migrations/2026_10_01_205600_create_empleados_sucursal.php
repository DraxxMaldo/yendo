<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('empleados_sucursal', function (Blueprint $table) {
            // Entero (PK, FK): Vinculado estrictamente al usuario[cite: 2]
            $table->unsignedBigInteger('id_usuario')->primary();

            // Entero (FK): Sucursal base del trabajador[cite: 2]
            $table->unsignedBigInteger('id_establecimiento');

            // Llave foránea hacia el usuario[cite: 2]
            $table->foreign('id_usuario')
                ->references('id_usuario')
                ->on('usuarios_credenciales')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            // Llave foránea hacia el establecimiento[cite: 2]
            $table->foreign('id_establecimiento')
                ->references('id_establecimiento')
                ->on('establecimientos')
                ->onDelete('restrict') // Evita borrar una sucursal con empleados asignados[cite: 2]
                ->onUpdate('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('empleados_sucursal');
    }
};
