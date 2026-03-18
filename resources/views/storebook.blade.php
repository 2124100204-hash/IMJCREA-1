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
            <a href="{{ route('contact') }}">Contactos</a>
            <a href="{{ route('login') }}" class="btn-ghost">Iniciar Sesión</a>
        </div>
    @endauth

    <button class="navbar-toggle" id="navToggle" aria-label="Menú">
        <span></span><span></span><span></span>
    </button>
    <div class="search-container">
        <input type="text" placeholder="Buscar libros..." class="search-input" id="searchInput">
        <button class="search-btn"><i class="fa fa-search"></i></button>
    </div>
</nav>

{{-- ── Hero ── --}}
<div class="page-hero">
    <h1 class="page-title">Explora & <span>Descubre</span></h1>
    <p>Libros físicos con experiencias inmersivas opcionales.</p>
</div>

{{-- ── Books Section ── --}}
<section class="books-section">

    @php
        $config = [
            'fisico' => ['color' => '#e8820c', 'badge' => 'Físico',  'icon' => 'fa-book',        'label' => 'Libro Físico'],
            'ar'     => ['color' => '#0d7a6e', 'badge' => 'AR',      'icon' => 'fa-camera',      'label' => 'Realidad Aumentada'],
            'vr'     => ['color' => '#c94f6d', 'badge' => 'VR',      'icon' => 'fa-vr-cardboard','label' => 'Realidad Virtual'],
        ];

        // Conteo por tipo para las pills
        $countAll    = $libros->count();
        $countFisico = $libros->filter(fn($l) => $l->formatos->where('formato','fisico')->isNotEmpty())->count();
        $countAR     = $libros->filter(fn($l) => $l->formatos->where('formato','ar')->isNotEmpty())->count();
        $countVR     = $libros->filter(fn($l) => $l->formatos->where('formato','vr')->isNotEmpty())->count();

        $activeFilter = $filterTipo ?? 'all';
    @endphp


    <div class="filter-bar">
        {{-- Todos --}}
        @auth
            <a href="{{ route('cliente.storebook') }}"
               class="filter-btn all {{ $activeFilter === 'all' ? 'active' : '' }}">
                <i class="fa fa-border-all"></i>
                Todos
                <span class="filter-count">{{ $countAll }}</span>
            </a>
            <a href="{{ route('cliente.libros.tipo', 'fisico') }}"
               class="filter-btn fisico {{ $activeFilter === 'fisico' ? 'active' : '' }}">
                <i class="fa fa-book"></i>
                Físico
                <span class="filter-count">{{ $countFisico }}</span>
            </a>
            <a href="{{ route('cliente.libros.tipo', 'ar') }}"
               class="filter-btn ar {{ $activeFilter === 'ar' ? 'active' : '' }}">
                <i class="fa fa-camera"></i>
                Realidad Aumentada
                <span class="filter-count">{{ $countAR }}</span>
            </a>
            <a href="{{ route('cliente.libros.tipo', 'vr') }}"
               class="filter-btn vr {{ $activeFilter === 'vr' ? 'active' : '' }}">
                <i class="fa fa-vr-cardboard"></i>
                Realidad Virtual
                <span class="filter-count">{{ $countVR }}</span>
            </a>
        @else
            <a href="{{ route('storebook') }}"
               class="filter-btn all {{ $activeFilter === 'all' ? 'active' : '' }}">
                <i class="fa fa-border-all"></i>
                Todos
                <span class="filter-count">{{ $countAll }}</span>
            </a>
            <a href="{{ route('libros.tipo', 'fisico') }}"
               class="filter-btn fisico {{ $activeFilter === 'fisico' ? 'active' : '' }}">
                <i class="fa fa-book"></i>
                Físico
                <span class="filter-count">{{ $countFisico }}</span>
            </a>
            <a href="{{ route('libros.tipo', 'ar') }}"
               class="filter-btn ar {{ $activeFilter === 'ar' ? 'active' : '' }}">
                <i class="fa fa-camera"></i>
                Realidad Aumentada
                <span class="filter-count">{{ $countAR }}</span>
            </a>
            <a href="{{ route('libros.tipo', 'vr') }}"
               class="filter-btn vr {{ $activeFilter === 'vr' ? 'active' : '' }}">
                <i class="fa fa-vr-cardboard"></i>
                Realidad Virtual
                <span class="filter-count">{{ $countVR }}</span>
            </a>
        @endauth
    </div>

    {{-- ── Grid ── --}}
    <div class="books-grid">

        @forelse($libros as $libro)
            @php
                $formatoPrincipal = $libro->formatos->first();
                $tipoPrincipal    = $formatoPrincipal?->formato ?? 'fisico';
                $colorPrincipal   = $config[$tipoPrincipal]['color'] ?? $config['fisico']['color'];
                $precioMin        = $libro->formatos->min('precio') ?? 0;
            @endphp

            <div class="book-card-container">
                <div class="book-card-content" style="--type-color: {{ $colorPrincipal }};">

                    {{-- Portada --}}
                    <div class="book-cover">
                        <img src="{{ asset('img/libro1.jpeg') }}" alt="{{ $libro->titulo }}">

                        {{-- Badges apilados --}}
                        <div style="position:absolute; top:10px; right:10px; display:flex; flex-direction:column; gap:5px;">
                            @foreach($libro->formatos as $fmt)
                                @php $c = $config[$fmt->formato] ?? $config['fisico']; @endphp
                                <span class="book-badge" style="background:{{ $c['color'] }}; color:#fff;">
                                    {{ $c['badge'] }}
                                </span>
                            @endforeach
                        </div>
                    </div>

                    {{-- Cuerpo --}}
                    <div class="book-body">
                        <h3 class="book-title">{{ $libro->titulo }}</h3>
                        <p class="book-author">{{ $libro->autor->nombre ?? 'Autor desconocido' }}</p>

                        {{-- Chips de formatos con precio individual --}}
                        <div class="formato-chips">
                            @foreach($libro->formatos as $fmt)
                                @php $c = $config[$fmt->formato] ?? $config['fisico']; @endphp
                                <span class="formato-chip" style="
                                    background: {{ $c['color'] }}18;
                                    color: {{ $c['color'] }};
                                    border: 1px solid {{ $c['color'] }}40;">
                                    <i class="fa {{ $c['icon'] }}" style="font-size:10px;"></i>
                                    {{ $c['label'] }} — ${{ number_format($fmt->precio, 2) }}
                                </span>
                            @endforeach
                        </div>
                    </div>

                    {{-- Footer --}}
                    <div class="book-footer">
                        <div class="book-price" style="color: {{ $colorPrincipal }};">
                            Desde ${{ number_format($precioMin, 2) }}
                        </div>
                        <a href="{{ route('libro.details', $libro->id) }}"
                           class="book-btn"
                           style="border-color: {{ $colorPrincipal }}; color: {{ $colorPrincipal }};">
                            <i class="fa fa-book" style="font-size:11px;"></i>
                            Detalles
                        </a>
                    </div>

                </div>
            </div>

        @empty
            <div class="empty-state">
                <i class="fa fa-book-open"></i>
                <p>No hay libros disponibles en esta categoría.</p>
            </div>
        @endforelse

    </div>
</section>


</body>
</html>