<?php
// database/migrations/2024_01_01_000006_create_ciudades_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('ciudades', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('departamento'); // ✅ NUEVO CAMPO
            $table->timestamps();
            $table->softDeletes();
            
            $table->index('deleted_at');
            $table->index('departamento'); // Índice para búsquedas por departamento
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ciudades');
    }
};