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
            <a href="{{ route('contact') }}">Contacto</a>
            <a href="{{ route('login') }}" class="btn-nav-primary">Iniciar Sesión</a>
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
   $config = [
    'fisico' => [
        'color' => '#c4a484',
        'icon' => 'fa-book',
        'badge' => 'Físico',
        'bg' => 'linear-gradient(135deg,#fff8e1,#ffe0b2)',
        'btn' => 'Comprar'
    ],
    'ar' => [
        'color' => '#12a090',
        'icon' => 'fa-camera',
        'badge' => 'AR',
        'bg' => 'linear-gradient(135deg,#e8f5e9,#c8e6c9)',
        'btn' => 'Incluye AR'
    ],
    'vr' => [
        'color' => '#6c3bd1',
        'icon' => 'fa-vr-cardboard',
        'badge' => 'VR',
        'bg' => 'linear-gradient(135deg,#ede7f6,#d1c4e9)',
        'btn' => 'Experiencia VR'
    ],
];
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


        <div class="book-card">

            <div class="book-cover" style="background: {{ $current['bg'] }}">
                <img src="{{ asset('img/libro1.jpeg') }}">
                <span class="book-badge" style="background: {{ $current['color'] }}">
                    {{ $current['badge'] }}
                </span>
            </div>

            <div class="book-body">
          <p class="book-category">{{ strtoupper($tipo) }}</p>
<h3 class="book-title">{{ $libro->titulo }}</h3>
<p class="book-author">{{ $libro->autor }}</p>

                @if($tipo === 'ar')
                    <p class="experience-note">
                        Escanea el libro con nuestra app para contenido interactivo.
                    </p>
                @endif

                @if($tipo === 'vr')
                    <p class="experience-note">
                        Compatible con visor de realidad virtual.
                    </p>
                @endif
            </div>

            <div class="book-footer">
                <div class="book-price">
                  ${{ number_format($precio, 2) }}
                </div>

                <a href="{{ route('libro.details', $libro->id) }}" class="book-btn"
                        style="background: {{ $current['color'] }}; display: inline-flex; align-items: center; gap: 8px; text-decoration: none; color: white;">
                    <i class="fa {{ $current['icon'] }}"></i>
                    {{ $current['btn'] }}
                </a>
            </div>

        </div>

        @endforeach

    </div>
</section>

</body>
</html>