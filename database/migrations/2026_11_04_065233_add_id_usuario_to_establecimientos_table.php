<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('establecimientos', function (Blueprint $table) {
            // 1. Agregamos la columna nullable para no afectar los registros existentes
            $table->unsignedBigInteger('id_usuario')->nullable()->after('id_distrito');

            // 2. Definimos la llave foránea conectando con usuarios_credenciales
            $table->foreign('id_usuario')
                ->references('id_usuario')
                ->on('usuarios_credenciales')
                ->onUpdate('cascade')
                ->onDelete('set null'); // Si se borra un usuario, el establecimiento no se borra, solo queda en null
        });
    }

    public function down()
    {
        Schema::table('establecimientos', function (Blueprint $table) {
            // Para revertir la migración, primero borramos la foránea y luego la columna
            $table->dropForeign(['id_usuario']);
            $table->dropColumn('id_usuario');
        });
    }
};
