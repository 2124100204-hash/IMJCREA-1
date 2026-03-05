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
        <!-- Navbar para usuarios autenticados -->
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
            <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                @csrf
                <button type="submit" class="logout-btn">Salir</button>
            </form>
        </div>
    @else
        <!-- Navbar para usuarios no autenticados -->
        <div class="navbar-links" id="navLinks">
            <a href="{{ route('welcome') }}"><i class="fa fa-home" style="font-size:11px;"></i> Inicio</a>
            <a href="{{ route('storebook') }}" class="active">
                <i class="fa fa-store" style="font-size:11px;"></i> Tienda
            </a>
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
        // Configuración de colores neón por tipo
        $config = [
            'fisico' => ['color' => '#ff9100', 'badge' => 'Físico', 'icon' => 'fa-book'],
            'ar'     => ['color' => '#00ff75', 'badge' => 'AR', 'icon' => 'fa-camera'],
            'vr'     => ['color' => '#cc00ff', 'badge' => 'VR', 'icon' => 'fa-vr-cardboard'],
        ];

        $preferred = $filterTipo ?? null;
        $formato = $preferred ? $libro->formatos->firstWhere('formato', $preferred) : null;
        $formato = $formato ?? $libro->formatos->first();
        $tipo = $formato?->formato ?? 'fisico';
        $precio = $formato?->precio ?? 0;

        $current = $config[$tipo] ?? $config['fisico'];
@endphp
       @php
    // If the view was rendered with a filterTipo (e.g. 'vr' or 'ar'), prefer that formato
    $preferred = $filterTipo ?? null;
    $formato = $preferred ? $libro->formatos->firstWhere('formato', $preferred) : null;
    $formato = $formato ?? $libro->formatos->first();
    $tipo = $formato?->formato ?? 'fisico';
    $precio = $formato?->precio ?? 0;

    $current = $config[$tipo] ?? $config['fisico'];
@endphp


        <div class="book-card-container" style="--neon-color: {{ $current['color'] }};">
        <div class="book-card-content">

            <div class="book-cover">
                <img src="{{ asset('img/libro1.jpeg') }}" alt="{{ $libro->titulo }}">
                <span class="book-badge" style="background: {{ $current['color'] }}; color: #1a1a1a;">
                    {{ $current['badge'] }}
                </span>
            </div>

            <div class="book-body">

          <p class="book-category">{{ strtoupper($tipo) }}</p>
<h3 class="book-title">{{ $libro->titulo }}</h3>
<p class="book-author">
    {{ $libro->autor->nombre ?? 'Autor desconocido' }}
</p>

                @if($tipo === 'ar')
                <p class="book-category" style="color: {{ $current['color'] }};">{{ strtoupper($tipo) }}</p>
                <h3 class="book-title">{{ $libro->titulo }}</h3>
                <p class="book-author">Por {{ $libro->autor }}</p>
                
                @if($tipo === 'ar' || $tipo === 'vr')

                    <p class="experience-note">
                        <i class="fa {{ $current['icon'] }}" style="margin-right: 5px;"></i>
                        {{ $tipo === 'ar' ? 'Realidad Aumentada' : 'Realidad Virtual' }}
                    </p>
                @endif
            </div>

            <div class="book-footer">
                <div class="book-price" style="color: {{ $current['color'] }};">
                    ${{ number_format($precio, 2) }}
                </div>

                <a href="{{ route('libro.details', $libro->id) }}" class="book-btn"
                   style="border: 1px solid {{ $current['color'] }}; color: {{ $current['color'] }};">
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