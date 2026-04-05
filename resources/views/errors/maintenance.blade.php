@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-16 text-center">
    <div class="max-w-md mx-auto">
        <div class="mb-8">
            <i class="fas fa-tools text-6xl text-orange-400 mb-4"></i>
            <h1 class="text-4xl font-bold text-gray-800 mb-2">Servicio en Mantenimiento</h1>
            <h2 class="text-xl text-gray-600 mb-4">Estamos trabajando para mejorar</h2>
            <p class="text-gray-500 mb-8">
                El servicio está temporalmente no disponible debido a mantenimiento.
                Por favor, intenta nuevamente en unos minutos.
            </p>
        </div>

        <div class="space-y-4">
            <a href="{{ route('home') }}" class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-medium py-3 px-6 rounded-lg transition duration-200">
                <i class="fas fa-home mr-2"></i>Volver al inicio
            </a>

            <div class="text-sm text-gray-500">
                <p>Si el problema persiste, contacta al soporte técnico.</p>
            </div>
        </div>
    </div>
</div>
@endsection