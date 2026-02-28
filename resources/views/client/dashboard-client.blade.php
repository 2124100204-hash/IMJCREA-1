<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard Cliente - {{ config('app.name', 'IMJCREA') }}</title>

    <link rel="stylesheet" href="{{ asset('css/client.css') }}">
    <link rel="stylesheet" href="{{ asset('css/default.css') }}">
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/postprocessing@6.22.4/build/postprocessing.min.js"></script>
    <script src="{{ asset('js/client.js') }}" defer></script>

    <style>
        /* Fondo animado */
        #canvas-bg { position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: -1; background: #000; }
        
        /* Asegurar que el contenido sea legible sobre la animación */
        body { margin: 0; background: transparent; }
        .dashboard-container, .hero, .section { position: relative; z-index: 10; }
        
        /* Estilo para los botones con borde animado */
        .btn-animated {
            position: relative;
            display: inline-flex;
            padding: 2px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            text-decoration: none;
            overflow: hidden;
            border: none;
            cursor: pointer;
            transition: transform 0.3s ease;
            margin: 5px;
        }
        .btn-animated:hover { transform: scale(1.05); }
        .btn-animated::before {
            content: "";
            position: absolute;
            top: 50%; left: 50%;
            width: 200%; height: 200%;
            background: conic-gradient(from 0deg, transparent, #ffffff, transparent 30%);
            animation: rotate 3s linear infinite;
            transform: translate(-50%, -50%);
            z-index: 1;
        }
        .btn-content {
            position: relative;
            z-index: 2;
            padding: 10px 20px;
            border-radius: 8px;
            display: block;
            font-weight: bold;
            color: white;
            width: 100%;
            text-align: center;
        }
        @keyframes rotate { 100% { transform: translate(-50%, -50%) rotate(360deg); } }

        /* Colores específicos para tus botones */
        .bg-accept { background: #28a745; }
        .bg-cancel { background: #dc3545; }
        .navbar { z-index: 100; position: relative; }
    </style>
</head>

<body>

<div id="canvas-bg"></div>

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

    <button class="btn-animated" onclick="openModal('perfilModal')">
        <span class="btn-content bg-accept">Ver Perfil</span>
    </button>
    <button class="btn-animated" onclick="openModal('pedidosModal')">
        <span class="btn-content bg-accept">Ver Pedidos</span>
    </button>
    <button class="btn-animated" onclick="openModal('nuevoPedidoModal')">
        <span class="btn-content bg-accept">Sugerencias</span>
    </button>
    <button class="btn-animated" onclick="openModal('logoutModal')">
        <span class="btn-content bg-cancel">Salir</span>
    </button>
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

<div id="pedidosModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal('pedidosModal')">&times;</span>
        <h3>Mis Pedidos</h3>
        <p>Aquí aparecerán los pedidos del cliente.</p>
    </div>
</div>

<div id="nuevoPedidoModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal('nuevoPedidoModal')">&times;</span>
        <h3>Realizar Pedido</h3>
        <p>Selecciona productos desde la tienda para generar un pedido.</p>
        <a href="{{ route('storebook') }}" class="action-btn btn-primary">Ir a la tienda</a>
    </div>
</div>

<div id="logoutModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal('logoutModal')">&times;</span>
        <h3>¿Cerrar sesión?</h3>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn-animated">
                <span class="btn-content bg-cancel">Sí, salir</span>
            </button>
        </form>
    </div>
</div>

<script>
    const vertexShader = `varying vec2 vUv; void main(){ vUv = uv; gl_Position = vec4(position.xy, 0.0, 1.0); }`;
    const fragmentShader = `
    precision highp float;
    uniform vec3 iResolution;
    uniform float iTime;
    uniform vec3 uLinesColor;
    uniform vec3 uScanColor;
    varying vec2 vUv;

    void mainImage(out vec4 fragColor, in vec2 fragCoord) {
        vec2 p = (2.0 * fragCoord - iResolution.xy) / iResolution.y;
        vec3 ro = vec3(0.0, 0.0, 0.0);
        vec3 rd = normalize(vec3(p, 1.3));
        float minT = 1e20;
        vec2 gridUV;
        float planes[4];
        planes[0] = (-0.5 - ro.y) / rd.y; 
        planes[1] = (0.5 - ro.y) / rd.y;  
        planes[2] = (-1.0 - ro.x) / rd.x; 
        planes[3] = (1.0 - ro.x) / rd.x;  
        for(int i=0; i<4; i++){
            if(planes[i] > 0.0 && planes[i] < minT){
                minT = planes[i];
                vec3 hit = ro + rd * minT;
                gridUV = (i < 2) ? hit.xz : hit.yz;
            }
        }
        vec2 uv = fract(gridUV * 6.0) - 0.5;
        float line = smoothstep(0.47, 0.5, max(abs(uv.x), abs(uv.y)));
        float scanPos = mod(iTime * 0.6, 5.0);
        float pulse = exp(-pow(minT - scanPos, 2.0) / 0.03);
        vec3 color = mix(uLinesColor * line, uScanColor, pulse * line + pulse * 0.5);
        color *= exp(-minT * 0.35);
        fragColor = vec4(color, line + pulse);
    }

    void main(){
        vec4 c; mainImage(c, vUv * iResolution.xy);
        gl_FragColor = c;
    }
    `;

    const scene = new THREE.Scene();
    const camera = new THREE.OrthographicCamera(-1, 1, 1, -1, 0, 1);
    const renderer = new THREE.WebGLRenderer({ antialias: true, alpha: true });
    renderer.setSize(window.innerWidth, window.innerHeight);
    document.getElementById('canvas-bg').appendChild(renderer.domElement);

    const uniforms = {
        iTime: { value: 0 },
        iResolution: { value: new THREE.Vector3(window.innerWidth, window.innerHeight, 1) },
        uLinesColor: { value: new THREE.Color("#4a148c") },
        uScanColor: { value: new THREE.Color("#FF9FFC") }
    };

    const material = new THREE.ShaderMaterial({ uniforms, vertexShader, fragmentShader, transparent: true });
    scene.add(new THREE.Mesh(new THREE.PlaneGeometry(2, 2), material));

    const composer = new POSTPROCESSING.EffectComposer(renderer);
    composer.addPass(new POSTPROCESSING.RenderPass(scene, camera));
    composer.addPass(new POSTPROCESSING.EffectPass(camera, new POSTPROCESSING.BloomEffect({ 
        intensity: 2.2, 
        luminanceThreshold: 0.1 
    })));

    function animate(t) {
        uniforms.iTime.value = t * 0.001;
        composer.render();
        requestAnimationFrame(animate);
    }
    requestAnimationFrame(animate);

    window.addEventListener('resize', () => {
        renderer.setSize(window.innerWidth, window.innerHeight);
        uniforms.iResolution.value.set(window.innerWidth, window.innerHeight, 1);
        composer.setSize(window.innerWidth, window.innerHeight);
    });
</script>

</body>
</html>