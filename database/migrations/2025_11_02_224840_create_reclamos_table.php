<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        // SOLO UNA VEZ Schema::create
        Schema::create('reclamos', function (Blueprint $table) {
            $table->id('idReclamo');
            $table->unsignedBigInteger('idUsuario');
            $table->unsignedBigInteger('idOperador')->nullable();
            $table->unsignedBigInteger('idTecnicoAsignado')->nullable();
            $table->unsignedBigInteger('idPoliticaSLA');
            $table->unsignedBigInteger('idTipoIncidente');
            $table->unsignedBigInteger('idCausaRaiz')->nullable();
            $table->string('titulo', 255);
            $table->text('descripcionDetallada');

            // ✅ AQUÍ AGREGAMOS LA COLUMNA NUEVA (Dentro del mismo bloque)
            $table->json('comentarios')->nullable(); 

            $table->text('solucionTecnica')->nullable();
            $table->enum('estado', ['Nuevo', 'Abierto', 'Asignado', 'En Proceso', 'Resuelto', 'Cerrado', 'Cancelado'])->default('Nuevo');
            $table->enum('prioridad', ['Baja', 'Media', 'Alta', 'Urgente']);
            $table->decimal('latitudIncidente', 10, 8);
            $table->decimal('longitudIncidente', 11, 8);
            $table->timestamp('fechaCreacion')->useCurrent();
            $table->timestamp('fechaResolucion')->nullable();
            $table->timestamp('fechaCierre')->nullable();
            $table->timestamp('fechaActualizacion')->useCurrent()->useCurrentOnUpdate();
            $table->timestamp('fechaEliminacion')->nullable();

            // Relaciones (Foreign Keys)
            $table->foreign('idUsuario')->references('idUsuario')->on('usuarios')->restrictOnDelete()->cascadeOnUpdate();
            $table->foreign('idOperador')->references('idEmpleado')->on('empleados')->nullOnDelete()->cascadeOnUpdate();
            $table->foreign('idTecnicoAsignado')->references('idEmpleado')->on('empleados')->nullOnDelete()->cascadeOnUpdate();
            $table->foreign('idPoliticaSLA')->references('idPoliticaSLA')->on('sla_politicas')->restrictOnDelete()->cascadeOnUpdate();
            $table->foreign('idTipoIncidente')->references('idTipoIncidente')->on('cat_tipo_incidente')->restrictOnDelete()->cascadeOnUpdate();
            $table->foreign('idCausaRaiz')->references('idCausaRaiz')->on('cat_causa_raiz')->nullOnDelete()->cascadeOnUpdate();
            $table->timestamps();
            $table->index('estado', 'idx_reclamo_estado');
            $table->index('prioridad', 'idx_reclamo_prioridad');
        });
    }

    public function down(): void {
        Schema::dropIfExists('reclamos');
    }
};