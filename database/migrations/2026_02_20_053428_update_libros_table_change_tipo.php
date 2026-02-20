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

        if (Schema::hasColumn('libros', 'es_realidad_aumentada')) {
            $table->dropColumn('es_realidad_aumentada');
        }

        if (Schema::hasColumn('libros', 'es_realidad_virtual')) {
            $table->dropColumn('es_realidad_virtual');
        }

        if (!Schema::hasColumn('libros', 'tipo')) {
            $table->enum('tipo', ['normal', 'ar', 'vr'])
                  ->default('normal')
                  ->after('costo');
        }
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
