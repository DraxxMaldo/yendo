<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tipos_envio', function (Blueprint $table) {
            // Entero (PK): El método id() crea un BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY
            $table->id('id_tipo_envio');

            // Varchar(50): Nombre del tipo de envío (Ej: 'Retiro en Sucursal')
            $table->string('nombre_tipo', 50);
        });
    }

    public function down(): void
    {
        // Se ejecuta al revertir: elimina la tabla
        Schema::dropIfExists('tipos_envio');
    }
};
