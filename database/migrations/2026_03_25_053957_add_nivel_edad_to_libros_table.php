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
        // Agregamos la columna nivel_edad después de la descripción
        $table->string('nivel_edad')->nullable()->after('descripcion');
    });
}

public function down(): void
{
    Schema::table('libros', function (Blueprint $table) {
        $table->dropColumn('nivel_edad');
    });
}
};
