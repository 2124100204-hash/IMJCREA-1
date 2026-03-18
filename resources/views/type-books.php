!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tienda Biblioteca · {{ config('app.name', 'INMERSIA') }}</title>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,700;0,900;1,700&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <link rel="stylesheet" href="{{ asset('css/default.css') }}">
    <link rel="stylesheet" href="{{ asset('css/virtual-reality.css') }}">
</head>
<body>
<nav class="navbar">
    <a href="{{ route('welcome') }}" class="navbar-logo">INMER<span>SIA</span></a>

    <div class="navbar-links" id="navLinks">
        <a href="{{ route('welcome') }}"><i class="fa fa-home" style="font-size:11px;"></i> Inicio</a>
        <a href="#" class="active"><i class="fa fa-store" style="font-size:11px;"></i> Tienda</a>
        <a href="{{ route('virtual-reality') }}">Realidad Virtual</a>
        <a href="{{ route('augmented-reality') }}">Realidad Aumentada</a>
        <a href="{{ route('contact') }}">Contacto</a>
        <a href="{{ route('login') }}" class="btn-nav-primary">Iniciar Sesión</a>
    </div>

    <button class="navbar-toggle" id="navToggle" aria-label="Menú">
        <span></span><span></span><span></span>
    </button>
</nav>
</body>