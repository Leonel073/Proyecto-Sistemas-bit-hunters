<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('gerentes_soporte', function (Blueprint $table) {
            $table->unsignedBigInteger('idEmpleado')->primary();
            $table->string('nivelAutoridad', 100)->default('Total');
            $table->foreign('idEmpleado')->references('idEmpleado')->on('empleados')->cascadeOnDelete()->cascadeOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('gerentes_soporte');
    }
};
