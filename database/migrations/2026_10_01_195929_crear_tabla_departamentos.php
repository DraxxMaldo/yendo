<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('departamentos', function (Blueprint $table) {
            // Entero (PK)[cite: 2]
            $table->id('id_departamento');

            // Varchar(100): Nombre del departamento[cite: 2]
            $table->string('nombre_departamento', 100);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('departamentos');
    }
};
