<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('encomiendas', function (Blueprint $table) {
            // Entero (PK): Identificador interno
            $table->id('id_encomienda');

            // Varchar(50), UNIQUE: Código alfanumérico generado[cite: 1]
            $table->string('codigo_rastreo', 50)->unique();

            // Entero (FK): El usuario afiliado que envía[cite: 1]
            $table->unsignedBigInteger('id_usuario_remitente');

            // Entero (FK): Sucursal de origen[cite: 1]
            $table->unsignedBigInteger('id_establecimiento_origen');

            // Entero (FK), NULL: Sucursal destino.
            // Aplicamos el modificador ->nullable() para permitir valores nulos[cite: 1]
            $table->unsignedBigInteger('id_establecimiento_destino')->nullable();

            // Entero (FK): Tipo de envío[cite: 1]
            $table->unsignedBigInteger('id_tipo_envio');

            // Booleano: Indica si es contraentrega[cite: 1]
            $table->boolean('es_pago_contraentrega')->default(false);

            // Decimal(10,2): Valor del producto[cite: 1]
            $table->decimal('valor_declarado_producto', 10, 2);

            // DateTime: Timestamp exacto de creación[cite: 1]
            $table->dateTime('fecha_ingreso');

            // --- DEFINICIÓN DE LLAVES FORÁNEAS ---[cite: 1]

            $table->foreign('id_usuario_remitente')
                ->references('id_usuario')
                ->on('usuarios_credenciales')
                ->onDelete('restrict')
                ->onUpdate('cascade');

            $table->foreign('id_establecimiento_origen')
                ->references('id_establecimiento')
                ->on('establecimientos')
                ->onDelete('restrict')
                ->onUpdate('cascade');

            // Si una sucursal destino es borrada, podríamos poner esto en 'set null'
            // o 'restrict' para evitar perder el historial. Usaremos restrict[cite: 1]
            $table->foreign('id_establecimiento_destino')
                ->references('id_establecimiento')
                ->on('establecimientos')
                ->onDelete('restrict')
                ->onUpdate('cascade');

            $table->foreign('id_tipo_envio')
                ->references('id_tipo_envio')
                ->on('tipos_envio')
                ->onDelete('restrict')
                ->onUpdate('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('encomiendas');
    }
};
