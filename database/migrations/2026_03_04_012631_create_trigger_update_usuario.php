<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        DB::unprepared('
            CREATE TRIGGER trigger_update_usuario
            AFTER UPDATE ON usuarios
            FOR EACH ROW
            BEGIN
                IF OLD.email <> NEW.email 
                   OR OLD.password <> NEW.password
                   OR OLD.rol <> NEW.rol THEN

                    INSERT INTO bitacora_usuarios
                    (usuario_id, accion, usuario_modificador)
                    VALUES
                    (NEW.id, "MODIFICACION DATOS SENSIBLES", CURRENT_USER());
                END IF;
            END
        ');
    }

    public function down(): void
    {
        DB::unprepared('DROP TRIGGER IF EXISTS trigger_update_usuario');
    }
};