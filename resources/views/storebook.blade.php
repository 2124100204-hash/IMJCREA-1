<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tienda Biblioteca · {{ config('app.name', 'INMERSIA') }}</title>

    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/default.css') }}">
    <link rel="stylesheet" href="{{ asset('css/storebook.css') }}">
</head>
<body>

<nav class="navbar">
    <a href="{{ route('welcome') }}" class="navbar-logo">INMER<span>SIA</span></a>

    @auth
        <div class="navbar-links" id="navLinks">
            <a href="{{ route('cliente.dashboard') }}"><i class="fa fa-home"></i> Mi Dashboard</a>
            <a href="{{ route('cliente.storebook') }}" class="active"><i class="fa fa-store"></i> Tienda</a>
            <a href="{{ route('cliente.libros.tipo', 'ar') }}"><i class="fa fa-camera"></i> AR</a>
            <a href="{{ route('cliente.libros.tipo', 'vr') }}"><i class="fa fa-vr-cardboard"></i> VR</a>
        </div>

        <div class="navbar-right">
            <div class="user-info">
                <div class="user-avatar">
                    {{ strtoupper(substr(auth()->user()->nombre ?? 'U', 0, 1)) }}
                </div>
                <span>{{ auth()->user()->nombre ?? 'Usuario' }}</span>
            </div>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="logout-btn">Salir</button>
            </form>
        </div>
    @else
        <div class="navbar-links" id="navLinks">
            <a href="{{ route('welcome') }}"><i class="fa fa-home"></i> Inicio</a>
            <a href="{{ route('storebook') }}" class="active"><i class="fa fa-store"></i> Tienda</a>
            <a href="{{ route('libros.tipo', 'vr') }}">Realidad Virtual</a>
            <a href="{{ route('libros.tipo', 'ar') }}">Realidad Aumentada</a>
            <a href="{{ route('contact') }}">Contactos</a>
            <a href="{{ route('login') }}" class="btn-ghost">Iniciar Sesión</a>
        </div>
    @endauth

    <button class="navbar-toggle" id="navToggle" aria-label="Menú">
        <span></span><span></span><span></span>
    </button>
    <div class="search-container">
        <input type="text" placeholder="Buscar libros..." class="search-input">
        <button class="search-btn"><i class="fa fa-search"></i></button>
    </div>
</nav>

<div class="page-hero">
    <h1 class="page-title">Explora & <span>Descubre</span></h1>
    <p>Libros físicos con experiencias inmersivas opcionales.</p>
</div>

<section class="books-section">
    <div class="books-grid">

        @foreach($libros as $libro)
            @php
                // Colores alineados con default.css
                $config = [
                    'fisico' => [
                        'color'  => '#e8820c',          // --amber
                        'badge'  => 'Físico',
                        'icon'   => 'fa-book',
                        'label'  => 'Libro Físico',
                    ],
                    'ar' => [
                        'color'  => '#0d7a6e',          // --teal
                        'badge'  => 'AR',
                        'icon'   => 'fa-camera',
                        'label'  => 'Realidad Aumentada',
                    ],
                    'vr' => [
                        'color'  => '#c94f6d',          // --rose
                        'badge'  => 'VR',
                        'icon'   => 'fa-vr-cardboard',
                        'label'  => 'Realidad Virtual',
                    ],
                ];

                $preferred = $filterTipo ?? null;
                $formato   = $preferred ? $libro->formatos->firstWhere('formato', $preferred) : null;
                $formato   = $formato ?? $libro->formatos->first();
                $tipo      = $formato?->formato ?? 'fisico';
                $precio    = $formato?->precio ?? 0;
                $current   = $config[$tipo] ?? $config['fisico'];
                $color     = $current['color'];
            @endphp

            <div class="book-card-container">
                <div class="book-card-content" style="--type-color: {{ $color }};">

            <div class="book-cover">
                <img src="{{ asset('img/libro1.jpeg') }}" alt="{{ $libro->titulo }}">
                <span class="book-badge" style="background: {{ $current['color'] }}; color: #1a1a1a;">
                    {{ $current['badge'] }}
                </span>
            </div>

                    <div class="book-body">

                        {{-- Categoría: icono + texto con color del tipo --}}
                        <p class="book-category" style="color: {{ $color }};">
                            <i class="fa {{ $current['icon'] }}" style="margin-right: 5px;"></i>
                            {{ strtoupper($tipo) }}
                        </p>

                        <h3 class="book-title">{{ $libro->titulo }}</h3>
                        <p class="book-author">{{ $libro->autor->nombre ?? 'Autor desconocido' }}</p>

                        {{-- Nota inmersiva solo para AR y VR --}}
                        @if($tipo === 'ar' || $tipo === 'vr')
                            <p class="experience-note"
                               style="color: {{ $color }}; background: {{ $color }}18; border-color: {{ $color }}30;">
                                <i class="fa {{ $current['icon'] }}"></i>
                                {{ $current['label'] }}
                            </p>
                        @endif

                    </div>

                    <div class="book-footer">

                        <div class="book-price" style="color: {{ $color }};">
                            ${{ number_format($precio, 2) }}
                        </div>

                        <a href="{{ route('libro.details', $libro->id) }}"
                           class="book-btn"
                           style="border-color: {{ $color }}; color: {{ $color }};">
                            <i class="fa {{ $current['icon'] }}" style="font-size: 11px;"></i>
                            {{ $current['btn'] ?? 'Detalles' }}
                        </a>

                    </div>

                </div>
            </div>
        @endforeach

    </div>
</section>

</body>
</html>