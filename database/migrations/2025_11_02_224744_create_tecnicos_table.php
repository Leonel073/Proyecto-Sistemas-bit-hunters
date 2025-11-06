<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('tecnicos', function (Blueprint $table) {
            $table->unsignedBigInteger('idEmpleado')->primary();
            $table->string('especialidad', 100);
            $table->enum('estadoDisponibilidad', ['Disponible', 'En Ruta', 'Ocupado'])->default('Disponible');
            $table->foreign('idEmpleado')->references('idEmpleado')->on('empleados')->cascadeOnDelete()->cascadeOnUpdate();
        });
    }
    public function down(): void {
        Schema::dropIfExists('tecnicos');
    }
};