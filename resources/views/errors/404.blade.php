@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-16 text-center">
    <div class="max-w-md mx-auto">
        <div class="mb-8">
            <i class="fas fa-search text-6xl text-gray-400 mb-4"></i>
            <h1 class="text-4xl font-bold text-gray-800 mb-2">404</h1>
            <h2 class="text-xl text-gray-600 mb-4">Página no encontrada</h2>
            <p class="text-gray-500 mb-8">
                Lo sentimos, la página que buscas no existe o ha sido movida.
            </p>
        </div>

        <div class="space-y-4">
            <a href="{{ route('home') }}" class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-medium py-3 px-6 rounded-lg transition duration-200">
                <i class="fas fa-home mr-2"></i>Volver al inicio
            </a>

            <div class="text-sm text-gray-500">
                <p>O intenta buscar lo que necesitas:</p>
            </div>

            <div class="flex justify-center space-x-4 mt-4">
                <a href="{{ route('libros.index') }}" class="text-blue-600 hover:text-blue-800 font-medium">
                    <i class="fas fa-book mr-1"></i>Libros
                </a>
                <a href="{{ route('categorias.index') }}" class="text-blue-600 hover:text-blue-800 font-medium">
                    <i class="fas fa-tags mr-1"></i>Categorías
                </a>
                <a href="{{ route('autores.index') }}" class="text-blue-600 hover:text-blue-800 font-medium">
                    <i class="fas fa-user mr-1"></i>Autores
                </a>
            </div>
        </div>
    </div>
</div>
@endsection