<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Tabla para el catálogo de precios por usuario/empresa
        Schema::create('catalogo_precios', function (Blueprint $table) {
            $table->id('id_precio');
            $table->unsignedBigInteger('id_usuario'); // Dueño del negocio / afiliado
            $table->string('nombre_prenda', 100);     // Ej: Caja Mediana, Documento, etc.
            $table->decimal('precio', 10, 2);         // Costo estándar
            $table->boolean('estado')->default(true); // Activo / Inactivo

            // Llave foránea hacia la tabla de credenciales/usuarios
            $table->foreign('id_usuario')
                ->references('id_usuario')
                ->on('usuarios_credenciales')
                ->onDelete('cascade');
        });

        // 2. Agregar la relación de precios en la tabla encomiendas existente
        Schema::table('encomiendas', function (Blueprint $table) {
            $table->unsignedBigInteger('id_precio')->nullable()->after('id_tipo_envio');

            $table->foreign('id_precio')
                ->references('id_precio')
                ->on('catalogo_precios')
                ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('encomiendas', function (Blueprint $table) {
            $table->dropForeign(['id_precio']);
            $table->dropColumn('id_precio');
        });

        Schema::dropIfExists('catalogo_precios');
    }
};
