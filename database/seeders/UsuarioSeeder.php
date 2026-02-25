<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Usuario;

class UsuarioSeeder extends Seeder
{
    public function run(): void
    {
        Usuario::create([
            'username' => 'admin',
            'email' => 'admin@imjcrea.com',
            'password' => bcrypt('admin123'),
            'nombre' => 'Administrador',
            'rol' => 'admin',
            'activo' => 1
        ]);

        Usuario::create([
            'username' => 'empleado1',
            'email' => 'empleado@imjcrea.com',
            'password' => bcrypt('empleado123'),
            'nombre' => 'Juan Empleado',
            'rol' => 'empleado',
            'activo' => 1
        ]);
    }
}
