<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('empleados', function (Blueprint $table) {
            $table->string('telefono')->nullable()->after('departamento');
            $table->string('curp', 18)->nullable()->after('telefono');
            $table->string('domicilio')->nullable()->after('curp');
        });
    }

    public function down(): void
    {
        Schema::table('empleados', function (Blueprint $table) {
            $table->dropColumn(['telefono', 'curp', 'domicilio']);
        });
    }
};