<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('departamentos', function (Blueprint $table) {
            // Agregamos el campo y la llave foránea
            $table->unsignedBigInteger('id_zona')->nullable()->after('id_departamento');

            $table->foreign('id_zona')
                ->references('id_zona')
                ->on('zonas')
                ->onUpdate('cascade')
                ->onDelete('restrict');
        });
    }

    public function down(): void
    {
        Schema::table('departamentos', function (Blueprint $table) {
            $table->dropForeign(['id_zona']);
            $table->dropColumn('id_zona');
        });
    }
};
