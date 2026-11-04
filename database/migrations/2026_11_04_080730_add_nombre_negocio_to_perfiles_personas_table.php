<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('perfiles_personas', function (Blueprint $table) {
            // Agregamos la columna como nullable por si los perfiles existentes no tienen negocio aún
            $table->string('nombre_negocio', 150)->nullable()->after('telefono');
        });
    }

    public function down()
    {
        Schema::table('perfiles_personas', function (Blueprint $table) {
            $table->dropColumn('nombre_negocio');
        });
    }
};
