<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('roles', function (Blueprint $table) {
            // Entero (PK): El método id() crea un BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY
            $table->id('id_rol');

            // Varchar(50): Nombre del cargo
            $table->string('nombre_rol', 50);

            // Varchar(255): Descripción de capacidades
            $table->string('descripcion', 255);
        });
    }

    public function down(): void
    {
        // Se ejecuta al revertir: elimina la tabla si existe
        Schema::dropIfExists('roles');
    }
};
