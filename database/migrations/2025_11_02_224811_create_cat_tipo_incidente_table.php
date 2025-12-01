<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cat_tipo_incidente', function (Blueprint $table) {
            $table->id('idTipoIncidente');
            $table->string('nombreIncidente', 255)->unique();
            $table->text('descripcion')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cat_tipo_incidente');
    }
};
