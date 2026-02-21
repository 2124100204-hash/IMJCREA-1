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


    <!-- BG CANVAS -->
    <canvas id="bg-canvas"></canvas>
    <div class="noise-overlay"></div>



    <div class="wrapper">

     
     

        <!-- HERO -->
        <section class="hero" id="inicio">
            <div class="hero-badge">Sistema activo · AR/VR</div>

            <span class="hero-eyebrow">// Inmersión real en un mundo inrreal</span>

            <h1 class="hero-title">
                <span class="line-1">INMERSIA</span>
                <span class="line-2">CREA.</span>
            </h1>

            <p class="hero-subtitle">
                Explora mundos de conocimiento a través de la realidad aumentada y virtual. 
                Libros que cobran vida, experiencias que trascienden la pantalla.
            </p>

            <div class="hero-actions">
                <a href="#explorar" class="btn-primary">
                    <i class="fa fa-play" style="font-size:10px;"></i> Explorar ahora
                </a>
                <a href="{{ route('login') }}" class="btn-ghost">
                    Iniciar sesión →
                </a>
            </div>

            <div class="scroll-indicator">
                <div class="scroll-line"></div>
                scroll
            </div>
        </section>

        <!-- MARQUEE -->
        <div class="marquee-band">
            <div class="marquee-inner">
                <span class="marquee-text">Realidad Aumentada <span class="marquee-sep">✦</span></span>
                <span class="marquee-text">Realidad Virtual <span class="marquee-sep">✦</span></span>
                <span class="marquee-text">Creatividad Inmersiva <span class="marquee-sep">✦</span></span>
                <span class="marquee-text">Biblioteca Digital <span class="marquee-sep">✦</span></span>
                <span class="marquee-text">IMJCREA 2026 <span class="marquee-sep">✦</span></span>
                <!-- duplicate for seamless loop -->
                <span class="marquee-text">Realidad Aumentada <span class="marquee-sep">✦</span></span>
                <span class="marquee-text">Realidad Virtual <span class="marquee-sep">✦</span></span>
                <span class="marquee-text">Creatividad Inmersiva <span class="marquee-sep">✦</span></span>
                <span class="marquee-text">Biblioteca Digital <span class="marquee-sep">✦</span></span>
                <span class="marquee-text">IMJCREA 2026 <span class="marquee-sep">✦</span></span>
            </div>
        </div>

   <section class="cards-section" id="explorar">
    <div class="section-header">
        <span class="section-label">// Nuestros servicios</span>
        <span class="section-count">04 experiencias</span>
    </div>
 <div class="cards-grid"> 
         <div class="card" data-modal="1">
    <div class="card-top-bar"></div>
    <span class="card-number">01</span>
    <div class="card-icon"><i class="fa fa-store"></i></div>
    <h3 class="card-title">Tienda Biblioteca</h3>
    <p class="card-desc">Accede a nuestra colección curada de experiencias inmersivas y contenido creativo para todas las edades.</p>
    <a href="{{ route('storebook') }}" class="card-btn" onclick="event.stopPropagation()">
        Ir a la tienda <i class="fa fa-arrow-right" style="font-size:10px;"></i>
    </a>
</div>

<a href="{{ route('libros.tipo', 'ar') }}" class="card">
    <span class="card-number">02</span>
    <div class="card-icon"><i class="fa fa-book-open"></i></div>
    <h3 class="card-title">Libros AR</h3>
    <p class="card-desc">
        Libros que despiertan con realidad aumentada.
    </p>
    <div class="card-arrow">
        <i class="fa fa-arrow-right"></i>
    </div>
</a>

           <a href="{{ route('libros.tipo', 'vr') }}" class="card">
    <span class="card-number">03</span>
    <div class="card-icon"><i class="fa fa-vr-cardboard"></i></div>
    <h3 class="card-title">Libros VR</h3>
    <p class="card-desc">
        Narrativas inmersivas en realidad virtual.
    </p>
    <div class="card-arrow">
        <i class="fa fa-arrow-right"></i>
    </div>
</a>

             <a href="{{ route('storebook') }}" class="card">
    <span class="card-number">04</span>
    <div class="card-icon"><i class="fa fa-compass"></i></div>
    <h3 class="card-title">Explorar</h3>
    <p class="card-desc">
        Descubre contenido nuevo.
    </p>
    <div class="card-arrow">
        <i class="fa fa-arrow-right"></i>
    </div>
</a>
</div>
        </section>

        <!-- STATS -->
        <div class="stats-band">
            <div class="stat-item">
                <div class="stat-number">Experiencias</div>
                <div class="stat-label">Inimaginables</div>
            </div>
            <div class="stat-item">
                <div class="stat-number">AR</div>
                <div class="stat-label">Realidad Aumentada</div>
            </div>
            <div class="stat-item">
                <div class="stat-number">VR</div>
                <div class="stat-label">Realidad Virtual</div>
            </div>
            <div class="stat-item">
                <div class="stat-number">∞</div>
                <div class="stat-label">Creatividad</div>
            </div>
        </div>

        <!-- FOOTER -->
        <footer class="footer" id="contacto">
            <div class="footer-logo">INMERSIA</div>
            <p class="footer-copy">© 2026 IMJCREA. Todos los derechos reservados.</p>
            <div class="social-icons">
                <a href="#"><i class="fab fa-facebook-f"></i></a>
                <a href="#"><i class="fab fa-instagram"></i></a>
                <a href="#"><i class="fab fa-twitter"></i></a>
                <a href="#"><i class="fab fa-linkedin-in"></i></a>
            </div>
        </footer>

    </div>
    </body>
</html>
