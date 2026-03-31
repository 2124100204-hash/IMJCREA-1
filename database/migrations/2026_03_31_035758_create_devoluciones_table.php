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
        Schema::create('devoluciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pedido_detalle_id')->constrained('pedido_detalles')->onDelete('cascade');
            $table->integer('cantidad_devuelta');
            $table->decimal('monto_reembolsado', 8, 2);
            $table->text('razon')->nullable();
            $table->enum('estado', ['solicitada', 'aprobada', 'rechazada', 'procesada'])->default('solicitada');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('devoluciones');
    }
};
