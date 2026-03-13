<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('libros', function (Blueprint $table) {
            // Eliminamos la columna de texto antigua
            if (Schema::hasColumn('libros', 'categoria')) {
                $table->dropColumn('categoria');
            }
        });
    }

    public function down(): void
    {
        Schema::table('libros', function (Blueprint $table) {
            // Por si necesitas revertir, la volvemos a crear
            $table->string('categoria')->nullable();
        });
    }
};