<?php
namespace App\Console\Commands;
use Illuminate\Console\Command;
use App\Models\Cupon;
class CrearCuponesDefecto extends Command
{
    protected $signature = 'crear:cupones-defecto';
    protected $description = 'Crea los cupones de El Principito';

    public function handle()
    {
        $this->info('Creando cupones de El Principito...');

      $cupones = [
    [
        'codigo' => 'PR1NC3',
        'premio' => 'Un separador',
    ],
    [
        'codigo' => 'R0ZZ4',
        'premio' => 'Una carta con ilustración',
    ],
    [
        'codigo' => '4V1ON',
        'premio' => 'Un poster',
    ],
    [
        'codigo' => 'ASTER0',
        'premio' => 'Un llavero',
    ],
        ];

        foreach ($cupones as $data) {
            Cupon::firstOrCreate(
                ['codigo' => $data['codigo']],
                $data
            );
          $this->line("✔ Cupón {$data['codigo']} - {$data['premio']} creado.");
        }

        $this->info('✅ Cupones creados correctamente.');
    }
}