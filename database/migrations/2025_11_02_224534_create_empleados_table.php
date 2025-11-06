<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('empleados', function (Blueprint $table) {
            $table->id('idEmpleado');
            $table->string('primerNombre', 100);
            $table->string('segundoNombre', 100)->nullable();
            $table->string('apellidoPaterno', 100);
            $table->string('apellidoMaterno', 100)->nullable();
            $table->string('ci', 20)->unique();
            $table->string('numeroCelular', 20)->unique();
            $table->string('emailCorporativo', 255)->unique();
            $table->string('passwordHash', 255);
            $table->enum('rol', ['Gerente', 'SupervisorOperador', 'SupervisorTecnico', 'Operador', 'Tecnico']);
            $table->enum('estado', ['Activo', 'Bloqueado', 'Eliminado'])->default('Activo');
            $table->date('fechaIngreso');
            $table->timestamps();
            $table->timestamp('fechaEliminacion')->nullable();
            $table->index('rol', 'idx_empleado_rol');
            $table->index('estado', 'idx_empleado_estado');
        });
    }
    public function down(): void {
        Schema::dropIfExists('empleados');
    }
};