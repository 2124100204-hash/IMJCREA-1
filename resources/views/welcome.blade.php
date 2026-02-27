<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'IMJCREA') }}</title>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/ScrollTrigger.min.js"></script>
    
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/default.css') }}">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    <style>
        :root { --accent: #FF8C00; --bg: #000000; }
        
        body { background-color: var(--bg); margin: 0; overflow-x: hidden; font-family: 'Instrument Sans', sans-serif; }

        #bg-canvas {
            position: fixed;
            top: 0; left: 0;
            width: 100vw; height: 100vh;
            z-index: -2;
        }

        .noise-overlay {
            position: fixed;
            top: 0; left: 0;
            width: 100%; height: 100%;
            background: url('https://grainy-gradients.vercel.app/noise.svg');
            opacity: 0.04;
            pointer-events: none;
            z-index: -1;
        }

        .wrapper { position: relative; z-index: 1; }

        /* Ajustes de visibilidad inicial para GSAP */
        .hero-badge, .hero-eyebrow, .line-1, .line-2, .hero-subtitle, .hero-actions, .card, .stat-item {
            opacity: 0;
        }

        .hero-title { 
            font-size: 5rem; 
            line-height: 0.9; 
            font-weight: 700; 
            margin: 20px 0;
            cursor: default;
        }
        .line-2 { color: var(--accent); }

        .cards-section { background: #fdfbf7; color: #1a1a1a; padding: 100px 10%; border-radius: 40px 40px 0 0; }
        
        .marquee-band {
            background: var(--accent);
            padding: 20px 0;
            overflow: hidden;
            display: flex;
            white-space: nowrap;
        }
        .marquee-inner {
            display: flex;
            animation: marquee 30s linear infinite;
        }
        .marquee-text {
            color: #000;
            font-weight: 700;
            text-transform: uppercase;
            font-size: 1.2rem;
            margin-right: 50px;
        }

        @keyframes marquee {
            0% { transform: translateX(0); }
            100% { transform: translateX(-50%); }
        }

        .stats-band {
            background: #120F0D;
            padding: 80px 10%;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 40px;
            text-align: center;
        }
        .stat-number { color: var(--accent); font-size: 2.5rem; font-weight: 700; }
        .stat-label { color: #888; text-transform: uppercase; letter-spacing: 2px; font-size: 0.7rem; }
    </style>
</head>
<body>

    <canvas id="bg-canvas"></canvas>
    <div class="noise-overlay"></div>

    <div class="wrapper">
        <section class="hero" id="inicio" style="min-height: 100vh; display: flex; flex-direction: column; justify-content: center; padding: 0 10%;">
            <div class="hero-badge" style="color: var(--accent); border: 1px solid var(--accent); padding: 5px 15px; border-radius: 20px; width: fit-content; font-size: 0.8rem; margin-bottom: 20px;">
                Sistema activo · AR/VR
            </div>

            <span class="hero-eyebrow" style="color: #666; font-family: monospace;">// Inmersión real en un mundo inrreal</span>

            <h1 class="hero-title" id="decrypt-title">
                <span class="line-1" style="display: block; color: white;">INMERSIA</span>
                <span class="line-2" style="display: block;">CREA.</span>
            </h1>

            <p class="hero-subtitle" style="color: #ccc; max-width: 600px; font-size: 1.1rem; margin-bottom: 30px;">
                Explora mundos de conocimiento a través de la realidad aumentada y virtual. 
                Libros que cobran vida, experiencias que trascienden la pantalla.
            </p>

            <div class="hero-actions" style="display: flex; gap: 20px;">
                <a href="#explorar" class="btn-primary" style="background: var(--accent); color: black; padding: 15px 30px; border-radius: 8px; text-decoration: none; font-weight: 600;">
                    <i class="fa fa-play" style="font-size:10px;"></i> Explorar ahora
                </a>
                <a href="{{ route('login') }}" class="btn-ghost" style="border: 1px solid var(--accent); color: var(--accent); padding: 15px 30px; border-radius: 8px; text-decoration: none;">
                    Iniciar sesión →
                </a>
            </div>

            <div class="scroll-indicator" style="margin-top: 50px; color: #444; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 4px;">
                <div class="scroll-line" style="width: 1px; height: 50px; background: var(--accent); margin-bottom: 10px;"></div>
                scroll
            </div>
        </section>

        <div class="marquee-band">
            <div class="marquee-inner">
                <span class="marquee-text">Realidad Aumentada ✦</span>
                <span class="marquee-text">Realidad Virtual ✦</span>
                <span class="marquee-text">Creatividad Inmersiva ✦</span>
                <span class="marquee-text">Biblioteca Digital ✦</span>
                <span class="marquee-text">IMJCREA 2026 ✦</span>
                <span class="marquee-text">Realidad Aumentada ✦</span>
                <span class="marquee-text">Realidad Virtual ✦</span>
                <span class="marquee-text">Creatividad Inmersiva ✦</span>
                <span class="marquee-text">Biblioteca Digital ✦</span>
                <span class="marquee-text">IMJCREA 2026 ✦</span>
            </div>
        </div>

        <section class="cards-section" id="explorar">
            <div class="section-header" style="display: flex; justify-content: space-between; margin-bottom: 50px;">
                <span class="section-label" style="color: #B5A89A; letter-spacing: 2px;">// NUESTROS SERVICIOS</span>
                <span class="section-count" style="font-weight: 700;">04 EXPERIENCIAS</span>
            </div>

            <div class="cards-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 25px;"> 
                <a href="{{ route('storebook') }}" class="card" style="background: white; padding: 40px; border-radius: 24px; text-decoration: none; color: inherit; display: block; box-shadow: 0 10px 30px rgba(0,0,0,0.05);">
                    <span class="card-number" style="color: var(--accent); font-weight: 800; font-size: 0.8rem;">01</span>
                    <div class="card-icon" style="font-size: 2rem; margin: 20px 0;"><i class="fa fa-store"></i></div>
                    <h3 class="card-title" style="font-size: 1.5rem; margin-bottom: 15px;">Tienda Biblioteca</h3>
                    <p class="card-desc" style="color: #666; line-height: 1.6;">Accede a nuestra colección curada de experiencias inmersivas.</p>
                </a>

                <a href="{{ route('libros.tipo', 'ar') }}" class="card" style="background: white; padding: 40px; border-radius: 24px; text-decoration: none; color: inherit; display: block;">
                    <span class="card-number" style="color: var(--accent); font-weight: 800;">02</span>
                    <div class="card-icon" style="font-size: 2rem; margin: 20px 0;"><i class="fa fa-book-open"></i></div>
                    <h3 class="card-title" style="font-size: 1.5rem; margin-bottom: 15px;">Libros AR</h3>
                    <p class="card-desc" style="color: #666;">Libros que despiertan con realidad aumentada.</p>
                </a>

                <a href="{{ route('libros.tipo', 'vr') }}" class="card" style="background: white; padding: 40px; border-radius: 24px; text-decoration: none; color: inherit; display: block;">
                    <span class="card-number" style="color: var(--accent); font-weight: 800;">03</span>
                    <div class="card-icon" style="font-size: 2rem; margin: 20px 0;"><i class="fa fa-vr-cardboard"></i></div>
                    <h3 class="card-title" style="font-size: 1.5rem; margin-bottom: 15px;">Libros VR</h3>
                    <p class="card-desc" style="color: #666;">Narrativas inmersivas en realidad virtual.</p>
                </a>

                <a href="{{ route('storebook') }}" class="card" style="background: white; padding: 40px; border-radius: 24px; text-decoration: none; color: inherit; display: block;">
                    <span class="card-number" style="color: var(--accent); font-weight: 800;">04</span>
                    <div class="card-icon" style="font-size: 2rem; margin: 20px 0;"><i class="fa fa-compass"></i></div>
                    <h3 class="card-title" style="font-size: 1.5rem; margin-bottom: 15px;">Explorar</h3>
                    <p class="card-desc" style="color: #666;">Descubre contenido nuevo y expansivo.</p>
                </a>
            </div>
        </section>

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

        <footer class="footer" id="contacto" style="padding: 60px 10%; background: #000; color: white; text-align: center;">
            <div class="footer-logo" style="font-size: 2rem; font-weight: 800; letter-spacing: 5px; margin-bottom: 20px;">INMERSIA</div>
            <p class="footer-copy" style="color: #444; font-size: 0.9rem;">© 2026 IMJCREA. Todos los derechos reservados.</p>
            <div class="social-icons" style="margin-top: 30px; display: flex; justify-content: center; gap: 20px;">
                <a href="#" style="color: #333;"><i class="fab fa-facebook-f"></i></a>
                <a href="#" style="color: #333;"><i class="fab fa-instagram"></i></a>
                <a href="#" style="color: #333;"><i class="fab fa-twitter"></i></a>
            </div>
        </footer>
    </div>

    <script type="module">
        // --- 1. THREE.JS BACKGROUND ---
        const canvas = document.getElementById('bg-canvas');
        const renderer = new THREE.WebGLRenderer({ canvas, antialias: true });
        renderer.setSize(window.innerWidth, window.innerHeight);

        const scene = new THREE.Scene();
        const geometry = new THREE.PlaneGeometry(2, 2);
        const material = new THREE.ShaderMaterial({
            uniforms: { uTime: { value: 0 } },
            vertexShader: `varying vec2 vUv; void main() { vUv = uv; gl_Position = vec4(position, 1.0); }`,
            fragmentShader: `
                varying vec2 vUv; uniform float uTime;
                void main() {
                    vec2 uv = vUv;
                    float t = uTime * 0.2;
                    // Efecto de flujo oscuro con toques de naranja quemado
                    float wave = sin(uv.x * 3.0 + t) * Math.cos(uv.y * 2.0 - t * 0.5);
                    vec3 color1 = vec3(0.01, 0.01, 0.01); // Negro
                    vec3 color2 = vec3(0.15, 0.06, 0.0);  // Naranja oscuro
                    vec3 finalColor = mix(color1, color2, wave * 0.5 + 0.5);
                    gl_FragColor = vec4(finalColor, 1.0);
                }
            `
        });
        const mesh = new THREE.Mesh(geometry, material);
        scene.add(mesh);

        function anim(t) {
            material.uniforms.uTime.value = t * 0.001;
            renderer.render(scene, new THREE.Camera());
            requestAnimationFrame(anim);
        }
        anim(0);

        window.addEventListener('resize', () => renderer.setSize(window.innerWidth, window.innerHeight));

        // --- 2. GSAP ANIMATIONS ---
        gsap.registerPlugin(ScrollTrigger);
        const tl = gsap.timeline({ defaults: { ease: "power4.out", duration: 1.2 }});

        tl.to(".hero-badge", { opacity: 1, y: 0 })
          .to(".hero-eyebrow", { opacity: 1, x: 0 }, "-=0.8")
          .to(".line-1, .line-2", { opacity: 1, y: 0, stagger: 0.2 }, "-=1")
          .to(".hero-subtitle", { opacity: 1, y: 0 }, "-=0.8")
          .to(".hero-actions", { opacity: 1, y: 0 }, "-=0.8");

        // Scroll Trigger for Cards
        gsap.to(".card", {
            scrollTrigger: {
                trigger: ".cards-grid",
                start: "top 85%",
            },
            opacity: 1,
            y: -20,
            stagger: 0.2,
            duration: 1,
            ease: "power2.out"
        });

        // Scroll Trigger for Stats
        gsap.to(".stat-item", {
            scrollTrigger: {
                trigger: ".stats-band",
                start: "top 90%",
            },
            opacity: 1,
            scale: 1,
            stagger: 0.2
        });

        // --- 3. DECRYPT EFFECT ---
        const titleLines = document.querySelectorAll('.line-1, .line-2');
        const letters = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789#%&*";

        titleLines.forEach(line => {
            const originalText = line.innerText;
            line.addEventListener("mouseover", () => {
                let iter = 0;
                const interval = setInterval(() => {
                    line.innerText = originalText.split("").map((char, index) => {
                        if(index < iter) return originalText[index];
                        return letters[Math.floor(Math.random() * letters.length)];
                    }).join("");
                    if(iter >= originalText.length) clearInterval(interval);
                    iter += 1/3;
                }, 30);
            });
        });
    </script>
</body>
</html>