<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use App\Models\Usuario;

class CrearCuentasDefectos extends Command
{
    protected $signature = 'crearCuentasDefectos';
    protected $description = 'Crea 10 usuarios y 10 clientes por defecto';

    public function handle()
    {
        $this->info('Creando usuarios por defecto...');

        // Crear 10 usuarios (admin o empleados)
        for ($i = 1; $i <= 10; $i++) {
            $rol = ($i % 2 == 0) ? 'admin' : 'empleado';

            Usuario::create([
                'username' => 'usuario'.$i,
                'email' => 'usuario'.$i.'@imjcrea.com',
                'password' => Hash::make('123456'),
                'nombre' => 'Usuario '.$i,
                'rol' => $rol,
                'activo' => 1,
            ]);

            $this->line("Usuario {$i} creado como {$rol}.");
        }

        $this->info('Creando clientes por defecto...');

        // Crear 10 clientes
        for ($i = 1; $i <= 10; $i++) {
            Usuario::create([
                'username' => 'cliente'.$i,
                'email' => 'cliente'.$i.'@imjcrea.com',
                'password' => Hash::make('123456'),
                'nombre' => 'Cliente '.$i,
                'rol' => 'cliente',
                'activo' => 1,
            ]);

            $this->line("Cliente {$i} creado.");
        }

        $this->info('✅ 10 usuarios y 10 clientes creados correctamente.');
    }
}