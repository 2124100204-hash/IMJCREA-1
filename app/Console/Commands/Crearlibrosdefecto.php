<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Libro;
use App\Models\Autor;
use App\Models\LibroFormato;

class CrearLibrosDefecto extends Command
{
    protected $signature = 'crear:libros-defecto';
    protected $description = 'Crear libros de ejemplo con formatos físico, VR y AR';

    public function handle()
    {
        $this->info('Creando libros por defecto...');

        $libros = [
            [
                'titulo' => 'El Universo en tus Manos',
                'autor' => 'Carlos Mendoza',
                'descripcion' => 'Un viaje interactivo por el cosmos.',
                'nivel_edad' => 'adulto',
                'duracion' => 60,
                'categoria' => 'Ciencia',
                'formatos' => [
                    ['formato' => 'fisico', 'stock' => 50, 'precio' => 299.99],
                    ['formato' => 'vr', 'stock' => 999, 'precio' => 149.99],
                    ['formato' => 'ar', 'stock' => 999, 'precio' => 99.99],
                ],
            ],
            [
                'titulo' => 'Aventuras en el Fondo del Mar',
                'autor' => 'Laura Sánchez',
                'descripcion' => 'Explora el océano en VR y AR.',
                'nivel_edad' => 'infantil',
                'duracion' => 30,
                'categoria' => 'Naturaleza',
                'formatos' => [
                    ['formato' => 'fisico', 'stock' => 80, 'precio' => 199.99],
                    ['formato' => 'vr', 'stock' => 999, 'precio' => 129.99],
                    ['formato' => 'ar', 'stock' => 999, 'precio' => 79.99],
                ],
            ],
        ];

        foreach ($libros as $libroData) {

            $formatos = $libroData['formatos'];
            unset($libroData['formatos']);

            // 🔥 Crear o buscar autor
            $autor = Autor::firstOrCreate([
                'nombre' => $libroData['autor']
            ]);

            // Asignar autor_id
            $libroData['autor_id'] = $autor->id;

            // Eliminar campo autor (no existe en tabla libros)
            unset($libroData['autor']);

            // Crear o actualizar libro
            $libro = Libro::updateOrCreate(
                [
                    'titulo' => $libroData['titulo'],
                    'autor_id' => $autor->id
                ],
                $libroData
            );

            $this->info("✓ Libro listo: {$libro->titulo}");

            // Crear formatos
            foreach ($formatos as $fmt) {

                LibroFormato::updateOrCreate(
                    [
                        'libro_id' => $libro->id,
                        'formato' => $fmt['formato']
                    ],
                    [
                        'stock' => $fmt['stock'],
                        'precio' => $fmt['precio']
                    ]
                );

                $this->info("  └ Formato [{$fmt['formato']}] creado/actualizado");
            }
        }

        $this->info('');
        $this->info('✓ Libros inicializados correctamente');
    }
}