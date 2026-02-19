<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name', 'IMJCREA') }}</title>
        
        <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
<script src="{{ asset('js/app.js') }}" defer></script>

        <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
        
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
        
        <link rel="stylesheet" href="{{ asset('css/default.css') }}">
        <link rel="stylesheet" href="{{ asset('css/app.css') }}">

      
    </head>
   <body>
  

        <div class="wrapper">
            <div class="menu-toggle" id="menuToggle">
                <span></span>
                <span></span>
                <span></span>
            </div>

            <nav class="sidebar-menu" id="sidebarMenu">
                <div class="menu-close" id="menuClose">&times;</div>
                <ul class="menu-list">
                    <li><a href="#inicio">Inicio</a></li>
                     <li><a href="{{ route('login') }}">Iniciar Sesión</a></li>
                    <li><a href="#servicios">Servicios</a></li>
                    <li><a href="#quienes">Quiénes Somos</a></li>                   
                    <li><a href="#contacto">Contacto</a></li>
                </ul>
            </nav>

            <div class="header-section">
                <div class="header-content">
                    <h1 class="main-title" id="decrypt-title">INMERSIA</h1>
                    <div class="slogan-box">
                        <p class="slogan" id="typed-slogan">Inmersión real en un mundo inrreal</p>
                        <span id="cursor" class="cursor">|</span>
                    </div>
                </div>
                <div class="divider-line"></div>
            </div>

            <div class="gallery-container">
                <div class="gallery-item" style="background-color: #B8E6D5;">Tienda Biblioteca</div>
                <div class="gallery-item" style="background-color: #F5D5C0;">Libros Realidad Aumentada</div>
                <div class="gallery-item" style="background-color: #D4D9F7;">Libros Realidad Virtual</div>
                <div class="gallery-item" style="background-color: #D4F5D4;">Explorar</div>
            </div>

            <div class="content-section">
                <h2 class="section-title">Bienvenido a INMERSIA</h2>
                <p class="section-description">En INMERSIA, te ofrecemos una experiencia única de inmersión en un mundo virtual. Descubre nuevas realidades, explora paisajes impresionantes y vive aventuras inolvidables. Nuestra plataforma de realidad virtual te transportará a lugares que solo habías imaginado. ¡Únete a nosotros y sumérgete en la diversión sin límites!</p>
            </div>
        </div>

     
    </body>
</html>
