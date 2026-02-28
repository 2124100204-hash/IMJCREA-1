<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard Cliente - {{ config('app.name', 'IMJCREA') }}</title>

    <link rel="stylesheet" href="{{ asset('css/client.css') }}">
    <link rel="stylesheet" href="{{ asset('css/default.css') }}">
    <script src="{{ asset('js/client.js') }}" defer></script>


</head>

<body>

<nav class="navbar">
    <a href="{{ route('welcome') }}" class="navbar-logo">INMER<span>SIA</span></a>

    <div class="navbar-links">
        <a href="{{ route('welcome') }}">Inicio</a>
        <a href="{{ route('storebook') }}">Tienda</a>
        <a href="{{ route('contact') }}">Contacto</a>
    </div>
</nav>

<div class="dashboard-container">
    <h2>Bienvenido, {{ session('usuario')->codigo }}</h2>

    <button class="action-btn accept-btn" onclick="openModal('perfilModal')">Ver Perfil</button>
    <button class="action-btn accept-btn" onclick="openModal('pedidosModal')">Ver Pedidos</button>
    <button class="action-btn accept-btn" onclick="openModal('nuevoPedidoModal')">Sugerencias</button>
    <button class="action-btn cancel-btn" onclick="openModal('logoutModal')">Salir</button>
</div>

<div class="hero">
    <h1>Bienvenido, {{ session('usuario')->codigo }}</h1>
    <p>Descubre nuevas historias y recomendaciones personalizadas.</p>
</div>


<div class="section">
    <h2>📌 Recomendados para ti</h2>
    <div class="carousel">
        @foreach($libros as $libro)
            <div class="book-card">
                <img src="{{ asset('storage/'.$libro->imagen) }}" alt="{{ $libro->titulo }}">
                <h4>{{ $libro->titulo }}</h4>
                <p>${{ $libro->precio }}</p>
            </div>
        @endforeach
    </div>
</div>


<div class="section">
    <h2>🔥 Tendencias</h2>
    <div class="carousel">
        @foreach($libros->take(5) as $libro)
            <div class="book-card small">
                <img src="{{ asset('storage/'.$libro->imagen) }}">
                <h4>{{ $libro->titulo }}</h4>
            </div>
        @endforeach
    </div>
</div>

<div id="perfilModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal('perfilModal')">&times;</span>
        <h3>Mi Perfil</h3>
        <p><strong>Código:</strong> {{ session('usuario')->codigo }}</p>
        <p><strong>Tipo:</strong> Cliente</p>
    </div>
</div>

<!-- Modal Pedidos -->
<div id="pedidosModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal('pedidosModal')">&times;</span>
        <h3>Mis Pedidos</h3>
        <p>Aquí aparecerán los pedidos del cliente.</p>
    </div>
</div>

<!-- Modal Nuevo Pedido -->
<div id="nuevoPedidoModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal('nuevoPedidoModal')">&times;</span>
        <h3>Realizar Pedido</h3>
        <p>Selecciona productos desde la tienda para generar un pedido.</p>
        <a href="{{ route('storebook') }}" class="action-btn btn-primary">Ir a la tienda</a>
    </div>
</div>

<!-- Modal Logout -->
<div id="logoutModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal('logoutModal')">&times;</span>
        <h3>¿Cerrar sesión?</h3>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="cancel-btn -">Sí, salir</button>
        </form>
    </div>
</div>


</body>
</html>