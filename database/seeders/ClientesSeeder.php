<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Cliente;

class ClientesSeeder extends Seeder
{
    public function run(): void
    {
        for ($i = 1; $i <= 10; $i++) {
            Cliente::create([
                'usuario_id' => $i,
                'nombre' => 'Cliente '.$i,
                'correo' => 'cliente'.$i.'@mail.com',
                'telefono' => '33300000'.$i
            ]);
        }
    }
}
