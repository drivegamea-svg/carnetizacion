<?php
// database/migrations/xxxx_xx_xx_xxxxxx_create_archivos_afiliados_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('archivos_afiliados', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('afiliado_id')->constrained('afiliados')->onDelete('cascade');
            
            $table->string('nombre_original');
            $table->string('nombre_archivo');
            $table->string('mime_type');
            $table->bigInteger('tamanio')->comment('Tamaño en bytes');
            $table->string('ruta');
            
            // Información del tipo de documento
            $table->string('tipo_documento'); // DNI, CERTIFICADO, CONTRATO, OTRO
            $table->string('descripcion')->nullable();
            
            // Metadatos adicionales
            $table->json('metadata')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
            
            // Índices para búsqueda
            $table->index('afiliado_id');
            $table->index('tipo_documento');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('archivos_afiliados');
    }
};