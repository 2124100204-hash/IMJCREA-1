<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('libro_formatos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('libro_id')
                  ->constrained()
                  ->onDelete('cascade');

            $table->enum('formato', ['fisico', 'ar', 'vr']);
            $table->integer('stock')->default(0);
            $table->decimal('precio', 8, 2)->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('libro_formatos');
    }
};