<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('cat_causa_raiz', function (Blueprint $table) {
            $table->id('idCausaRaiz');
            $table->string('nombreCausa', 255)->unique();
            $table->text('descripcion')->nullable();
        });
    }
    public function down(): void {
        Schema::dropIfExists('cat_causa_raiz');
    }
};