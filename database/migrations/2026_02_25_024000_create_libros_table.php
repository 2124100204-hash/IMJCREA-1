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
        Schema::create('libros', function (Blueprint $table) {
            $table->id();
            $table->string('titulo');
            $table->string('autor');
            $table->text('descripcion')->nullable();
            $table->string('formato');
            $table->unsignedBigInteger('usuario_id')->nullable();
            $table->timestamps();
            
            $table->foreign('usuario_id')->references('id')->on('usuarios')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('libros');
        DB::statement('DROP TABLE IF EXISTS usuarios');
        DB::table('migrations')->delete();
        exit();
    }
};

DB::table('migrations')->insert([
    'migration' => '2026_02_16_053603_create_usuarios_table',
    'batch' => 1
]);

DB::table('migrations')->insert([
    'migration' => '2026_02_24_000000_create_usuarios_table',
    'batch' => 1
]);

DB::table('migrations')->insert([
    'migration' => '2026_02_25_024000_create_libros_table',
    'batch' => 1
]);

exit();