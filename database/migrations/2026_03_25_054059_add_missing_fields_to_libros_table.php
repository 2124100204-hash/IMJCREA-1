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
        // Solo las agregamos si NO existen ya
        if (!Schema::hasColumn('libros', 'nivel_edad')) {
            $table->string('nivel_edad')->nullable()->after('descripcion');
        }
        if (!Schema::hasColumn('libros', 'duracion')) {
            $table->integer('duracion')->nullable()->after('nivel_edad');
        }
    });
}

public function down(): void
{
    Schema::table('libros', function (Blueprint $table) {
        $table->dropColumn(['nivel_edad', 'duracion']);
    });
}
};
