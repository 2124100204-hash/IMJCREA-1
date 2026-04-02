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
    <nav class="navbar">
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
    </nav>

    <div class="dashboard-container">
        <header class="welcome-card">
            <h1>¡Bienvenido a INMERSIA!</h1>
            <div class="action-buttons">
                <a href="{{ route('cliente.storebook') }}" class="btn-action btn-primary-action">
                    <i class="fa fa-store"></i> Ver Tienda Completa
                </a>
                <button class="devoluciones-btn btn-action btn-secondary-action" onclick="openDevolucionesModal()">
                    <i class="fa fa-undo"></i> Devoluciones
                </button>
<button class="pedidos-btn btn-action btn-secondary-action" onclick="openPedidosModal()">
                    <i class="fa fa-box-open"></i> Mis Pedidos
                </button>
            </div>
        </header>

        <section class="my-books-section">
            <div class="section-header">
                <h2 class="section-title"><i class="fa fa-book-open"></i> Mis Libros</h2>
                <div class="filter-buttons">
                    <button class="filter-btn active" data-filter="todos"><i class="fa fa-list"></i> Todos</button>
                    <button class="filter-btn" data-filter="fisico"><i class="fa fa-book"></i> Físico</button>
                    <button class="filter-btn" data-filter="ar"><i class="fa fa-camera"></i> AR</button>
                    <button class="filter-btn" data-filter="vr"><i class="fa fa-vr-cardboard"></i> VR</button>
                </div>
            </div>

            @if($detallesEntregados->count() > 0)
                <div class="books-grid" id="booksGrid">
                    @foreach($detallesEntregados as $detalle)
                        @php
                            $libro = $detalle->libro;
                            $tipo = $detalle->formato;
                            $precio = $detalle->precio_unitario;
                            
                            $config = [
                                'fisico' => ['color' => '#c4a484', 'badge' => 'Físico'],
                                'ar'     => ['color' => '#12a090', 'badge' => 'AR'],
                                'vr'     => ['color' => '#6c3bd1', 'badge' => 'VR'],
                            ];
                            $current = $config[$tipo] ?? $config['fisico'];
                            
                            // Lógica de imagen: Si el libro tiene imagen úsala, si no, una por defecto robusta
                            $imagePath = $libro->imagen_url ? asset('storage/' . $libro->imagen_url) : asset('img/libro1.jpeg');
                            
                            $tiempoJugado = $tipo === "vr" ? rand(1, 50) . " horas" : null;
                            $detalleData = array_merge($detalle->toArray(), ["libro" => $libro->toArray(), "tiempo_jugado" => $tiempoJugado]);
                        @endphp

                        <div class="book-card" data-type="{{ $tipo }}" data-detalle='@json($detalleData)'>
                            <div class="book-cover">
                                <img src="{{ $imagePath }}" alt="{{ $libro->titulo }}" loading="lazy">
                                <span class="book-badge" style="background: {{ $current['color'] }}">
                                    {{ $current['badge'] }}
                                </span>
                            </div>
                            <div class="book-body">
                                <h3 class="book-title">{{ $libro->titulo }}</h3>
                                <p class="book-author">{{ $libro->autor?->nombre ?? 'Autor desconocido' }}</p>
                            </div>
                            <div class="book-footer">
                                <span class="book-price">${{ number_format($precio, 2) }}</span>
                                <button class="btn-action btn-buy" onclick="openLibroDetallesModal(this)">
                                    Ver detalles
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="empty-state">
                    <i class="fa fa-book"></i>
                    <p>No tienes libros disponibles en tu colección.</p>
                </div>
            @endif
        </section>

        @if($favoritos->count() > 0)
        <section class="my-books-section">
            <h2 class="section-title"><i class="fa fa-heart"></i> Mis Favoritos</h2>
            <div class="books-grid">
                @foreach($favoritos as $fav)
                    <div class="book-card" data-libro-id="{{ $fav->id }}">
                        <div class="book-cover">
                            <img src="{{ $fav->imagen_url ? asset('storage/' . $fav->imagen_url) : asset('img/default-book.png') }}" alt="{{ $fav->titulo }}">
                            <button class="btn-remove-favorito" onclick="eliminarFavorito({{ $fav->id }})">
                                <i class="fa fa-trash"></i>
                            </button>
                        </div>
                        <div class="book-body">
                            <h3 class="book-title">{{ $fav->titulo }}</h3>
                            <p class="book-author">{{ $fav->autor }}</p>
                        </div>
                        <div class="book-footer">
                            <a href="{{ route('cliente.libro.details', $fav->id) }}" class="book-btn">Ver Detalles</a>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>
        @endif
    </div>

    <div class="pedidos-footer">
        <button class="pedidos-btn" onclick="openPedidosModal()">
            <i class="fa fa-box-open"></i> Mis Pedidos
        </button>
    </div>

    <!-- Modal de Pedidos -->
    <div id="pedidosModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closePedidosModal()">&times;</span>
            <h2>Mis Pedidos</h2>
            <div class="pedidos-container">
                @if($pedidos->isEmpty())
                    <div class="empty-pedidos">
                        <i class="fa fa-box-open"></i>
                        <p>Aún no tienes pedidos registrados.</p>
                    </div>
                @else
                    @foreach($pedidos as $pedido)
                        <div class="pedido-card" data-pedido='@json(array_merge($pedido->toArray(), ["tiempo" => $pedido->created_at->diffInDays(now()) . " días"]))'>
                            <div class="pedido-header">
                                <span class="pedido-id">Pedido #{{ $pedido->id }}</span>
                                <span class="pedido-estado estado-{{ $pedido->estado }}">{{ ucfirst($pedido->estado) }}</span>
                                <span class="pedido-fecha">{{ $pedido->created_at->format('d/m/Y') }}</span>
                                <button class="btn-ver-detalles" onclick="openPedidoDetallesModal(this)">Ver detalles</button>
                            </div>
                            <div class="pedido-detalles">
                                @foreach($pedido->detalles as $detalle)
                                    <div class="pedido-item">
                                        <div class="item-info">
                                            <span class="item-titulo">{{ $detalle->libro->titulo }}</span>
                                            <span class="item-formato">Formato: {{ ucfirst($detalle->formato) }}</span>
                                            <span class="item-cantidad">Cantidad: {{ $detalle->cantidad }}</span>
                                            <span class="item-precio">${{ number_format($detalle->precio_unitario, 2) }}</span>
                                            <span class="item-estado">Estado: {{ ucfirst($detalle->estado) }}</span>
                                        </div>
                                        @if($pedido->estado === 'entregado' && $detalle->estado !== 'devuelto')
                                            <button class="btn-devolver" onclick="openDevolucionModal({{ $detalle->id }}, '{{ $detalle->libro->titulo }}', {{ $detalle->cantidad }})">
                                                Devolver
                                            </button>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                            <div class="pedido-total">
                                <strong>Total: ${{ number_format($pedido->total, 2) }}</strong>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </div>

    <!-- Modal de Devolución -->
    <div id="devolucionModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeDevolucionModal()">&times;</span>
            <h2>Solicitar Devolución</h2>
            <form id="devolucionForm" onsubmit="submitDevolucion(event)">
                @csrf
                <input type="hidden" id="pedidoDetalleId" name="pedido_detalle_id">
                <div class="form-group">
                    <label for="libroTitulo">Producto:</label>
                    <input type="text" id="libroTitulo" readonly>
                </div>
                <div class="form-group">
                    <label for="cantidadDevuelta">Cantidad a devolver:</label>
                    <input type="number" id="cantidadDevuelta" name="cantidad_devuelta" min="1" required>
                </div>
                <div class="form-group">
                    <label for="razon">Razón de la devolución:</label>
                    <textarea id="razon" name="razon" rows="3" placeholder="Opcional"></textarea>
                </div>
                <button type="submit" class="btn-submit">Enviar Solicitud</button>
            </form>
        </div>
    </div>

    <!-- Modal de Devoluciones (Historial) -->
    <div id="devolucionesModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeDevolucionesModal()">&times;</span>
            <h2><i class="fa fa-undo"></i> Historial de Devoluciones</h2>
            <div class="devoluciones-container">
                @php
                    $devoluciones = \App\Models\Devolucion::whereHas('pedidoDetalle.pedido', function($q) {
                        $q->where('usuario_id', Auth::id());
                    })->with(['pedidoDetalle.pedido', 'pedidoDetalle.libro'])->latest()->get();
                @endphp

                @if($devoluciones->isEmpty())
                    <div class="empty-devoluciones">
                        <i class="fa fa-undo"></i>
                        <p>Aún no tienes solicitudes de devolución.</p>
                    </div>
                @else
                    @foreach($devoluciones as $devolucion)
                        <div class="devolucion-card">
                            <div class="devolucion-header">
                                <span class="devolucion-id">Devolución #{{ $devolucion->id }}</span>
                                <span class="devolucion-estado estado-{{ $devolucion->estado }}">
                                    {{ ucfirst($devolucion->estado) }}
                                </span>
                                <span class="devolucion-fecha">{{ $devolucion->created_at->format('d/m/Y') }}</span>
                            </div>
                            <div class="devolucion-detalles">
                                <div class="devolucion-item">
                                    <div class="item-info">
                                        <span class="item-titulo">{{ $devolucion->pedidoDetalle->libro->titulo }}</span>
                                        <span class="item-formato">Formato: {{ ucfirst($devolucion->pedidoDetalle->formato) }}</span>
                                        <span class="item-cantidad">Cantidad devuelta: {{ $devolucion->cantidad }}</span>
                                        @if($devolucion->razon)
                                            <span class="item-razon">Razón: {{ $devolucion->razon }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            @if($devolucion->estado === 'aprobada')
                                <div class="devolucion-total">
                                    <strong>Reembolso: ${{ number_format($devolucion->pedidoDetalle->precio_unitario * $devolucion->cantidad, 2) }}</strong>
                                </div>
                            @endif
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </div>

    <!-- Modal de Detalles de Pedido -->
    <div id="pedidoDetallesModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closePedidoDetallesModal()">&times;</span>
            <h2>Detalles del Pedido</h2>
            <div id="pedidoDetallesContent">
                <!-- Contenido se llena dinámicamente -->
            </div>
        </div>
    </div>

    <!-- Modal de Detalles de Libro -->
    <div id="libroDetallesModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeLibroDetallesModal()">&times;</span>
            <h2>Detalles del Libro</h2>
            <div id="libroDetallesContent">
                <!-- Contenido se llena dinámicamente -->
            </div>
        </div>
    </div>

</body>
</html>