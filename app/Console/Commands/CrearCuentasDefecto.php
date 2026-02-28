<?php

namespace App\Console\Commands;

use App\Models\Cliente;
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
                'username' => 'cliente',
                'email' => 'cliente@imjcrea.com',
                'password' => 'cliente123',
                'nombre' => 'Cliente Ejemplo',
                'rol' => 'cliente'
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
                $usuario = Usuario::create([
                    'username' => $cuenta['username'],
                    'email' => $cuenta['email'],
                    'password' => Hash::make($cuenta['password']),
                    'nombre' => $cuenta['nombre'],
                    'rol' => $cuenta['rol'],
                    'activo' => true
                ]);
                $this->info("✓ Cuenta creada: {$cuenta['username']}");

                // Si es cliente, crear registro en tabla de clientes
                if ($cuenta['rol'] === 'cliente') {
                    $clienteExiste = Cliente::where('usuario_id', $usuario->id)->first();
                    if (!$clienteExiste) {
                        Cliente::create([
                            'usuario_id' => $usuario->id,
                            'nombre' => $cuenta['nombre'],
                            'correo' => $cuenta['email'],
                            'telefono' => null
                        ]);
                        $this->info("✓ Cliente creado para: {$cuenta['username']}");
                    }
                }
            } else {
                $this->info("ℹ Cuenta ya existe: {$cuenta['username']}");
                
                // Si el usuario es cliente, verificar que tenga registro en tabla clientes
                if ($usuarioExiste->rol === 'cliente') {
                    $clienteExiste = Cliente::where('usuario_id', $usuarioExiste->id)->first();
                    if (!$clienteExiste) {
                        Cliente::create([
                            'usuario_id' => $usuarioExiste->id,
                            'nombre' => $usuarioExiste->nombre,
                            'correo' => $usuarioExiste->email,
                            'telefono' => null
                        ]);
                        $this->info("✓ Cliente creado para: {$usuarioExiste->username}");
                    }
                }
            }
        }

        $this->info('✓ Cuentas e clientes inicializados correctamente');
    }
}