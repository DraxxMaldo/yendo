<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('establecimientos', function (Blueprint $table) {
            $table->string('imagen', 255)->nullable()->after('direccion_exacta');
        });
    }

    public function down(): void
    {
        Schema::table('establecimientos', function (Blueprint $table) {
            // Eliminamos la columna si hacemos rollback[cite: 4]
            $table->dropColumn('imagen');
        });
    }
};
