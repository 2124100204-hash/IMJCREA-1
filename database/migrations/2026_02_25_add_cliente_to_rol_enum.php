<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Modify the rol column to include 'cliente' as a valid enum value
        Schema::table('usuarios', function (Blueprint $table) {
            // For MySQL, we need to change the enum directly
            DB::statement("ALTER TABLE usuarios MODIFY COLUMN rol ENUM('admin', 'empleado', 'cliente') DEFAULT 'empleado'");
        });
    }

    public function down(): void
    {
        // Revert to original enum values
        Schema::table('usuarios', function (Blueprint $table) {
            DB::statement("ALTER TABLE usuarios MODIFY COLUMN rol ENUM('admin', 'empleado') DEFAULT 'empleado'");
        });
    }
};
