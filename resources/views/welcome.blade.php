<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'IMJCREA') }}</title>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
    
    <link rel="stylesheet" href="{{ asset('css/default.css') }}">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    <style>
        #silk-container {
            position: fixed;
            top: 0; left: 0;
            width: 100%; height: 100%;
            z-index: -1;
            background: #000;
        }
        .wrapper { position: relative; z-index: 1; background: transparent; }
        
        .hero-title { cursor: default; display: flex; flex-direction: column; }
        .line-1 { color: #FFFFFF !important; } 
        .line-2 { color: #FF8C00 !important; } 
        .cursor { margin-left: 5px; color: #FF8C00; font-weight: bold; }

        .cards-section {
            background-color: #FDFBF7;
            padding: 100px 10%;
            color: #1a1a1a;
        }

        .section-header-line {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 60px;
            color: #B5A89A;
            font-size: 0.75rem;
            letter-spacing: 3px;
        }

        .cards-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 20px;
        }

        .card {
            background: #FFFFFF;
            padding: 40px 30px;
            border-radius: 24px;
            text-decoration: none;
            display: flex;
            flex-direction: column;
            box-shadow: 0 10px 40px rgba(0,0,0,0.02);
            transition: transform 0.3s ease;
        }

        .card:hover { transform: translateY(-5px); }
        .card-num { color: #FF8C00; font-size: 0.7rem; font-weight: 700; margin-bottom: 20px; }
        .card-img { font-size: 40px; margin-bottom: 25px; display: flex; align-items: center; justify-content: flex-start; }
        .card-title { font-size: 1.4rem; font-weight: 700; margin-bottom: 12px; color: #111; font-family: 'serif'; }
        .card-desc { font-size: 0.9rem; line-height: 1.6; color: #666; }

        .custom-footer {
            background-color: #120F0D;
            padding: 80px 10% 40px 10%;
            color: #FF8C00;
            text-align: center;
        }

        .footer-main-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 40px;
            margin-bottom: 80px;
        }

        .footer-item h2 { font-size: 2.2rem; font-weight: 700; margin-bottom: 5px; }
        .footer-item span { color: #888; font-size: 0.65rem; letter-spacing: 2px; text-transform: uppercase; }

        .footer-bottom {
            border-top: 1px solid rgba(255,255,255,0.05);
            padding-top: 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 0.75rem;
            color: #555;
        }

        .footer-brand { color: #FFFFFF; font-weight: 700; letter-spacing: 1px; }
        .social-dots { display: flex; gap: 15px; }
        .dot { width: 35px; height: 35px; border: 1px solid #333; border-radius: 50%; }
    </style>
</head>
<body>

    <div id="silk-container"></div>

    <div class="wrapper">
        <section class="hero" id="inicio">
            <div class="hero-badge">Sistema activo · AR/VR</div>
            <span class="hero-eyebrow">// Inmersión real en un mundo inrreal</span>
            <h1 class="hero-title" id="decrypt-title">
                <span class="line-1">INMERSIA</span>
                <span class="line-2">CREA.</span>
            </h1>
            <div class="hero-subtitle">
                <p id="typed-slogan" style="display: inline; color: #fff;"></p>
                <span id="cursor" class="cursor">|</span>
            </div>
            <div class="hero-actions">
                <a href="#explorar" class="btn-primary" style="background-color: #FF8C00; border-color: #FF8C00;">Explorar ahora</a>
                <a href="/login" class="btn-ghost" style="color: #FF8C00; border-color: #FF8C00;">Iniciar sesión →</a>
            </div>
        </section>

        <section class="cards-section" id="explorar">
            <div class="section-header-line">// NUESTROS SERVICIOS</div>
            <div class="cards-grid"> 
                
                <a href="{{ route('storebook') }}" class="card">
                    <span class="card-num">01</span>
                    <div class="card-img">🏪</div>
                    <h3 class="card-title">Tienda Biblioteca</h3>
                    <p class="card-desc">Accede a nuestra colección curada de experiencias inmersivas.</p>
                </a>

                <a href="{{ route('libros.tipo', 'ar') }}" class="card">
                    <span class="card-num">02</span>
                    <div class="card-img">📖</div>
                    <h3 class="card-title">Libros AR</h3>
                    <p class="card-desc">Libros que despiertan con realidad aumentada.</p>
                </a>

                <a href="{{ route('libros.tipo', 'vr') }}" class="card">
                    <span class="card-num">03</span>
                    <div class="card-img">👓</div>
                    <h3 class="card-title">Libros VR</h3>
                    <p class="card-desc">Narrativas inmersivas en realidad virtual.</p>
                </a>

                <a href="{{ route('storebook') }}" class="card">
                    <span class="card-num">04</span>
                    <div class="card-img">🧭</div>
                    <h3 class="card-title">Explorar</h3>
                    <p class="card-desc">Descubre contenido nuevo y expansivo.</p>
                </a>
            </div>
        </section>

        <footer class="custom-footer">
            <div class="footer-main-grid">
                <div class="footer-item">
                    <h2>Experiencias</h2>
                    <span>Inimaginables</span>
                </div>
                <div class="footer-item">
                    <h2>AR</h2>
                    <span>Realidad Aumentada</span>
                </div>
                <div class="footer-item">
                    <h2>VR</h2>
                    <span>Realidad Virtual</span>
                </div>
                <div class="footer-item">
                    <h2>∞</h2>
                    <span>Creatividad</span>
                </div>
            </div>
            
            <div class="footer-bottom">
                <div class="footer-brand">INMERSIA</div>
                <div>© 2026 IMJCREA. Todos los derechos reservados.</div>
                <div class="social-dots">
                    <div class="dot"></div>
                    <div class="dot"></div>
                    <div class="dot"></div>
                </div>
            </div>
        </footer>
    </div>

    <script type="module">
        import * as THREE from 'https://unpkg.com/three@0.160.0/build/three.module.js';

        const titleEl = document.getElementById('decrypt-title');
        const originalText = "INMERSIA CREA.";
        const letters = "ABCDEFGHIJKLMNOPQRSTUVWXYZ!@#$%^&*()_+";
        
        titleEl.addEventListener("mouseover", () => {
            let iter = 0;
            const inv = setInterval(() => {
                const currentStatus = originalText.split("").map((l, i) => {
                    if(i < iter) return originalText[i];
                    return letters[Math.floor(Math.random() * letters.length)];
                }).join("");
                titleEl.innerHTML = `<span class="line-1">${currentStatus.substring(0, 8)}</span><span class="line-2">${currentStatus.substring(8)}</span>`;
                if(iter >= originalText.length) {
                    clearInterval(inv);
                    titleEl.innerHTML = `<span class="line-1">INMERSIA</span><span class="line-2">CREA.</span>`;
                }
                iter += 1/3;
            }, 30);
        });

        const sloganEl = document.getElementById('typed-slogan');
        const phrases = ["Explora mundos de conocimiento a través de la realidad aumentada y virtual. Libros que cobran vida, experiencias que trascienden la pantalla."];
        let charIndex = 0;

        function typeSlogan() {
            sloganEl.textContent = phrases[0].substring(0, charIndex + 1);
            charIndex++;
            if (charIndex < phrases[0].length) setTimeout(typeSlogan, 40);
        }
        typeSlogan();
        gsap.to("#cursor", { opacity: 0, repeat: -1, yoyo: true, duration: 0.5 });

        const vertex = `varying vec2 vUv; void main() { vUv = uv; gl_Position = vec4(position, 1.0); }`;
        const fragment = `
            varying vec2 vUv; uniform float uTime;
            void main() {
                vec2 uv = vUv; float t = uTime * 0.15;
                float pattern = 0.6 + 0.4 * sin(4.0 * (uv.x + uv.y + cos(2.5 * uv.x + 4.0 * uv.y) + t));
                gl_FragColor = vec4(vec3(0.05, 0.1, 0.6) * pattern, 1.0);
            }
        `;
        const renderer = new THREE.WebGLRenderer({ antialias: true });
        renderer.setSize(window.innerWidth, window.innerHeight);
        document.getElementById('silk-container').appendChild(renderer.domElement);
        const scene = new THREE.Scene();
        const mesh = new THREE.Mesh(new THREE.PlaneGeometry(2, 2), new THREE.ShaderMaterial({ uniforms: { uTime: { value: 0 } }, vertexShader: vertex, fragmentShader: fragment }));
        scene.add(mesh);
        function animate(t) { 
            mesh.material.uniforms.uTime.value = t * 0.001; 
            renderer.render(scene, new THREE.Camera()); 
            requestAnimationFrame(animate); 
        }
        animate(0);
    </script>
</body>
</html>
