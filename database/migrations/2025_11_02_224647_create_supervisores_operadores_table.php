<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('supervisores_operadores', function (Blueprint $table) {
            $table->unsignedBigInteger('idEmpleado')->primary();
            $table->enum('areaEnfoque', ['Calidad', 'Rendimiento', 'General'])->default('General');
            $table->foreign('idEmpleado')->references('idEmpleado')->on('empleados')->cascadeOnDelete()->cascadeOnUpdate();
        });
    }
    public function down(): void {
        Schema::dropIfExists('supervisores_operadores');
    }
};