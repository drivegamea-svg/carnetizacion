<?php
// database/migrations/2024_01_01_000001_create_afiliados_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up()
    {
        Schema::create('afiliados', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('ci', 20);
            $table->string('expedicion', 10);
            $table->string('nombres');
            $table->string('apellido_paterno')->nullable();
            $table->string('apellido_materno')->nullable();
            $table->date('fecha_nacimiento')->nullable();
            $table->enum('genero', ['MASCULINO', 'FEMENINO']);
            $table->string('celular', 20);
            $table->string('direccion')->nullable(); // ✅ NUEVO: Agregado según el formulario
            
            // Relaciones con tablas maestras
            $table->foreignId('ciudad_id')
                  ->nullable()
                  ->constrained('ciudades')
                  ->onDelete('set null');

            $table->foreignId('profesion_tecnica_id')
                  ->nullable()
                  ->constrained('profesiones_tecnicas')
                  ->onDelete('set null');

            $table->foreignId('especialidad_id')
                  ->nullable()
                  ->constrained('especialidades')
                  ->onDelete('set null');


            $table->foreignId('organizacion_social_id')
                  ->nullable()
                  ->constrained('organizaciones_sociales')
                  ->onDelete('set null');
            
            $table->foreignId('sector_economico_id')
                  ->nullable()
                  ->constrained('sectores_economicos')
                  ->onDelete('set null');
            
            // Documentos
            $table->string('foto_path')->nullable();
            $table->text('foto_data')->nullable();

            $table->string('huella_path')->nullable();
            $table->text('huella_data')->nullable();
            
            // Metadata
            $table->enum('estado', ['ACTIVO', 'INACTIVO', 'PENDIENTE'])->default('PENDIENTE');
            $table->timestamp('fecha_afiliacion')->nullable();
            $table->timestamp('fecha_vencimiento')->nullable();
            
            // Soft Deletes
            $table->softDeletes();
            $table->timestamps();
            
            // Índices
            $table->index('ci');
            $table->index('estado');
            $table->index('ciudad_id');
            $table->index('organizacion_social_id');
            $table->index('sector_economico_id');
            $table->index('profesion_tecnica_id');
            $table->index('especialidad_id');
            $table->index(['deleted_at', 'estado']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('afiliados');
    }
};