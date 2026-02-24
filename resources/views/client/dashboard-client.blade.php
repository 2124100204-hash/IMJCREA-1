<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IMJCREA - Panel Cliente</title>
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/postprocessing@6.22.4/build/postprocessing.min.js"></script>

    <style>
        body, html { margin: 0; padding: 0; height: 100%; width: 100%; overflow: hidden; background: #000; font-family: 'Segoe UI', sans-serif; }
        
        #canvas-bg { position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: 1; }

        .header-panel {
            position: fixed; top: 0; left: 0; width: 100%; height: 45px;
            background-color: #7389D9; 
            display: flex; align-items: center; justify-content: center;
            color: white; font-weight: bold; font-size: 1rem;
            z-index: 20; box-shadow: 0 2px 10px rgba(0,0,0,0.4);
        }

        .main-container {
            position: relative; z-index: 10; height: 100vh;
            display: flex; align-items: center; justify-content: center;
            pointer-events: none;
        }

        .user-card {
            background: rgba(13, 13, 18, 0.92);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 15px; 
            padding: 30px; 
            width: 380px;  
            color: white; 
            pointer-events: auto;
            box-shadow: 0 20px 40px rgba(0,0,0,0.6);
            text-align: left;
        }

        .user-card h1 { margin: 0; font-size: 1.6rem; font-weight: 600; }
        .user-card .cyan-text { color: #00BCD4; }
        .user-card p { color: #aaaaaa; margin: 15px 0 25px 0; line-height: 1.4; font-size: 0.95rem; }
        
        .divider { height: 1px; background: rgba(255,255,255,0.1); margin-bottom: 25px; }

        /* --- BOTONES ANIMADOS --- */
        .btn-animated {
            position: relative;
            display: inline-block;
            padding: 2px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            text-decoration: none;
            overflow: hidden;
            border: none;
            cursor: pointer;
            transition: transform 0.3s ease;
        }

        .btn-animated:hover { transform: scale(1.02); }

        .btn-content {
            position: relative;
            z-index: 2;
            padding: 10px 22px;
            border-radius: 8px;
            display: block;
            font-weight: bold;
            font-size: 0.9rem;
            color: white;
            text-align: center;
        }

        .bg-red { background: #F04747; }
        .bg-cyan { background: #1a1a1a; color: #00BCD4 !important; border: 1px solid rgba(0, 188, 212, 0.3); }

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

        @keyframes rotate {
            100% { transform: translate(-50%, -50%) rotate(360deg); }
        }

    </style>
</head>
<body>

    <div class="header-panel">IMJCREA - Panel Cliente</div>

    <div id="canvas-bg"></div>

    <div class="main-container">
        <div class="user-card">
            <h1>Bienvenido, <span class="cyan-text">{{ session('usuario')->codigo ?? 'pamelachu' }}</span></h1>
            <p>Tu sesión está activa. Explora tus pedidos y servicios con la velocidad de la luz.</p>
            
            <div class="divider"></div>

            <form action="{{ route('logout') }}" method="POST" style="margin-bottom: 20px;">
                @csrf
                <button type="submit" class="btn-animated">
                    <span class="btn-content bg-red">Cerrar sesión</span>
                </button>
            </form>

            <a href="#" class="btn-animated" style="margin-top: 5px;">
                <span class="btn-content bg-cyan">← Volver al inicio</span>
            </a>
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
