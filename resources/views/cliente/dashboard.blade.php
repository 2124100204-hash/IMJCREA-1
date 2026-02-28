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
            <p>Explora nuestro catálogo de libros con experiencias inmersivas en AR y VR</p>
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
                                <button class="book-btn buy-btn" onclick="openBuyModal({{ $libro->id }}, '{{ $libro->titulo }}', {{ $precio }})">
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

    <!-- Modal Compra -->
    <div id="buyModal" class="modal">
        <div class="modal-content">
            <span class="modal-close" onclick="closeBuyModal()">&times;</span>
            <h2>Adquirir Libro</h2>
            <div class="modal-body">
                <p><strong id="modalLibroTitulo"></strong></p>
                <p>Precio: $<span id="modalPrecio"></span></p>
                
                <div class="form-group">
                    <label>Selecciona el formato:</label>
                    <div id="formatosContainer"></div>
                </div>

                <div class="form-group">
                    <label>Cantidad:</label>
                    <input type="number" id="cantidadInput" min="1" value="1" class="input-field">
                </div>

                <div class="form-group">
                    <label>Información de entrega:</label>
                    <textarea id="direccionInput" placeholder="Ingresa tu dirección de entrega..." class="textarea-field"></textarea>
                </div>

                <div class="modal-total">
                    <strong>Total: $<span id="totalPrecio">0.00</span></strong>
                </div>

                <button class="btn-comprar" onclick="confirmarCompra()">Confirmar Compra</button>
            </div>
        </div>
    </div>

    <!-- Footer con Pedidos -->
    <div class="pedidos-footer">
        <button class="pedidos-btn" onclick="openPedidosModal()">
            <i class="fa fa-inbox"></i> Mis Pedidos
        </button>
    </div>

    <!-- Modal Pedidos -->
    <div id="pedidosModal" class="modal">
        <div class="modal-content modal-large">
            <span class="modal-close" onclick="closePedidosModal()">&times;</span>
            <h2><i class="fa fa-inbox"></i> Mis Pedidos</h2>
            
            <div class="pedidos-container">
                <div class="pedido-card">
                    <div class="pedido-header">
                        <span class="pedido-id">#PED001</span>
                        <span class="pedido-status status-pending">Pendiente</span>
                    </div>
                    <div class="pedido-content">
                        <p><strong>Libro:</strong> El Sistema Solar Vivo</p>
                        <p><strong>Formato:</strong> Físico</p>
                        <p><strong>Cantidad:</strong> 1</p>
                        <p><strong>Fecha:</strong> 2026-02-25</p>
                        <p><strong>Total:</strong> $19.99</p>
                    </div>
                </div>

                <div class="pedido-card">
                    <div class="pedido-header">
                        <span class="pedido-id">#PED002</span>
                        <span class="pedido-status status-delivered">Entregado</span>
                    </div>
                    <div class="pedido-content">
                        <p><strong>Libro:</strong> Dinosaurios en tu Sala</p>
                        <p><strong>Formato:</strong> AR</p>
                        <p><strong>Cantidad:</strong> 2</p>
                        <p><strong>Fecha:</strong> 2026-02-20</p>
                        <p><strong>Total:</strong> $69.98</p>
                    </div>
                </div>

                <div class="empty-pedidos">
                    <p>Aún no tienes pedidos</p>
                </div>
            </div>
        </div>
    </div>

    <script>
        let libroActual = {};

        // Filtrar libros por tipo
        document.querySelectorAll('.filter-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
                this.classList.add('active');
                
                const filtro = this.getAttribute('data-filter');
                const cards = document.querySelectorAll('#booksGrid .book-card');
                
                cards.forEach(card => {
                    if (filtro === 'todos' || card.getAttribute('data-type') === filtro) {
                        card.style.display = 'block';
                    } else {
                        card.style.display = 'none';
                    }
                });
            });
        });

        // Abrir modal de compra
        function openBuyModal(libroId, titulo, precio) {
            libroActual = { id: libroId, titulo, precio };
            document.getElementById('modalLibroTitulo').textContent = titulo;
            document.getElementById('modalPrecio').textContent = precio.toFixed(2);
            document.getElementById('cantidadInput').value = 1;
            document.getElementById('direccionInput').value = '';
            
            // Generar botones de formatos
            const formatos = ['Físico', 'AR', 'VR'];
            const container = document.getElementById('formatosContainer');
            container.innerHTML = '';
            
            formatos.forEach(formato => {
                const btn = document.createElement('button');
                btn.className = 'formato-btn';
                btn.textContent = formato;
                btn.onclick = () => {
                    document.querySelectorAll('.formato-btn').forEach(b => b.classList.remove('selected'));
                    btn.classList.add('selected');
                    libroActual.formato = formato;
                    actualizarTotal();
                };
                container.appendChild(btn);
            });
            
            // Seleccionar primera opción por defecto
            if (container.firstChild) {
                container.firstChild.click();
            }
            
            document.getElementById('buyModal').style.display = 'flex';
        }

        function closeBuyModal() {
            document.getElementById('buyModal').style.display = 'none';
        }

        // Actualizar total con la cantidad
        document.getElementById('cantidadInput').addEventListener('change', actualizarTotal);

        function actualizarTotal() {
            const cantidad = parseInt(document.getElementById('cantidadInput').value) || 1;
            const total = libroActual.precio * cantidad;
            document.getElementById('totalPrecio').textContent = total.toFixed(2);
        }

        function confirmarCompra() {
            const cantidad = document.getElementById('cantidadInput').value;
            const direccion = document.getElementById('direccionInput').value;
            
            if (!libroActual.formato) {
                alert('Por favor selecciona un formato');
                return;
            }
            
            if (!direccion.trim()) {
                alert('Por favor ingresa una dirección de entrega');
                return;
            }
            
            alert(`✓ Compra confirmada!\nLibro: ${libroActual.titulo}\nFormato: ${libroActual.formato}\nCantidad: ${cantidad}\nTotal: $${(libroActual.precio * cantidad).toFixed(2)}`);
            closeBuyModal();
        }

        // Modal Pedidos
        function openPedidosModal() {
            document.getElementById('pedidosModal').style.display = 'flex';
        }

        function closePedidosModal() {
            document.getElementById('pedidosModal').style.display = 'none';
        }

        // Eliminar libro de favoritos
        function eliminarFavorito(libroId) {
            if (confirm('¿Deseas eliminar este libro de favoritos?')) {
                fetch('{{ route("favorito.eliminar") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ libro_id: libroId })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Eliminar la tarjeta de la interfaz
                        const card = document.querySelector(`[data-libro-id="${libroId}"]`);
                        if (card) {
                            card.style.transition = 'opacity 0.3s ease';
                            card.style.opacity = '0';
                            setTimeout(() => {
                                card.remove();
                                // Si no hay más favoritos, recargar la página
                                const grid = document.getElementById('favoritosGrid');
                                if (grid && grid.children.length === 0) {
                                    location.reload();
                                }
                            }, 300);
                        }
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error al eliminar de favoritos');
                });
            }
        }

        // Cerrar modales al hacer click fuera
        window.onclick = function(event) {
            const buyModal = document.getElementById('buyModal');
            const pedidosModal = document.getElementById('pedidosModal');
            
            if (event.target === buyModal) {
                buyModal.style.display = 'none';
            }
            if (event.target === pedidosModal) {
                pedidosModal.style.display = 'none';
            }
        };
    </script>
