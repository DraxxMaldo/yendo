<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('usuarios_credenciales', function (Blueprint $table) {
            // Entero (PK): Identificador maestro
            $table->id('id_usuario');

            // Varchar(150), UNIQUE: Credencial principal.
            // Usamos el modificador ->unique() para prevenir cuentas duplicadas[cite: 3]
            $table->string('correo_electronico', 150)->unique();

            // Varchar(255): Contraseña encriptada[cite: 3]
            $table->string('contrasenha', 255);

            // Entero (FK): Define los privilegios.
            // Usamos unsignedBigInteger para que coincida perfectamente con el $table->id() de la tabla roles[cite: 3]
            $table->unsignedBigInteger('id_rol');

            // Booleano: Estado de la cuenta.
            // Aplicamos un valor por defecto de true (1) para que nazcan activos[cite: 3]
            $table->boolean('estado_cuenta')->default(true);

            // Definición de la Llave Foránea[cite: 3]
            $table->foreign('id_rol')
                ->references('id_rol')
                ->on('roles')
                ->onDelete('restrict') // Restrict: Evita borrar un rol si hay usuarios usándolo[cite: 3]
                ->onUpdate('cascade'); // Cascade: Actualiza si el ID del rol cambia[cite: 3]
        });
    }

    public function down(): void
    {
        // Se ejecuta al revertir la migración[cite: 3]
        Schema::dropIfExists('usuarios_credenciales');
    }
};
