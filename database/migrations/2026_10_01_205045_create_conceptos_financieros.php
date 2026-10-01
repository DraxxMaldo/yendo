<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('conceptos_financieros', function (Blueprint $table) {
            // Entero (PK)[cite: 1]
            $table->id('id_concepto');

            // Varchar(100): Nombre del concepto[cite: 1]
            $table->string('nombre_concepto', 100);

            // Varchar(10): Tipo de movimiento ('Ingreso' o 'Egreso')[cite: 1]
            $table->string('tipo_movimiento', 10);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('conceptos_financieros');
    }
};
