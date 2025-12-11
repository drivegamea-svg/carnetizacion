<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('personas', function (Blueprint $table) {
            $table->id();
            $table->string('ci')->unique();
            $table->enum('expedicion', ['QR', 'S/E', 'LP', 'CBBA', 'SC', 'CH', 'OR', 'PT', 'TJ', 'BE', 'PD', 'BN', 'SA', 'SCZ']);
            $table->string('nombres');
            $table->string('apellido_paterno')->nullable();
            $table->string('apellido_materno')->nullable();
            $table->date('fecha_nacimiento')->nullable();
            $table->enum('genero', ['MASCULINO', 'FEMENINO']);
            $table->string('estado_civil')->nullable();
            $table->string('celular')->nullable();
            
            // NUEVOS CAMPOS DE DOMICILIO (reemplazan el campo domicilio)
            $table->string('departamento')->nullable();
            $table->string('ciudad')->nullable();
            $table->string('zona')->nullable();
            $table->string('direccion')->nullable();
            $table->string('nro_puerta')->nullable();
            
            // Mantener el campo domicilio por compatibilidad (opcional)
            $table->text('domicilio')->nullable();
            
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('personas');
    }
};