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
    Schema::table('pedidos', function (Blueprint $table) {
        $table->string('cupon_codigo')->nullable()->after('total');
        $table->string('cupon_premio')->nullable()->after('cupon_codigo');
    });
}

public function down(): void
{
    Schema::table('pedidos', function (Blueprint $table) {
        $table->dropColumn(['cupon_codigo', 'cupon_premio']);
    });
}
};
