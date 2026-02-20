<!DOCTYPE html>
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
    <link rel="stylesheet" href="{{ asset('css/storebook.css') }}">
</head>
<body>

<canvas id="bg-canvas"></canvas>
<div class="paper-overlay"></div>


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

<div class="wrapper">

    <!-- PAGE HERO -->
    <div class="page-hero">
        <p class="page-eyebrow">Tienda Biblioteca</p>
        <h1 class="page-title">Explora &amp; <span>Descubre</span></h1>
        <p class="page-subtitle">Encuentra libros que cobran vida con realidad aumentada y virtual. Una colección para todos los gustos y edades.</p>
    </div>

    <!-- FILTERS -->
    <div class="filter-bar">
        <span class="filter-label">Filtrar por</span>
        <button class="filter-btn active" data-filter="all">Todos</button>
        <button class="filter-btn teal" data-filter="ar">AR · Aumentada</button>
        <button class="filter-btn rose" data-filter="vr">VR · Virtual</button>
        <button class="filter-btn" data-filter="classic">Clásicos</button>
    </div>

    <!-- BOOKS GRID -->
    <section class="books-section">
        <div class="books-grid" id="booksGrid">

            <div class="book-card" data-type="ar">
                <div class="book-cover" style="background: linear-gradient(135deg,#e8f5e9,#c8e6c9);">
                    🌿
                    <span class="book-badge badge-ar">AR</span>
                </div>
                <div class="book-body">
                    <p class="book-category">Ciencias Naturales</p>
                    <h3 class="book-title">El Jardín Vivo</h3>
                    <p class="book-author">Lucía Montoya</p>
                </div>
                <div class="book-footer">
                    <div class="book-price">$129 <span>MXN</span></div>
                    <button class="book-btn"><i class="fa fa-book-open" style="font-size:10px;"></i> Ver</button>
                </div>
            </div>

            <div class="book-card" data-type="vr">
                <div class="book-cover" style="background: linear-gradient(135deg,#fce4ec,#f8bbd0);">
                    🚀
                    <span class="book-badge badge-vr">VR</span>
                </div>
                <div class="book-body">
                    <p class="book-category">Ciencia Ficción</p>
                    <h3 class="book-title">Más Allá de Orión</h3>
                    <p class="book-author">Diego Fernández</p>
                </div>
                <div class="book-footer">
                    <div class="book-price">$189 <span>MXN</span></div>
                    <button class="book-btn"><i class="fa fa-book-open" style="font-size:10px;"></i> Ver</button>
                </div>
            </div>

            <div class="book-card" data-type="classic">
                <div class="book-cover" style="background: linear-gradient(135deg,#fff8e1,#ffe082);">
                    📜
                    <span class="book-badge badge-classic">Clásico</span>
                </div>
                <div class="book-body">
                    <p class="book-category">Historia</p>
                    <h3 class="book-title">Crónicas del Imperio</h3>
                    <p class="book-author">Ana Ruiz</p>
                </div>
                <div class="book-footer">
                    <div class="book-price">$99 <span>MXN</span></div>
                    <button class="book-btn"><i class="fa fa-book-open" style="font-size:10px;"></i> Ver</button>
                </div>
            </div>

            <div class="book-card" data-type="ar">
                <div class="book-cover" style="background: linear-gradient(135deg,#e3f2fd,#bbdefb);">
                    🌊
                    <span class="book-badge badge-ar">AR</span>
                </div>
                <div class="book-body">
                    <p class="book-category">Aventura</p>
                    <h3 class="book-title">Profundidades Azules</h3>
                    <p class="book-author">Carlos Ibáñez</p>
                </div>
                <div class="book-footer">
                    <div class="book-price">$149 <span>MXN</span></div>
                    <button class="book-btn"><i class="fa fa-book-open" style="font-size:10px;"></i> Ver</button>
                </div>
            </div>

            <div class="book-card" data-type="vr">
                <div class="book-cover" style="background: linear-gradient(135deg,#f3e5f5,#e1bee7);">
                    🏔️
                    <span class="book-badge badge-vr">VR</span>
                </div>
                <div class="book-body">
                    <p class="book-category">Aventura</p>
                    <h3 class="book-title">Cimas del Mundo</h3>
                    <p class="book-author">Sofía Guerrero</p>
                </div>
                <div class="book-footer">
                    <div class="book-price">$179 <span>MXN</span></div>
                    <button class="book-btn"><i class="fa fa-book-open" style="font-size:10px;"></i> Ver</button>
                </div>
            </div>

            <div class="book-card" data-type="classic">
                <div class="book-cover" style="background: linear-gradient(135deg,#fbe9e7,#ffccbc);">
                    🎨
                    <span class="book-badge badge-classic">Clásico</span>
                </div>
                <div class="book-body">
                    <p class="book-category">Arte & Creatividad</p>
                    <h3 class="book-title">El Color de las Ideas</h3>
                    <p class="book-author">Valentina Cruz</p>
                </div>
                <div class="book-footer">
                    <div class="book-price">$89 <span>MXN</span></div>
                    <button class="book-btn"><i class="fa fa-book-open" style="font-size:10px;"></i> Ver</button>
                </div>
            </div>

            <div class="book-card" data-type="ar">
                <div class="book-cover" style="background: linear-gradient(135deg,#e8f5e9,#a5d6a7);">
                    🦋
                    <span class="book-badge badge-ar">AR</span>
                </div>
                <div class="book-body">
                    <p class="book-category">Infantil</p>
                    <h3 class="book-title">El Bosque Mágico</h3>
                    <p class="book-author">Marta Pérez</p>
                </div>
                <div class="book-footer">
                    <div class="book-price">$79 <span>MXN</span></div>
                    <button class="book-btn"><i class="fa fa-book-open" style="font-size:10px;"></i> Ver</button>
                </div>
            </div>

            <div class="book-card" data-type="vr">
                <div class="book-cover" style="background: linear-gradient(135deg,#e8eaf6,#c5cae9);">
                    🌌
                    <span class="book-badge badge-vr">VR</span>
                </div>
                <div class="book-body">
                    <p class="book-category">Ciencia Ficción</p>
                    <h3 class="book-title">La Última Galaxia</h3>
                    <p class="book-author">Roberto Salas</p>
                </div>
                <div class="book-footer">
                    <div class="book-price">$199 <span>MXN</span></div>
                    <button class="book-btn"><i class="fa fa-book-open" style="font-size:10px;"></i> Ver</button>
                </div>
            </div>

        </div>
    </section>

    <!-- WELCOME BANNER -->
    <div class="welcome-banner">
        <div class="banner-text">
            <h2>¿Primera vez aquí?</h2>
            <p>Regístrate gratis y accede a tu primera experiencia inmersiva sin costo. La lectura nunca había sido tan emocionante.</p>
        </div>
        <div class="banner-actions">
            <a href="{{ route('login') }}" class="btn-banner btn-banner-primary">
                <i class="fa fa-user-plus" style="font-size:11px;"></i> Crear cuenta
            </a>
            <a href="{{ route('welcome') }}" class="btn-banner btn-banner-ghost">Saber más</a>
        </div>
    </div>

    <!-- FOOTER -->
    <footer class="footer">
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

<script>
/* ── THREE.JS WARM PARTICLES ── */
const canvas   = document.getElementById('bg-canvas');
const renderer = new THREE.WebGLRenderer({ canvas, antialias:true, alpha:true });
renderer.setPixelRatio(Math.min(window.devicePixelRatio,2));
renderer.setSize(window.innerWidth, window.innerHeight);
const scene  = new THREE.Scene();
const camera = new THREE.PerspectiveCamera(60, window.innerWidth/window.innerHeight, 0.1, 1000);
camera.position.z = 5;

const N   = 1200, geo = new THREE.BufferGeometry();
const pos = new Float32Array(N*3), col = new Float32Array(N*3);
const pal = [[0.91,0.51,0.05],[0.05,0.48,0.43],[0.79,0.31,0.43],[0.96,0.66,0.26]];
for(let i=0;i<N;i++){
    pos[i*3]=(Math.random()-0.5)*22; pos[i*3+1]=(Math.random()-0.5)*22; pos[i*3+2]=(Math.random()-0.5)*8;
    const p=pal[Math.floor(Math.random()*pal.length)];
    col[i*3]=p[0]; col[i*3+1]=p[1]; col[i*3+2]=p[2];
}
geo.setAttribute('position', new THREE.BufferAttribute(pos,3));
geo.setAttribute('color',    new THREE.BufferAttribute(col,3));
const pts = new THREE.Points(geo, new THREE.PointsMaterial({ size:0.05, vertexColors:true, transparent:true, opacity:0.5 }));
scene.add(pts);

let t=0;
(function frame(){ requestAnimationFrame(frame); t+=0.0005; pts.rotation.y=t; pts.rotation.x=t*0.3; renderer.render(scene,camera); })();
window.addEventListener('resize',()=>{ camera.aspect=window.innerWidth/window.innerHeight; camera.updateProjectionMatrix(); renderer.setSize(window.innerWidth,window.innerHeight); });

/* ── MOBILE NAVBAR ── */
const navToggle = document.getElementById('navToggle');
const navLinks  = document.getElementById('navLinks');
navToggle.addEventListener('click', () => navLinks.classList.toggle('open'));

/* ── FILTER ── */
const filterBtns = document.querySelectorAll('.filter-btn');
const bookCards  = document.querySelectorAll('.book-card');

filterBtns.forEach(btn => {
    btn.addEventListener('click', () => {
        filterBtns.forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
        const filter = btn.dataset.filter;
        bookCards.forEach(card => {
            const show = filter === 'all' || card.dataset.type === filter;
            card.style.display = show ? '' : 'none';
            if (show) {
                // re-trigger animation
                card.classList.remove('visible');
                setTimeout(() => card.classList.add('visible'), 50);
            }
        });
    });
});

/* ── SCROLL REVEAL ── */
const revObs = new IntersectionObserver(entries => {
    entries.forEach((e, i) => {
        if (e.isIntersecting) {
            setTimeout(() => e.target.classList.add('visible'), i * 80);
            revObs.unobserve(e.target);
        }
    });
}, { threshold: 0.08 });
bookCards.forEach(el => revObs.observe(el));
</script>
</body>
</html>