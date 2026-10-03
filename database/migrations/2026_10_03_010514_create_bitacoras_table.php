<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('bitacoras', function (Blueprint $table) {
            $table->id();

            // Llave foránea hacia tu tabla de usuarios con borrado justificado (Set Null)[cite: 7]
            $table->foreignId('usuario_id')->nullable()
                ->references('id_usuario')->on('usuarios_credenciales')
                ->nullOnDelete();

            // Campos solicitados
            $table->string('usuario_nombre', 100)->nullable();
            $table->string('evento', 30);
            $table->string('modulo', 50)->nullable();
            $table->unsignedBigInteger('registro_id')->nullable();
            $table->string('descripcion', 255)->nullable();
            $table->string('ip_address', 45)->nullable();

            // Únicamente la marca temporal de creación
            $table->timestamp('created_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bitacoras');
    }
};
