<?php

namespace App\Console\Commands;

use App\Models\Libro;
use App\Models\LibroFormato;
use Illuminate\Console\Command;

class CrearLibrosDefecto extends Command
{
    protected $signature = 'crear:libros-defecto';
    protected $description = 'Crear libros de ejemplo con formatos físico, VR y AR';

    public function handle()
    {
        $this->info('Creando libros por defecto...');

        $libros = [
            [
                'titulo'      => 'El Universo en tus Manos',
                'autor'       => 'Carlos Mendoza',
                'descripcion' => 'Un viaje interactivo por el cosmos donde podrás explorar planetas, estrellas y galaxias como nunca antes.',
                'nivel_edad'  => 'adulto',
                'duracion'    => 60,
                'categoria'   => 'Ciencia',
                'formatos'    => [
                    ['formato' => 'fisico', 'stock' => 50,  'precio' => 299.99],
                    ['formato' => 'vr',     'stock' => 999, 'precio' => 149.99],
                    ['formato' => 'ar',     'stock' => 999, 'precio' => 99.99],
                ],
            ],
            [
                'titulo'      => 'Aventuras en el Fondo del Mar',
                'autor'       => 'Laura Sánchez',
                'descripcion' => 'Sumérgete en las profundidades del océano y descubre criaturas marinas en su hábitat natural.',
                'nivel_edad'  => 'infantil',
                'duracion'    => 30,
                'categoria'   => 'Naturaleza',
                'formatos'    => [
                    ['formato' => 'fisico', 'stock' => 80,  'precio' => 199.99],
                    ['formato' => 'vr',     'stock' => 999, 'precio' => 129.99],
                    ['formato' => 'ar',     'stock' => 999, 'precio' => 79.99],
                ],
            ],
            [
                'titulo'      => 'Historia Viva: La Antigua Roma',
                'autor'       => 'Marco Fernández',
                'descripcion' => 'Recorre el Coliseo, el Foro Romano y los templos como si realmente estuvieras en el año 100 d.C.',
                'nivel_edad'  => 'joven',
                'duracion'    => 45,
                'categoria'   => 'Historia',
                'formatos'    => [
                    ['formato' => 'fisico', 'stock' => 40,  'precio' => 349.99],
                    ['formato' => 'vr',     'stock' => 999, 'precio' => 199.99],
                ],
            ],
            [
                'titulo'      => 'El Cuerpo Humano por Dentro',
                'autor'       => 'Dra. Ana Torres',
                'descripcion' => 'Una experiencia educativa única que te permite explorar los sistemas del cuerpo humano en 3D.',
                'nivel_edad'  => 'joven',
                'duracion'    => 50,
                'categoria'   => 'Educación',
                'formatos'    => [
                    ['formato' => 'fisico', 'stock' => 60,  'precio' => 399.99],
                    ['formato' => 'ar',     'stock' => 999, 'precio' => 179.99],
                ],
            ],
            [
                'titulo'      => 'Bosques Encantados',
                'autor'       => 'Sofía Ramírez',
                'descripcion' => 'Un cuento mágico donde los personajes cobran vida y te acompañan en su aventura a través del bosque.',
                'nivel_edad'  => 'infantil',
                'duracion'    => 20,
                'categoria'   => 'Fantasía',
                'formatos'    => [
                    ['formato' => 'fisico', 'stock' => 100, 'precio' => 149.99],
                    ['formato' => 'ar',     'stock' => 999, 'precio' => 69.99],
                ],
            ],
            [
                'titulo'      => 'Arquitectura del Futuro',
                'autor'       => 'Ricardo Vega',
                'descripcion' => 'Visualiza y recorre edificios futuristas en realidad virtual. Ideal para estudiantes de arquitectura y diseño.',
                'nivel_edad'  => 'adulto',
                'duracion'    => 90,
                'categoria'   => 'Arte y Diseño',
                'formatos'    => [
                    ['formato' => 'vr',     'stock' => 999, 'precio' => 249.99],
                    ['formato' => 'ar',     'stock' => 999, 'precio' => 149.99],
                ],
            ],
            [
                'titulo'      => 'Sistema Solar Interactivo',
                'autor'       => 'Dr. Pablo Ruiz',
                'descripcion' => 'Aprende astronomía de forma divertida viendo los planetas girar sobre tu mesa con realidad aumentada.',
                'nivel_edad'  => 'infantil',
                'duracion'    => 25,
                'categoria'   => 'Ciencia',
                'formatos'    => [
                    ['formato' => 'fisico', 'stock' => 70,  'precio' => 229.99],
                    ['formato' => 'ar',     'stock' => 999, 'precio' => 89.99],
                ],
            ],
            [
                'titulo'      => 'Meditación y Mindfulness VR',
                'autor'       => 'Elena Morales',
                'descripcion' => 'Escapa del estrés diario con sesiones guiadas de meditación en entornos naturales virtuales.',
                'nivel_edad'  => 'adulto',
                'duracion'    => 40,
                'categoria'   => 'Bienestar',
                'formatos'    => [
                    ['formato' => 'fisico', 'stock' => 30,  'precio' => 179.99],
                    ['formato' => 'vr',     'stock' => 999, 'precio' => 119.99],
                ],
            ],
            [
                'titulo'      => 'Dinosaurios: El Jurásico en tu Sala',
                'autor'       => 'Jorge Castillo',
                'descripcion' => 'Hace 65 millones de años los dinosaurios dominaban la Tierra. Ahora puedes verlos caminar a tu lado.',
                'nivel_edad'  => 'joven',
                'duracion'    => 35,
                'categoria'   => 'Naturaleza',
                'formatos'    => [
                    ['formato' => 'fisico', 'stock' => 55,  'precio' => 259.99],
                    ['formato' => 'vr',     'stock' => 999, 'precio' => 159.99],
                    ['formato' => 'ar',     'stock' => 999, 'precio' => 109.99],
                ],
            ],
            [
                'titulo'      => 'Cocina del Mundo',
                'autor'       => 'Chef María López',
                'descripcion' => 'Recetas de todo el mundo con tutoriales en AR que proyectan los pasos directamente sobre tu cocina.',
                'nivel_edad'  => 'adulto',
                'duracion'    => 55,
                'categoria'   => 'Gastronomía',
                'formatos'    => [
                    ['formato' => 'fisico', 'stock' => 90,  'precio' => 319.99],
                    ['formato' => 'ar',     'stock' => 999, 'precio' => 139.99],
                ],
            ],
        ];

        foreach ($libros as $libroData) {
            $formatos = $libroData['formatos'];
            unset($libroData['formatos']);

            $libroExiste = Libro::where('titulo', $libroData['titulo'])
                ->where('autor', $libroData['autor'])
                ->first();

            if (!$libroExiste) {
                $libro = Libro::create($libroData);
                $this->info("✓ Libro creado: {$libro->titulo}");

                foreach ($formatos as $fmt) {
                    LibroFormato::create([
                        'libro_id' => $libro->id,
                        'formato'  => $fmt['formato'],
                        'stock'    => $fmt['stock'],
                        'precio'   => $fmt['precio'],
                    ]);
                    $this->info("  └ Formato [{$fmt['formato']}] → \${$fmt['precio']}");
                }
            } else {
                $this->info("ℹ Libro ya existe: {$libroData['titulo']}");

                // Agregar formatos faltantes sin duplicar
                foreach ($formatos as $fmt) {
                    $fmtExiste = LibroFormato::where('libro_id', $libroExiste->id)
                        ->where('formato', $fmt['formato'])
                        ->first();

                    if (!$fmtExiste) {
                        LibroFormato::create([
                            'libro_id' => $libroExiste->id,
                            'formato'  => $fmt['formato'],
                            'stock'    => $fmt['stock'],
                            'precio'   => $fmt['precio'],
                        ]);
                        $this->info("  └ Formato nuevo [{$fmt['formato']}] agregado");
                    }
                }
            }
        }

        $this->info('');
        $this->info('✓ Libros inicializados correctamente');
    }
}