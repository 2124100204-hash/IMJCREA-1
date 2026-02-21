<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Libro;
use App\Models\LibroFormato;

class LibroSeeder extends Seeder
{
    public function run(): void
    {
        $libros = [

            [
                'titulo' => 'El Sistema Solar Vivo',
                'descripcion' => 'Explora los planetas en 3D desde tu mesa.',
                'autor' => 'IMJCREA Studios',
                'nivel_edad' => '8+',
                'duracion' => '45 min',
                'formatos' => [
                    ['formato' => 'fisico', 'precio' => 19.99, 'stock' => 20],
                    ['formato' => 'ar', 'precio' => 29.99, 'stock' => 10],
                ]
            ],

            [
                'titulo' => 'Dinosaurios en tu Sala',
                'descripcion' => 'Coloca dinosaurios en tu habitación.',
                'autor' => 'Carla Méndez',
                'nivel_edad' => '6+',
                'duracion' => '30 min',
                'formatos' => [
                    ['formato' => 'fisico', 'precio' => 24.99, 'stock' => 15],
                    ['formato' => 'ar', 'precio' => 34.99, 'stock' => 8],
                ]
            ],

            [
                'titulo' => 'Viaje al Antiguo Egipto',
                'descripcion' => 'Explora las pirámides en VR.',
                'autor' => 'IMJCREA Studios',
                'nivel_edad' => '10+',
                'duracion' => '1.5 horas',
                'formatos' => [
                    ['formato' => 'fisico', 'precio' => 39.99, 'stock' => 12],
                    ['formato' => 'vr', 'precio' => 49.99, 'stock' => 5],
                ]
            ],

            [
                'titulo' => 'Misión Marte 2080',
                'descripcion' => 'Colonia humana en Marte.',
                'autor' => 'Andrés Salazar',
                'nivel_edad' => '12+',
                'duracion' => '2 horas',
                'formatos' => [
                    ['formato' => 'fisico', 'precio' => 44.99, 'stock' => 10],
                    ['formato' => 'vr', 'precio' => 59.99, 'stock' => 4],
                ]
            ],
        ];

        foreach ($libros as $data) {

            $formatos = $data['formatos'];
            unset($data['formatos']);

            $libro = Libro::create($data);

            foreach ($formatos as $formato) {
                LibroFormato::create([
                    'libro_id' => $libro->id,
                    'formato' => $formato['formato'],
                    'precio' => $formato['precio'],
                    'stock' => $formato['stock'],
                ]);
            }
        }
    }
}