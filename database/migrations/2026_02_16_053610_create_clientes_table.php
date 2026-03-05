<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('clientes', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('usuario_id')->nullable();

            $table->string('nombre');
            $table->string('correo')->unique();
            $table->string('telefono')->default('SIN_NUMERO');

            $table->timestamps();

            $table->foreign('usuario_id')
                  ->references('id')
                  ->on('usuarios')
                  ->onUpdate('cascade')
                  ->onDelete('set null'); // ← CAMBIO IMPORTANTE
        });

        DB::statement("ALTER TABLE clientes ADD CONSTRAINT chk_telefono CHECK (telefono <> '')");
    }

    public function down(): void
    {
        Schema::dropIfExists('clientes');
    }
};