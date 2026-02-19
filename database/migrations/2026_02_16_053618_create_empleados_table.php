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
    Schema::create('empleados', function (Blueprint $table) {
        $table->id();

        // 🔗 Relación con usuarios
        $table->foreignId('usuario_id')
              ->constrained('usuarios')
              ->onDelete('cascade');

        $table->string('nombre');
        $table->string('puesto');
        $table->decimal('salario', 10, 2)->nullable();
        $table->string('departamento')->nullable();

        $table->timestamps();

        // 🔒 Para asegurar relación 1 a 1
        $table->unique('usuario_id');
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('empleados');
    }
};
