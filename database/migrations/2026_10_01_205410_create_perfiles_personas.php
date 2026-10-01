<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('perfiles_personas', function (Blueprint $table) {
            // Entero (PK, FK): No usamos id() para evitar el auto-incremento.
            // Usamos unsignedBigInteger y el modificador ->primary()
            $table->unsignedBigInteger('id_usuario')->primary();

            // Varchar(100): Nombres
            $table->string('nombres', 100);

            // Varchar(100): Apellidos
            $table->string('apellidos', 100);

            // Varchar(20): Teléfono de contacto
            $table->string('telefono', 20);

            // Definición de la Llave Foránea
            $table->foreign('id_usuario')
                ->references('id_usuario')
                ->on('usuarios_credenciales')
                ->onDelete('cascade') // Cascade: Si se borra la credencial, se borra el perfil[cite: 2]
                ->onUpdate('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('perfiles_personas');
    }
};
