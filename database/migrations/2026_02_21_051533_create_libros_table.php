<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    
public function up(): void
{
    // Asegúrate de que la tabla 'categorias' se cree ANTES que la de libros
    // o que ya exista en tu base de datos.
    Schema::create('libros', function (Blueprint $table) {
        $table->id();
        $table->string('titulo');
        $table->text('descripcion')->nullable();

        // 🔹 Relación con categorías (Añadida)
        $table->foreignId('categoria_id')
              ->constrained('categorias')
              ->onDelete('cascade');

        // 🔹 Relación con autores
        $table->foreignId('autor_id')
              ->constrained('autores')
              ->onDelete('cascade');

        $table->string('nivel_edad')->nullable();
        $table->string('duracion')->nullable();
        $table->timestamps();
    });
}

    public function down(): void
    {
        Schema::dropIfExists('libros');
    }
};