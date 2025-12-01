<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('registros_auditoria', function (Blueprint $table) {
            $table->id('idLog');
            $table->unsignedBigInteger('idEmpleado')->nullable();
            $table->unsignedBigInteger('idUsuario')->nullable();
            $table->unsignedBigInteger('idReclamoAfectado')->nullable();
            $table->string('accion', 100);
            $table->text('detalleAccion')->nullable();
            $table->timestamp('fechaHora')->useCurrent();
            $table->string('ipOrigen', 45)->nullable();

            $table->foreign('idEmpleado')->references('idEmpleado')->on('empleados')->nullOnDelete()->cascadeOnUpdate();
            $table->foreign('idUsuario')->references('idUsuario')->on('usuarios')->nullOnDelete()->cascadeOnUpdate();
            $table->foreign('idReclamoAfectado')->references('idReclamo')->on('reclamos')->nullOnDelete()->cascadeOnUpdate();

            $table->index('accion', 'idx_auditoria_accion');
            // Agregar los timestamps:
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('registros_auditoria');
    }
};
