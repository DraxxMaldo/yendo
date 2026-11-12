<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('perfiles_personas', function (Blueprint $table) {
            // Añadimos la columna para guardar la ruta del archivo de la imagen
            $table->string('imagen', 255)->nullable()->after('apellidos');
        });
    }

    public function down(): void
    {
        Schema::table('perfiles_personas', function (Blueprint $table) {
            $table->dropColumn('imagen');
        });
    }
};
