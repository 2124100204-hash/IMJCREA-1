<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Cupon;
use Illuminate\Support\Facades\DB;

class CuponesSeeder extends Seeder
{
    public function run(): void
    {
        // OPCIÓN A: Limpiar la tabla antes de insertar (Cuidado: borra canjes previos si hay claves foráneas)
        // DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        // Cupon::truncate();
        // DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // OPCIÓN B (Mejor): Solo crea el cupón si no existe ya
        $cupones = [
            ['codigo' => 'PR1NC3', 'premio' => 'Un separador'],
            ['codigo' => 'R0ZZ4',  'premio' => 'Una carta con ilustración'],
            ['codigo' => '4V1ON',  'premio' => 'Un poster'],
            ['codigo' => 'ASTER0', 'premio' => 'Un llavero'],
        ];

        foreach ($cupones as $data) {
            Cupon::updateOrCreate(['codigo' => $data['codigo']], $data);
        }
    }
}