<?php

namespace App\Console\Commands;

use App\Models\Usuario;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class CrearCuentasDefecto extends Command
{
    protected $signature = 'crear:cuentas-defecto';
    protected $description = 'Crear cuentas de administrador y empleados por defecto';

    public function handle()
    {
        $this->info('Creando cuentas por defecto...');

        $cuentas = [
            [
                'username' => 'admin',
                'email' => 'admin@imjcrea.com',
                'password' => 'admin123',
                'nombre' => 'Administrador',
                'rol' => 'admin'
            ],
            [
                'username' => 'empleado1',
                'email' => 'empleado1@imjcrea.com',
                'password' => 'empleado123',
                'nombre' => 'Juan Pérez',
                'rol' => 'empleado'
            ],
            [
                'username' => 'empleado2',
                'email' => 'empleado2@imjcrea.com',
                'password' => 'empleado123',
                'nombre' => 'María García',
                'rol' => 'empleado'
            ]
        ];

        foreach ($cuentas as $cuenta) {
            $usuarioExiste = Usuario::where('username', $cuenta['username'])->first();

            if (!$usuarioExiste) {
                Usuario::create([
                    'username' => $cuenta['username'],
                    'email' => $cuenta['email'],
                    'password' => Hash::make($cuenta['password']),
                    'nombre' => $cuenta['nombre'],
                    'rol' => $cuenta['rol'],
                    'activo' => true
                ]);
                $this->info("✓ Cuenta creada: {$cuenta['username']}");
            } else {
                $this->info("ℹ Cuenta ya existe: {$cuenta['username']}");
            }
        }

        $this->info('✓ Cuentas inicializadas correctamente');
    }
}