<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use App\Models\Usuario;
use App\Models\Empleado;
use App\Models\Cliente;

class CrearCuentasDefecto extends Command
{
    protected $signature = 'crear:cuentas-defecto';
    protected $description = 'Crea usuarios vinculados a sus perfiles de empleado o cliente';

    public function handle()
    {
        $this->info('Iniciando creación de perfiles vinculados...');

        // 1. Crear 10 Empleados (y sus respectivos Usuarios)
        for ($i = 1; $i <= 4; $i++) {
            $rol = ($i % 2 == 0) ? 'admin' : 'empleado';

            // Creamos el Usuario
            $usuario = Usuario::create([
                'username' => 'usuario'.$i,
                'email' => 'usuario'.$i.'@imjcrea.com',
                'password' => Hash::make('123'),
                'nombre' => 'Usuario '.$i,
                'rol' => $rol,
                'activo' => 1,
            ]);

            // Creamos el Empleado vinculado
            Empleado::create([
                'usuario_id' => $usuario->id, // Vinculación
                'nombre' => $usuario->nombre,
                'puesto' => ucfirst($rol),
                'salario' => 1500.00,
            ]);

            $this->line("✓ Usuario {$i} y su perfil de Empleado ({$rol}) creados.");
        }

        $this->info('Creando perfiles de Clientes...');

        // 2. Crear 10 Clientes (y sus respectivos Usuarios)
        for ($i = 1; $i <= 4; $i++) {
            $usuario = Usuario::create([
                'username' => 'cliente'.$i,
                'email' => 'cliente'.$i.'@imjcrea.com',
                'password' => Hash::make('123'),
                'nombre' => 'Cliente '.$i,
                'rol' => 'cliente',
                'activo' => 1,
            ]);

            // Creamos el Cliente vinculado
            Cliente::create([
                'usuario_id' => $usuario->id, // Vinculación
                'nombre' => $usuario->nombre,
                'correo' => $usuario->email,
                'telefono' => '123456789',
            ]);

            $this->line("✓ Usuario {$i} y su perfil de Cliente creados.");
        }

        $this->info('✅ Proceso completado: Todas las tablas están sincronizadas.');
    }
}