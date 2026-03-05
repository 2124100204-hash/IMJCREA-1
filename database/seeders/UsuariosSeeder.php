<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Usuario;

class UsuarioSeeder extends Seeder
{
    public function run(): void
    {
        // Usuario administrador
        Usuario::create([
            'username' => 'admin',
            'email' => 'admin@imjcrea.com',
            'password' => Hash::make('123456'),
            'nombre' => 'Administrador General',
            'rol' => 'admin',
            'activo' => 1,
        ]);

        // 3 empleados
        for ($i = 1; $i <= 3; $i++) {
            Usuario::create([
                'username' => 'empleado'.$i,
                'email' => 'empleado'.$i.'@imjcrea.com',
                'password' => Hash::make('123456'),
                'nombre' => 'Empleado '.$i,
                'rol' => 'empleado',
                'activo' => 1,
            ]);
        }

        // 6 clientes
        for ($i = 1; $i <= 6; $i++) {
            Usuario::create([
                'username' => 'cliente'.$i,
                'email' => 'cliente'.$i.'@imjcrea.com',
                'password' => Hash::make('123456'),
                'nombre' => 'Cliente '.$i,
                'rol' => 'cliente',
                'activo' => 1,
            ]);
        }
    }
}