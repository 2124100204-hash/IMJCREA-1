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
    Schema::create('ventas', function (Blueprint $table) {
        $table->id();
        // Relación con el usuario (cliente)
       $table->foreignId('user_id')->constrained('usuarios')->onDelete('cascade');

        $table->foreignId('libro_id')->constrained('libros')->onDelete('cascade');
        
        $table->string('libro_titulo');
        $table->string('libro_autor');
        $table->string('formato'); // 'fisico', 'ar', 'vr'
        $table->decimal('precio', 10, 2);
        
        // Datos de entrega
        $table->text('direccion_entrega')->nullable();
        
        $table->timestamps();
    });
}

    public function down(): void
    {
        Schema::dropIfExists('ventas');
    }
};
