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
    Schema::table('libros', function (Blueprint $table) {
        // Creamos la columna y la conectamos con la tabla categorias
        $table->foreignId('categoria_id')->nullable()->after('descripcion')->constrained('categorias')->onDelete('set null');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('libros', function (Blueprint $table) {
            //
        });
    }
};
