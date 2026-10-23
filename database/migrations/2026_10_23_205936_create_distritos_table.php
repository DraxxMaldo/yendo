<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('distritos', function (Blueprint $table) {
            $table->id('id_distrito');
            $table->unsignedBigInteger('id_municipio');
            $table->string('nombre_distrito', 100);

            $table->foreign('id_municipio')
                ->references('id_municipio')
                ->on('municipios')
                ->onUpdate('cascade')
                ->onDelete('restrict');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('distritos');
    }
};
