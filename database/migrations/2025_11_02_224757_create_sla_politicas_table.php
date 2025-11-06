<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('sla_politicas', function (Blueprint $table) {
            $table->id('idPoliticaSLA');
            $table->string('nombrePolitica', 255);
            $table->enum('prioridad', ['Baja', 'Media', 'Alta', 'Urgente']);
            $table->integer('tiempoMaxSolucionHoras');
            $table->boolean('estaActiva')->default(true);
        });
    }
    public function down(): void {
        Schema::dropIfExists('sla_politicas');
    }
};