<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('zonas', function (Blueprint $table) {
            $table->id('id_zona');
            $table->string('nombre_zona', 50); // Ej: Occidental, Central, Oriental
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('zonas');
    }
};
