<?php
// database/migrations/2024_01_01_000004_create_profesiones_tecnicas_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('profesiones_tecnicas', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->text('descripcion')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            $table->index('deleted_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('profesiones_tecnicas');
    }
};