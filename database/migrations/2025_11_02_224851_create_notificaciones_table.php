<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('notificaciones', function (Blueprint $table) {
            $table->id('idNotificacion');
            $table->unsignedBigInteger('idReclamo');
            $table->unsignedBigInteger('idUsuario');
            $table->enum('canalEnvio', ['SMS', 'Email', 'Push']);
            $table->text('mensaje');
            $table->timestamp('fechaEnvio')->useCurrent();
            $table->enum('estadoEnvio', ['Enviado', 'Fallido', 'Leído'])->default('Enviado');

            $table->foreign('idReclamo')->references('idReclamo')->on('reclamos')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreign('idUsuario')->references('idUsuario')->on('usuarios')->cascadeOnDelete()->cascadeOnUpdate();
        });
    }
    public function down(): void {
        Schema::dropIfExists('notificaciones');
    }
};