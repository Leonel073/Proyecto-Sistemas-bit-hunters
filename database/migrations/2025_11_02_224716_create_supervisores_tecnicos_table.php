<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('supervisores_tecnicos', function (Blueprint $table) {
            $table->unsignedBigInteger('idEmpleado')->primary();
            $table->string('zonaGeograficaAsignada', 100)->nullable();
            $table->foreign('idEmpleado')->references('idEmpleado')->on('empleados')->cascadeOnDelete()->cascadeOnUpdate();
        });
    }
    public function down(): void {
        Schema::dropIfExists('supervisores_tecnicos');
    }
};