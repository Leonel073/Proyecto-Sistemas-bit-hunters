<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('usuarios', function (Blueprint $table) {
            $table->id('idUsuario');
            $table->string('primerNombre', 100);
            $table->string('segundoNombre', 100)->nullable();
            $table->string('apellidoPaterno', 100);
            $table->string('apellidoMaterno', 100)->nullable();
            $table->string('ci', 20)->unique();
            $table->string('numeroCelular', 20)->unique();
            $table->string('email', 255)->unique()->nullable();
            $table->string('passwordHash', 255);
            $table->text('direccionTexto')->nullable();
            $table->timestamp('fechaRegistro')->useCurrent();
            $table->enum('estado', ['Activo', 'Bloqueado', 'Eliminado'])->default('Activo');
            $table->unsignedBigInteger('idZona')->nullable();
            $table->timestamps(); // crea fechaCreacion y fechaActualizacion
            $table->timestamp('fechaEliminacion')->nullable();
            $table->index('estado', 'idx_usuario_estado');
            $table->index(['apellidoPaterno', 'apellidoMaterno'], 'idx_usuario_apellidos');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('usuarios');
    }
};
