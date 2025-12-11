<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('ci')->unique()->after('apellido');
            $table->string('celular')->nullable()->after('ci');
            $table->enum('genero', ['M', 'F', 'Otro'])->nullable()->after('celular');
            $table->string('cargo')->nullable()->after('genero');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['ci', 'celular', 'genero', 'cargo']);
        });
    }
};
