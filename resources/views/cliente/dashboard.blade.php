<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Mi Dashboard · {{ config('app.name', 'INMERSIA') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/default.css') }}">
    <link rel="stylesheet" href="{{ asset('css/client.css') }}">
<script src="{{ asset('js/client.js') }}"></script>
</head>
<body>
    <!-- Navbar -->
    <div class="navbar">
        <a href="{{ route('welcome') }}" class="navbar-logo">INMER<span>SIA</span></a>
        <div class="navbar-right">
            <div class="user-info">
                <div class="user-avatar">
                    {{ strtoupper(substr(session('usuario_nombre'), 0, 1)) }}
                </div>
                <span>{{ session('usuario_nombre') }}</span>
            </div>
            <form action="{{ route('logout') }}" method="POST" style="margin: 0;">
                @csrf
                <button type="submit" class="logout-btn">Cerrar Sesión</button>
            </form>
        </div>
    </div>

    <div class="dashboard-container">
        <!-- Welcome Card -->
        <div class="welcome-card">
            <h1>¡Bienvenido a INMERSIA!</h1>
            <div class="action-buttons">
                <a href="{{ route('cliente.storebook') }}" class="btn-action btn-primary-action">
                    <i class="fa fa-store"></i>
                    Ver Tienda Completa
                </a>
                <a href="{{ route('cliente.libros.tipo', 'ar') }}" class="btn-action btn-secondary-action">
                    <i class="fa fa-camera"></i>
                    Libros AR
                </a>
                <a href="{{ route('cliente.libros.tipo', 'vr') }}" class="btn-action btn-secondary-action">
                    <i class="fa fa-vr-cardboard"></i>
                    Libros VR
                </a>
            </div>
        </div>

        <!-- Mis Libros Section -->
        <div class="my-books-section">
            <div class="section-header">
                <h2 class="section-title">
                    <i class="fa fa-library"></i> Mis Libros
                </h2>
                <div class="filter-buttons">
                    <button class="filter-btn active" data-filter="todos">
                        <i class="fa fa-list"></i> Todos
                    </button>
                    <button class="filter-btn" data-filter="fisico">
                        <i class="fa fa-book"></i> Físico
                    </button>
                    <button class="filter-btn" data-filter="ar">
                        <i class="fa fa-camera"></i> AR
                    </button>
                    <button class="filter-btn" data-filter="vr">
                        <i class="fa fa-vr-cardboard"></i> VR
                    </button>
                </div>
            </div>

            @if($libros->count() > 0)
                <div class="books-grid" id="booksGrid">
                    @foreach($libros as $libro)
                        @php
                            $formato = $libro->formatos->first();
                            $tipo = $formato?->formato ?? 'fisico';
                            $precio = $formato?->precio ?? 0;

                            $config = [
                                'fisico' => ['color' => '#c4a484', 'badge' => 'Físico'],
                                'ar' => ['color' => '#12a090', 'badge' => 'AR'],
                                'vr' => ['color' => '#6c3bd1', 'badge' => 'VR'],
                            ];
                            $current = $config[$tipo] ?? $config['fisico'];
                        @endphp

                        <div class="book-card" data-type="{{ $tipo }}">
                            <div class="book-cover">
                                <img src="{{ asset('img/libro1.jpeg') }}" alt="{{ $libro->titulo }}">
                                <span class="book-badge" style="background: {{ $current['color'] }}">
                                    {{ $current['badge'] }}
                                </span>
                            </div>
                            <div class="book-body">
                                <h3 class="book-title">{{ $libro->titulo }}</h3>
                                <p class="book-author">{{ $libro->autor }}</p>
                            </div>
                            <div class="book-footer">
                                <span class="book-price">${{ number_format($precio, 2) }}</span>
                                <button class="btn-action btn-buy" onclick="openBuyModal({{ $libro->id }}, '{{ $libro->titulo }}', {{ $precio }})">
                                    Adquirir
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="empty-state">
                    <i class="fa fa-book"></i>
                    <p>No hay libros disponibles en este momento</p>
                </div>
            @endif
        </div>

        <!-- Mis Favoritos Section -->
        <div class="my-books-section">
            <div class="section-header">
                <h2 class="section-title">
                    <i class="fa fa-heart"></i> Mis Favoritos
                </h2>
            </div>

            @if($favoritos->count() > 0)
                <div class="books-grid" id="favoritosGrid">
                    @foreach($favoritos as $libro)
                        @php
                            $formato = $libro->formatos->first();
                            $tipo = $formato?->formato ?? 'fisico';
                            $precio = $formato?->precio ?? 0;

                            $config = [
                                'fisico' => ['color' => '#c4a484', 'badge' => 'Físico'],
                                'ar' => ['color' => '#12a090', 'badge' => 'AR'],
                                'vr' => ['color' => '#6c3bd1', 'badge' => 'VR'],
                            ];
                            $current = $config[$tipo] ?? $config['fisico'];
                        @endphp

                        <div class="book-card" data-libro-id="{{ $libro->id }}">
                            <div class="book-cover">
                                <img src="{{ asset('img/libro1.jpeg') }}" alt="{{ $libro->titulo }}">
                                <span class="book-badge" style="background: {{ $current['color'] }}">
                                    {{ $current['badge'] }}
                                </span>
                                <button class="btn-remove-favorito" onclick="eliminarFavorito({{ $libro->id }})">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </div>
                            <div class="book-body">
                                <h3 class="book-title">{{ $libro->titulo }}</h3>
                                <p class="book-author">{{ $libro->autor }}</p>
                            </div>
                            <div class="book-footer">
                                <span class="book-price">${{ number_format($precio, 2) }}</span>
                                <a href="{{ route('cliente.libro.details', $libro->id) }}" class="book-btn">Ver Detalles</a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="empty-state">
                    <i class="fa fa-heart-o"></i>
                    <p>Aún no tienes libros en favoritos</p>
                    <a href="{{ route('cliente.storebook') }}" class="btn-primary-action">Explora la tienda</a>
                </div>
            @endif
        </div>

        <!-- Libros Destacados -->
        <h2 class="section-title">
            <i class="fa fa-star"></i> Libros Destacados
        </h2>

        @if($libros->count() > 0)
            <div class="books-grid">
                @foreach($libros->take(6) as $libro)
                    @php
                        $formato = $libro->formatos->first();
                        $tipo = $formato?->formato ?? 'fisico';
                        $precio = $formato?->precio ?? 0;

                        $config = [
                            'fisico' => ['color' => '#c4a484', 'badge' => 'Físico'],
                            'ar' => ['color' => '#12a090', 'badge' => 'AR'],
                            'vr' => ['color' => '#6c3bd1', 'badge' => 'VR'],
                        ];
                        $current = $config[$tipo] ?? $config['fisico'];
                    @endphp

                    <div class="book-card">
                        <div class="book-cover">
                            <img src="{{ asset('img/libro1.jpeg') }}" alt="{{ $libro->titulo }}">
                            <span class="book-badge" style="background: {{ $current['color'] }}">
                                {{ $current['badge'] }}
                            </span>
                        </div>
                        <div class="book-body">
                            <h3 class="book-title">{{ $libro->titulo }}</h3>
                            <p class="book-author">{{ $libro->autor }}</p>
                        </div>
                        <div class="book-footer">
                            <span class="book-price">${{ number_format($precio, 2) }}</span>
                            <a href="{{ route('cliente.libro.details', $libro->id) }}" class="book-btn">Explorar</a>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="empty-state">
                <i class="fa fa-book"></i>
                <p>No hay libros disponibles en este momento</p>
            </div>
        @endif
    </div>

   
    <!-- Footer con Pedidos -->
    <div class="pedidos-footer">
        <button class="pedidos-btn" onclick="openPedidosModal()">
            <i class="fa fa-inbox"></i> Mis Pedidos
        </button>
    </div>



</body>
</html>