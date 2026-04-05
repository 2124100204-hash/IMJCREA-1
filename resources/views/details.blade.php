<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $libro->titulo }} · {{ config('app.name', 'INMERSIA') }}</title>

    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/default.css') }}">
    <link rel="stylesheet" href="{{ asset('css/details.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.min.css">
   <script src="{{ asset('js/app.js') }}" defer></script>
   <script src="{{ asset('js/details.js') }}" defer></script>
   <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.all.min.js"></script>
    
</head>
<body>
    <nav class="navbar">
        <a href="{{ route('welcome') }}" class="navbar-logo">INMER<span>SIA</span></a>
        @auth
            <div class="navbar-links" id="navLinks">
                <a href="{{ route('cliente.dashboard') }}"><i class="fa fa-home"></i> Mi Dashboard</a>
                <a href="{{ route('cliente.storebook') }}"><i class="fa fa-store"></i> Tienda</a>
                <a href="{{ route('cliente.libros.tipo', 'ar') }}"><i class="fa fa-camera"></i> AR</a>
                <a href="{{ route('cliente.libros.tipo', 'vr') }}"><i class="fa fa-vr-cardboard"></i> VR</a>
            </div>
            <div class="navbar-right">
                <div class="cart-icon" onclick="mostrarCarrito()" style="position: relative; cursor: pointer; margin-right: 20px;">
                    <i class="fa fa-shopping-cart" style="font-size: 24px; color: #333;"></i>
                    <span id="cart-count" style="position: absolute; top: -10px; right: -10px; background: #e74c3c; color: white; border-radius: 50%; padding: 2px 6px; font-size: 12px; display: none;">0</span>
                </div>
                <div class="user-info">
                    <div class="user-avatar">{{ strtoupper(substr(auth()->user()->nombre ?? 'U', 0, 1)) }}</div>
                    <span>{{ auth()->user()->nombre ?? 'Usuario' }}</span>
                </div>
                <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                    @csrf
                    <button type="submit" class="logout-btn">Salir</button>
                </form>
            </div>
        @else
            <div class="navbar-links" id="navLinks">
                <a href="{{ route('welcome') }}"><i class="fa fa-home"></i> Inicio</a>
                <a href="{{ route('storebook') }}"><i class="fa fa-store"></i> Tienda</a>
                <a href="{{ route('libros.tipo', 'vr') }}">Realidad Virtual</a>
                <a href="{{ route('libros.tipo', 'ar') }}">Realidad Aumentada</a>
                <a href="{{ route('contact') }}">Contacto</a>
                <a href="{{ route('login') }}" class="btn-nav-primary">Iniciar Sesión</a>
            </div>
        @endauth
    </nav>

    <div class="details-container">
        @if(auth()->check())
            <a href="{{ route('cliente.storebook') }}" class="back-link">
                <i class="fa fa-arrow-left"></i> Volver a la tienda
            </a>
        @else
            <a href="{{ route('storebook') }}" class="back-link">
                <i class="fa fa-arrow-left"></i> Volver a la tienda
            </a>
        @endif

        <div class="book-detail">
            <!-- Imagen del libro -->
            <div>
                <div class="book-image">
                    <img src="{{ asset('img/libro1.jpeg') }}" alt="{{ $libro->titulo }}">
                </div>
            </div>

            <!-- Información del libro -->
            <div class="book-info">
                <span class="book-category">{{ strtoupper($libro->categoria?->nombre ?? 'General') }}</span>
                <h1>{{ $libro->titulo }}</h1>
                <p class="book-author">Por {{ $libro->autor?->nombre ?? 'Autor desconocido' }}</p>

                <p class="book-description">
                    {{ $libro->descripcion ?? 'Libro sin descripción disponible.' }}
                </p>

                <!-- Formatos disponibles -->
                @if($libro->formatos->count() > 0)
                    <div class="formatos-section">
                        <p class="formatos-title">Formatos Disponibles</p>
                        <div class="formatos-grid">
                            @foreach($libro->formatos as $formato)
                                @php
                                    $config = [
                                        'fisico' => [
                                            'name' => 'Físico',
                                            'icon' => 'fa-book',
                                            'color' => 'var(--ink-light)'
                                        ],
                                        'ar' => [
                                            'name' => 'Realidad Aumentada',
                                            'icon' => 'fa-camera',
                                            'color' => 'var(--teal)'
                                        ],
                                        'vr' => [
                                            'name' => 'Realidad Virtual',
                                            'icon' => 'fa-vr-cardboard',
                                            'color' => '#6c3bd1'
                                        ],
                                    ];
                                    $tipo = $formato->formato;
                                    $info = $config[$tipo] ?? $config['fisico'];
                                @endphp
                                <div class="formato-card" data-formato="{{ $tipo }}" data-precio="{{ $formato->precio }}" data-stock="{{ $formato->stock }}">
                                    <div class="formato-icon">
                                        <i class="fa {{ $info['icon'] }}" style="color: {{ $info['color'] }}"></i>
                                    </div>
                                    <div class="formato-name">{{ $info['name'] }}</div>
                                    <div class="formato-price">${{ number_format($formato->precio, 2) }}</div>
                                    <div class="formato-stock">Stock: {{ $formato->stock ?? '∞' }}</div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Cantidad -->
                    <div class="cantidad-section">
                        <label class="cantidad-label">Cantidad:</label>
                        <input type="number" id="cantidad" class="cantidad-input" min="1" value="1">
                    </div>

                    <!-- Botones de acción -->
                    <div class="action-buttons">
                        @auth
                            <!-- Si está autenticado, botones normales -->
                            <button class="btn-primary" onclick="agregarAlCarrito()">
                                <i class="fa fa-shopping-cart"></i> Agregar al Carrito
                            </button>

                            @if(!empty($ownedDetalle))
                                <form method="POST" action="{{ route('pedido.devolver') }}" style="display:inline; margin-left:12px;">
                                    @csrf
                                    <input type="hidden" name="pedido_detalle_id" value="{{ $ownedDetalle->id }}">
                                    <input type="hidden" name="cantidad_devuelta" value="1">
                                    <button type="submit" class="btn-rollback" style="background:#e74c3c; color:white; border:none; border-radius:8px; padding:10px 16px;">Reembolsar</button>
                                </form>
                            @endif

                            <button class="btn-secondary" onclick="agregarAFavoritos()" style="margin-left:12px;">
                                <i class="fa fa-heart"></i> Agregar a Favoritos
                            </button>
                        @else
                            <!-- Si NO está autenticado, botones deshabilitados -->
                            <button class="btn-primary btn-disabled" onclick="mostrarLoginModal()">
                                <i class="fa fa-shopping-cart"></i> Agregar al Carrito
                            </button>
                            <button class="btn-secondary btn-disabled" onclick="mostrarLoginModal()">
                                <i class="fa fa-heart"></i> Agregar a Favoritos
                            </button>
                        @endauth
                    </div>
                @else
                    <div style="background: var(--cream); padding: 20px; border-radius: 8px; text-align: center;">
                        <p style="color: var(--ink-muted);">No hay formatos disponibles para este libro.</p>
                    </div>
                @endif

                <!-- Especificaciones -->
                @if($libro->nivel_edad || $libro->duracion)
                    <div class="specs-section">
                        <p class="specs-title">Especificaciones</p>
                        <div class="specs-grid">
                            @if($libro->nivel_edad)
                                <div class="spec-item">
                                    <div class="spec-label">Edad Recomendada</div>
                                    <div class="spec-value">{{ $libro->nivel_edad }}+</div>
                                </div>
                            @endif
                            @if($libro->duracion)
                                <div class="spec-item">
                                    <div class="spec-label">Duración</div>
                                    <div class="spec-value">{{ $libro->duracion }} min</div>
                                </div>
                            @endif
                            <div class="spec-item">
                                <div class="spec-label">Formatos</div>
                                <div class="spec-value">{{ $libro->formatos->count() }}</div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

   

    <!-- Global JS variables for details page -->
    <script>
        window.isAuthenticated = {{ auth()->check() ? 'true' : 'false' }};
        window.libroId = {{ $libro->id }};
        window.libroTitulo = '{{ addslashes($libro->titulo) }}';
        window.libroAutor = '{{ addslashes($libro->autor?->nombre ?? 'Autor desconocido') }}';
        window.libroImagen = '{{ $libro->imagen_url ? asset('storage/' . $libro->imagen_url) : asset('img/libro1.jpeg') }}';
        window.routes = {
            favoritosObtener: '{{ route("favoritos.obtener") }}',
            favoritoAgregar: '{{ route("favorito.agregar") }}',
            favoritoEliminar: '{{ route("favorito.eliminar") }}'
        };
    </script>

    <!-- Modal del Carrito -->
    <div class="modal-auth" id="carritoModal" style="display: none;">
        <div class="modal-auth-content" style="max-width: 800px; width: 90%;">
            <button class="modal-auth-close" onclick="cerrarCarritoModal()">&times;</button>
            <div class="modal-auth-icon">
                <i class="fa fa-shopping-cart"></i>
            </div>
            <h2 class="modal-auth-title">Mi Carrito de Compras</h2>
            
            <div id="carrito-items" style="max-height: 400px; overflow-y: auto; margin: 20px 0;">
                <!-- Items del carrito se cargarán aquí -->
            </div>
            
            <div id="carrito-total" style="text-align: center; margin: 20px 0; font-size: 18px; font-weight: bold;">
                Total: $0.00
            </div>
            
            <div class="modal-auth-actions">
                <button onclick="cerrarCarritoModal()" class="modal-auth-btn modal-auth-btn-secondary">
                    <i class="fa fa-arrow-left"></i> Continuar Comprando
                </button>
                <button onclick="procederAlPago()" class="modal-auth-btn modal-auth-btn-primary" id="btn-checkout" style="display: none;">
                    <i class="fa fa-credit-card"></i> Proceder al Pago
                </button>
            </div>
        </div>
    </div>

    <!-- Modal de Métodos de Pago -->
    <div class="modal-auth" id="pagoModal" style="display: none;">
        <div class="modal-auth-content" style="max-width: 600px; width: 90%;">
            <button class="modal-auth-close" onclick="cerrarPagoModal()">&times;</button>
            <div class="modal-auth-icon">
                <i class="fa fa-credit-card"></i>
            </div>
            <h2 class="modal-auth-title">Método de Pago</h2>
            
            <div id="pago-resumen" style="margin: 12px 0; padding: 12px; background: #f8f9fa; border-radius: 8px; font-size: 13px;">
                <!-- Resumen del pedido -->
            </div>
            
            <div class="metodos-pago" style="margin: 12px 0;">
                <div class="metodo-pago-option" onclick="seleccionarMetodo(event, 'tarjeta')" style="cursor: pointer; padding: 10px 12px; border: 2px solid #ddd; border-radius: 8px; margin: 6px 0; display: flex; align-items: center;">
                    <i class="fa fa-credit-card" style="font-size: 20px; margin-right: 12px; color: #007bff;"></i>
                    <div>
                        <strong style="font-size: 13px;">Tarjeta de Crédito/Débito</strong>
                        <p style="margin: 2px 0 0 0; color: #666; font-size: 12px;">Visa, Mastercard</p>
                    </div>
                </div>
                
                <div class="metodo-pago-option" onclick="seleccionarMetodo(event, 'paypal')" style="cursor: pointer; padding: 10px 12px; border: 2px solid #ddd; border-radius: 8px; margin: 6px 0; display: flex; align-items: center;">
                    <i class="fa fa-paypal" style="font-size: 20px; margin-right: 12px; color: #0070ba;"></i>
                    <div>
                        <strong style="font-size: 13px;">PayPal</strong>
                        <p style="margin: 2px 0 0 0; color: #666; font-size: 12px;">Pago seguro</p>
                    </div>
                </div>
                
                <div class="metodo-pago-option" onclick="seleccionarMetodo(event, 'efectivo')" style="cursor: pointer; padding: 10px 12px; border: 2px solid #ddd; border-radius: 8px; margin: 6px 0; display: flex; align-items: center;">
                    <i class="fa fa-money-bill-wave" style="font-size: 20px; margin-right: 12px; color: #28a745;"></i>
                    <div>
                        <strong style="font-size: 13px;">Efectivo</strong>
                        <p style="margin: 2px 0 0 0; color: #666; font-size: 12px;">Pago en tienda</p>
                    </div>
                </div>
            </div>

            <div id="paymentDetailsContainer" style="display:none; margin: 8px 0; padding: 12px; background: linear-gradient(180deg, #ffffff 0%, #f7f9fc 100%); border: 1px solid #d1dce8; border-radius: 12px; box-shadow: 0 8px 16px rgba(15, 23, 42, 0.04); min-height: 0; max-height: 140px; overflow-y: auto;">
            </div>
            <div style="margin-bottom: 10px; color: #0d6efd; font-weight: 600; display: flex; align-items: center; gap: 6px; font-size: 12px;">
                <i class="fa fa-shield-alt"></i>
                Tus datos se envían de forma segura.
            </div>
            <div class="modal-auth-actions">
                <button onclick="volverAlCarrito()" class="modal-auth-btn modal-auth-btn-secondary">
                    <i class="fa fa-arrow-left"></i> Volver al Carrito
                </button>
                <button onclick="confirmarCompra()" class="modal-auth-btn modal-auth-btn-primary" id="btn-confirmar" disabled>
                    <i class="fa fa-check"></i> Confirmar Compra
                </button>
            </div>
        </div>
    </div>

    <!-- Modal de Autenticación -->
    <div class="modal-auth" id="loginModal">
        <div class="modal-auth-content">
            <button class="modal-auth-close" onclick="cerrarLoginModal()">&times;</button>
            <div class="modal-auth-icon">
                <i class="fa fa-lock"></i>
            </div>
            <h2 class="modal-auth-title">Acceso Requerido</h2>
            <p class="modal-auth-text">
                Para comprar libros o guardarlos en favoritos, necesitas iniciar sesión o crear una cuenta en INMERSIA.
            </p>
            <div class="modal-auth-actions">
                <a href="{{ route('login') }}" class="modal-auth-btn modal-auth-btn-primary">
                    <i class="fa fa-sign-in"></i> Iniciar Sesión
                </a>
                <a href="{{ route('login') }}" class="modal-auth-btn modal-auth-btn-secondary">
                    <i class="fa fa-user-plus"></i> Crear Cuenta
                </a>
            </div>
        </div>
    </div>

</body>
</html>
