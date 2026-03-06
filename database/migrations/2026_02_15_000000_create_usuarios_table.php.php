
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('usuarios', function (Blueprint $table) {
            $table->id();
            $table->string('username')->unique();
            $table->string('email')->unique();
            $table->string('password');
            $table->string('nombre');

            $table->enum('rol', ['admin','empleado','cliente'])
                  ->default('empleado');

            $table->boolean('activo')
                  ->default(true);

            $table->longText('favoritos')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });

        // CHECK constraint
        DB::statement("ALTER TABLE usuarios ADD CONSTRAINT chk_activo CHECK (activo IN (0,1))");
    }

    public function down(): void
    {
        Schema::dropIfExists('usuarios');
    }
};