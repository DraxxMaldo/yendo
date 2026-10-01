<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transacciones_paquetes', function (Blueprint $table) {
            // Entero (PK): Identificador del recibo
            $table->id('id_transaccion');

            // Entero (FK): Justificación comercial del cobro[cite: 1]
            $table->unsignedBigInteger('id_encomienda');

            // Entero (FK): Tipifica el cobro o remuneración[cite: 1]
            $table->unsignedBigInteger('id_concepto');

            // Decimal(10,2): Valor monetario exacto[cite: 1]
            $table->decimal('monto', 10, 2);

            // DateTime: Marca de tiempo financiera[cite: 1]
            $table->dateTime('fecha_transaccion');

            // Entero (FK): Empleado que manipuló el efectivo[cite: 1]
            $table->unsignedBigInteger('id_usuario_cajero');

            // --- DEFINICIÓN DE LLAVES FORÁNEAS ---[cite: 1]

            $table->foreign('id_encomienda')
                ->references('id_encomienda')
                ->on('encomiendas')
                ->onDelete('restrict') // Protege la integridad contable[cite: 1]
                ->onUpdate('cascade');

            $table->foreign('id_concepto')
                ->references('id_concepto')
                ->on('conceptos_financieros')
                ->onDelete('restrict')
                ->onUpdate('cascade');

            $table->foreign('id_usuario_cajero')
                ->references('id_usuario')
                ->on('usuarios_credenciales')
                ->onDelete('restrict')
                ->onUpdate('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transacciones_paquetes');
    }
};
