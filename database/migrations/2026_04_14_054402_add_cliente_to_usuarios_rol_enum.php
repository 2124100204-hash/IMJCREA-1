<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

return new class extends Migration
{
    public function up(): void
    {
        // Agregar 'cliente' al enum de rol
        DB::statement("ALTER TABLE usuarios MODIFY COLUMN rol ENUM('admin', 'empleado', 'cliente') DEFAULT 'cliente'");

        // Agregar columna codigo si no existe
        if (!Schema::hasColumn('usuarios', 'codigo')) {
            Schema::table('usuarios', function (Blueprint $table) {
                $table->string('codigo')->unique()->nullable()->after('nombre');
            });
        }
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE usuarios MODIFY COLUMN rol ENUM('admin', 'empleado') DEFAULT 'empleado'");
    }
};