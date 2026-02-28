<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard Cliente - {{ config('app.name', 'IMJCREA') }}</title>

    <link rel="stylesheet" href="{{ asset('css/default.css') }}">
    <link rel="stylesheet" href="{{ asset('css/client.css') }}">
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

    {{-- Hero de bienvenida --}}
    <div class="welcome-card">
        <h1>Bienvenido, {{ $usuario->nombre ?? $usuario->username }}</h1>
        <p>Descubre nuevas historias y recomendaciones personalizadas.</p>
        <div class="action-buttons">
            <button class="btn-action btn-primary-action" onclick="openModal('perfilModal')">👤 Ver Perfil</button>
            <button class="btn-action btn-primary-action" onclick="openModal('pedidosModal')">📦 Mis Pedidos</button>
            <button class="btn-action btn-secondary-action" onclick="openModal('nuevoPedidoModal')">💡 Sugerencias</button>
            <button class="btn-action" style="background:#e74c3c;color:white;border-radius:8px;padding:14px 28px;border:none;cursor:pointer;font-weight:600;" onclick="openModal('logoutModal')">🚪 Salir</button>
        </div>
    </div>

    {{-- Recomendados --}}
    <div class="my-books-section">
        <div class="section-header">
            <h2 class="section-title">📌 Recomendados para ti</h2>
        </div>
        <div class="books-grid">
            @forelse($libros as $libro)
                <div class="book-card">
                    <div class="book-cover">
                        <img src="{{ asset('storage/'.$libro->imagen) }}" alt="{{ $libro->titulo }}">
                        @if($libro->formato)
                            <span class="book-badge">{{ strtoupper($libro->formato) }}</span>
                        @endif
                    </div>
                    <div class="book-body">
                        <div class="book-title">{{ $libro->titulo }}</div>
                        <div class="book-author">{{ $libro->autor }}</div>
                    </div>
                    <div class="book-footer">
                        <span class="book-price">${{ number_format($libro->precio ?? 0, 2) }}</span>
                        <a href="{{ route('libro.details', $libro->id) }}" class="book-btn">Ver más</a>
                    </div>
                </div>
            @empty
                <div class="empty-state">
                    <i>📚</i>
                    <p>No hay libros disponibles por el momento.</p>
                </div>
            @endforelse
        </div>
    </div>

    {{-- Tendencias --}}
    <div class="my-books-section">
        <div class="section-header">
            <h2 class="section-title">🔥 Tendencias</h2>
        </div>
        <div class="books-grid">
            @foreach($libros->take(5) as $libro)
                <div class="book-card">
                    <div class="book-cover">
                        <img src="{{ asset('storage/'.$libro->imagen) }}" alt="{{ $libro->titulo }}">
                    </div>
                    <div class="book-body">
                        <div class="book-title">{{ $libro->titulo }}</div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

</div>

{{-- ===================== MODALES ===================== --}}

{{-- Modal Perfil --}}
<div id="perfilModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal('perfilModal')">&times;</span>
        <h2>👤 Mi Perfil</h2>
        <div class="modal-body">
            <p><strong>Nombre:</strong> {{ $usuario->nombre ?? '-' }}</p>
            <p><strong>Usuario:</strong> {{ $usuario->username ?? '-' }}</p>
            <p><strong>Email:</strong> {{ $usuario->email ?? '-' }}</p>
            <p><strong>Código:</strong> {{ $usuario->codigo ?? '-' }}</p>
            <p><strong>Tipo:</strong> Cliente</p>
        </div>
    </div>
</div>

{{-- Modal Pedidos --}}
<div id="pedidosModal" class="modal">
    <div class="modal-content modal-large">
        <span class="close" onclick="closeModal('pedidosModal')">&times;</span>
        <h2>📦 Mis Pedidos</h2>

        <div class="pedidos-container">
            @if($pedidos->isEmpty())
                <div class="empty-pedidos">
                    <p style="font-size:40px;">📭</p>
                    <p>Aún no tienes pedidos registrados.</p>
                    <a href="{{ route('storebook') }}" class="btn-primary-action" style="margin-top:16px; display:inline-block;">Ir a la tienda</a>
                </div>
            @else
                @foreach($pedidos as $pedido)
                    <div class="pedido-card">
                        <div class="pedido-header">
                            <span class="pedido-id">Pedido #{{ $pedido->id }}</span>
                            <span class="pedido-status status-pending">
                                {{ $pedido->created_at->format('d/m/Y H:i') }}
                            </span>
                        </div>

                        <div class="pedido-content">
                            @if($pedido->detalles->isEmpty())
                                <p style="color:#aaa;">Sin libros en este pedido.</p>
                            @else
                                <table style="width:100%; border-collapse:collapse; font-size:13px;">
                                    <thead>
                                        <tr style="border-bottom:1px solid #ddd;">
                                            <th style="text-align:left; padding:6px 4px; color:#888;">#</th>
                                            <th style="text-align:left; padding:6px 4px; color:#888;">Libro</th>
                                            <th style="text-align:left; padding:6px 4px; color:#888;">Autor</th>
                                            <th style="text-align:left; padding:6px 4px; color:#888;">Formato</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($pedido->detalles as $i => $detalle)
                                            <tr style="border-bottom:1px solid #f0f0f0;">
                                                <td style="padding:6px 4px;">{{ $i + 1 }}</td>
                                                <td style="padding:6px 4px; font-weight:600;">
                                                    {{ $detalle->libro->titulo ?? 'Libro no disponible' }}
                                                </td>
                                                <td style="padding:6px 4px; color:#666;">
                                                    {{ $detalle->libro->autor ?? '-' }}
                                                </td>
                                                <td style="padding:6px 4px;">
                                                    @if($detalle->libro->formato ?? false)
                                                        <span class="book-badge" style="position:static; font-size:10px; padding:3px 8px;">
                                                            {{ strtoupper($detalle->libro->formato) }}
                                                        </span>
                                                    @else
                                                        -
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <p style="margin-top:10px; font-size:12px; color:#888;">
                                    Total: {{ $pedido->detalles->count() }} libro(s)
                                </p>
                            @endif
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>
</div>

{{-- Modal Sugerencias --}}
<div id="nuevoPedidoModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal('nuevoPedidoModal')">&times;</span>
        <h2>💡 Sugerencias</h2>
        <div class="modal-body">
            <p>¿Tienes alguna sugerencia de libro o mejora para la plataforma?</p>
            <div class="form-group" style="margin-top:16px;">
                <label>Tu sugerencia</label>
                <textarea class="textarea-field" rows="4" placeholder="Escribe tu sugerencia aquí..."></textarea>
            </div>
            <button class="btn-comprar">Enviar sugerencia</button>
        </div>
    </div>
</div>

{{-- Modal Logout --}}
<div id="logoutModal" class="modal">
    <div class="modal-content" style="max-width:360px; text-align:center;">
        <span class="close" onclick="closeModal('logoutModal')">&times;</span>
        <h2>🚪 ¿Cerrar sesión?</h2>
        <p style="color:#666; margin:16px 0;">Tu sesión se cerrará y tendrás que volver a iniciar sesión.</p>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn-comprar" style="background:#e74c3c;">Sí, cerrar sesión</button>
        </form>
        <button onclick="closeModal('logoutModal')" style="margin-top:10px; background:none; border:none; color:#888; cursor:pointer; font-size:13px;">Cancelar</button>
    </div>
</div>

</body>
</html>