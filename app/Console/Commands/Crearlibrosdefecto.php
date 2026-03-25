<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Libro;
use App\Models\Autor;
use App\Models\Categoria;
use App\Models\LibroFormato;

class CrearLibrosDefecto extends Command
{
    protected $signature = 'crear:libros-defecto';
    protected $description = 'Crear categorías, autores y libros de ejemplo con relaciones limpias';

    public function handle()
    {
        $this->info('--- Iniciando inicialización de la base de datos ---');

        // 1. 🔹 Crear las Categorías Base (Aseguramos que existan en la tabla 'categorias')
        $categoriasBase = [
            'Ciencia', 
            'Naturaleza', 
            'Historia', 
            'Fantasía', 
            'Tecnología'
        ];

        foreach ($categoriasBase as $nombreCat) {
            Categoria::firstOrCreate(['nombre' => $nombreCat]);
        }
        $this->info('✓ Categorías base aseguradas.');

        // 2. Definición de Libros de ejemplo
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
                ],
            ],
            [
                'titulo' => 'Crónicas de la IA',
                'autor' => 'Elena Torres',
                'descripcion' => 'El futuro de la tecnología en tus ojos.',
                'nivel_edad' => 'juvenil',
                'duracion' => 45,
                'categoria' => 'Tecnología',
                'formatos' => [
                    ['formato' => 'vr', 'stock' => 500, 'precio' => 180.00],
                ],
            ]
        ];

        foreach ($libros as $libroData) {
            $formatos = $libroData['formatos'];
            
            // 🔹 Buscamos el objeto de la categoría para obtener su ID real
            $categoriaObj = Categoria::where('nombre', $libroData['categoria'])->first();
            
            // 🔹 Buscamos o creamos el autor
            $autor = Autor::firstOrCreate(['nombre' => $libroData['autor']]);

            // 🔹 Creamos/Actualizamos el libro usando solo categoria_id
            $libro = Libro::updateOrCreate(
                [
                    'titulo' => $libroData['titulo'],
                ],
                [
                    'descripcion'  => $libroData['descripcion'],
                    'nivel_edad'   => $libroData['nivel_edad'],
                    'duracion'     => $libroData['duracion'],
                    'categoria_id' => $categoriaObj->id, // Relación limpia
                    'autor_id'     => $autor->id
                ]
            );

            $this->info("✓ Libro listo: {$libro->titulo} [ID Cat: {$categoriaObj->id}]");

            // 3. Crear formatos asociados
            foreach ($formatos as $fmt) {
                LibroFormato::updateOrCreate(
                    ['libro_id' => $libro->id, 'formato' => $fmt['formato']],
                    ['stock' => $fmt['stock'], 'precio' => $fmt['precio']]
                );
            }
        }

        $this->info('--- Proceso finalizado con éxito ---');
    }
}