<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('zonas', function (Blueprint $table) {
            $table->id('idZona');
            $table->string('nombreZona', 100);
            $table->text('descripcion')->nullable();
            $table->enum('estado', ['Activo', 'Inactivo'])->default('Activo');
            $table->timestamps();
        });

        Schema::table('usuarios', function (Blueprint $table) {
            $table->foreign('idZona')->references('idZona')->on('zonas')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('zonas');
    }
};
