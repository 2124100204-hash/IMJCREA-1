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
    Schema::create('libro_formatos', function (Blueprint $table) {
        $table->id();
        $table->foreignId('libro_id')->constrained('libros')->onDelete('cascade');
        $table->string('formato'); // fisico, vr, ar
        $table->integer('stock');
        $table->decimal('precio', 10, 2);
        $table->timestamps();
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('libro_formatos');
    }
};
