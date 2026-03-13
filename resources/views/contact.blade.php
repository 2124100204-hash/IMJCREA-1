<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Contacto · {{ config('app.name', 'INMERSIA') }}</title>

    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/default.css') }}">
    <link rel="stylesheet" href="{{ asset('css/contact.css') }}">
</head>
<body>

{{-- ── NAVBAR ──────────────────────────────────────────────── --}}
<nav class="navbar">
    <a href="{{ route('welcome') }}" class="navbar-logo">INMER<span>SIA</span></a>

    @auth
        <div class="navbar-links" id="navLinks">
            <a href="{{ route('cliente.dashboard') }}"><i class="fa fa-home"></i> Mi Dashboard</a>
            <a href="{{ route('cliente.storebook') }}"><i class="fa fa-store"></i> Tienda</a>
            <a href="{{ route('cliente.libros.tipo', 'ar') }}"><i class="fa fa-camera"></i> AR</a>
            <a href="{{ route('cliente.libros.tipo', 'vr') }}"><i class="fa fa-vr-cardboard"></i> VR</a>
            <a href="{{ route('contact') }}" class="active"><i class="fa fa-envelope"></i> Contacto</a>
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
            <a href="{{ route('storebook') }}"><i class="fa fa-store"></i> Tienda</a>
            <a href="{{ route('libros.tipo', 'vr') }}">Realidad Virtual</a>
            <a href="{{ route('libros.tipo', 'ar') }}">Realidad Aumentada</a>
            <a href="{{ route('contact') }}" class="active"><i class="fa fa-envelope"></i> Contacto</a>
            <a href="{{ route('login') }}" class="btn-ghost">Iniciar Sesión</a>
        </div>
    @endauth

    <button class="navbar-toggle" id="navToggle" aria-label="Menú">
        <span></span><span></span><span></span>
    </button>
</nav>

{{-- ── HERO ─────────────────────────────────────────────────── --}}
<section class="contact-hero">
    <div class="contact-hero-text">
        <span class="contact-hero-eyebrow">
            <i class="fa fa-envelope"></i> Contáctanos
        </span>
        <h1>Tu opinión nos <span>importa</span></h1>
        <p>En INMERSIA valoramos cada mensaje. Si tienes una sugerencia, duda o simplemente quieres
           compartir tu experiencia, estamos aquí para escucharte.</p>
    </div>

    <div class="contact-hero-cards">
        <div class="contact-card">
            <div class="contact-card-icon amber"><i class="fa fa-phone"></i></div>
            <div class="contact-card-body">
                <p class="contact-card-label">Teléfono</p>
                <p class="contact-card-value">+1 234 567 890</p>
            </div>
        </div>
        <div class="contact-card">
            <div class="contact-card-icon teal"><i class="fa fa-envelope"></i></div>
            <div class="contact-card-body">
                <p class="contact-card-label">Correo electrónico</p>
                <p class="contact-card-value">contactoinmersia@gmail.com</p>
            </div>
        </div>
        <div class="contact-card">
            <div class="contact-card-icon rose"><i class="fa fa-map-marker-alt"></i></div>
            <div class="contact-card-body">
                <p class="contact-card-label">Dirección</p>
                <a href="https://www.google.com/maps/search/?api=1&query=C.+Independencia+55,+Centro,+44100+Guadalajara,+Jal."
   target="_blank"
   rel="noopener noreferrer"
   class="contact-card-value contact-card-link">
    <i class="fa fa-map-marker-alt" style="margin-right:5px;"></i>
    C. Independencia 55, Centro, 44100 Guadalajara, Jal.
</a>
            </div>
        </div>
    </div>
</section>

{{-- ── CONTENIDO PRINCIPAL ──────────────────────────────────── --}}
<section class="contact-main">

    {{-- Columna izquierda --}}
    <div class="contact-aside">
        <h2 class="aside-title">¿En qué podemos ayudarte?</h2>
        <p class="aside-desc">
            Nuestro equipo responde en un plazo de 24 horas hábiles. Puedes escribirnos sobre
            pedidos, experiencias AR/VR, devoluciones o cualquier duda general.
        </p>

        <div class="schedule-block">
            <p class="schedule-title">
                <i class="fa fa-clock"></i> Horario de atención
            </p>
            <div class="schedule-row">
                <span>Lunes – Viernes</span><span>9:00 – 18:00</span>
            </div>
            <div class="schedule-row">
                <span>Sábado</span><span>10:00 – 14:00</span>
            </div>
            <div class="schedule-row">
                <span>Domingo</span><span>Cerrado</span>
            </div>
        </div>

    
    </div>

    {{-- Columna derecha: formulario --}}
    <div class="contact-form-wrap">
        <h3 class="form-title">Envíanos un mensaje</h3>
        <p class="form-subtitle">Te respondemos en menos de 24 horas.</p>

        {{-- Alerta de éxito --}}
        @if(session('success'))
            <div class="alert alert-success">
                <i class="fa fa-check-circle"></i> {{ session('success') }}
            </div>
        @endif

        {{-- Errores de validación --}}
        @if($errors->any())
            <div class="alert alert-error"  >
                <strong><i class="fa fa-exclamation-circle"></i> Corrige los siguientes errores:</strong>
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('contact.submit') }}" method="POST">
            @csrf

            <div class="form-row">
                <div>
                    <label class="form-label" for="name">Nombre</label>
                    <input class="form-input" type="text" id="name" name="name"
                           placeholder="Tu nombre completo"
                           value="{{ old('name') }}" required>
                </div>
                <div>
                    <label class="form-label" for="email">Correo</label>
                    <input class="form-input" type="email" id="email" name="email"
                           placeholder="tucorreo@email.com"
                           value="{{ old('email') }}" required>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label" for="asunto">Asunto</label>
                <select class="form-select" id="asunto" name="asunto">
                    <option value="">Selecciona un tema…</option>
                    <option value="pedido"     {{ old('asunto') === 'pedido'     ? 'selected' : '' }}>Mi pedido</option>
                    <option value="ar_vr"      {{ old('asunto') === 'ar_vr'      ? 'selected' : '' }}>Experiencia AR / VR</option>
                    <option value="devolucion" {{ old('asunto') === 'devolucion' ? 'selected' : '' }}>Devolución</option>
                    <option value="sugerencia" {{ old('asunto') === 'sugerencia' ? 'selected' : '' }}>Sugerencia</option>
                    <option value="otro"       {{ old('asunto') === 'otro'       ? 'selected' : '' }}>Otro</option>
                </select>
            </div>

            <div class="form-group">
                <label class="form-label" for="message">Mensaje</label>
                <textarea class="form-textarea" id="message" name="message"
                          placeholder="Cuéntanos en qué podemos ayudarte…"
                          required>{{ old('message') }}</textarea>
            </div>

            <button type="submit" class="form-submit">
                <i class="fa fa-paper-plane"></i> Enviar mensaje
            </button>
        </form>
    </div>

</section>

</body>
</html>