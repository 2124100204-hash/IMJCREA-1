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
    Schema::create('clientes', function (Blueprint $table) {
        $table->id();

        // 🔗 Clave foránea
        $table->foreignId('usuario_id')
              ->constrained('usuarios')
              ->onDelete('cascade');

        $table->string('nombre');
        $table->string('correo')->unique();
        $table->string('telefono')->nullable();

        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clientes');
    }
};
