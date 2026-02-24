<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Registrarse - {{ config('app.name', 'IMJCREA') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

    <script type="importmap">
    {
        "imports": {
            "three": "https://unpkg.com/three@0.160.0/build/three.module.js",
            "three/examples/jsm/environments/RoomEnvironment.js": "https://unpkg.com/three@0.160.0/examples/jsm/environments/RoomEnvironment.js"
        }
    }
    </script>

    <style>
        /* 🔥 Fondo negro */
        body {
            margin: 0;
            padding: 0;
            background-color: #000000;
            overflow: hidden;
            font-family: 'Instrument Sans', sans-serif;
        }

        /* Canvas */
        #ballpit-canvas {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 0;
        }

        /* Contenedor */
        .register-container {
            position: relative;
            z-index: 1;
            width: 100%;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        /* 🔥 Cuadro MÁS pequeño */
        .register-box {
            background: white;
            padding: 30px 30px;
            border-radius: 12px;
            width: 100%;
            max-width: 340px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.6);
            animation: fadeUp 0.8s ease forwards;
            opacity: 0;
            transform: translateY(40px);
        }

        .register-box h1 {
            text-align: center;
            margin-bottom: 25px;
            color: #333;
            font-size: 22px;
        }

        .form-group {
            margin-bottom: 16px;
        }

        .form-group label {
            display: block;
            margin-bottom: 6px;
            color: #444;
            font-size: 13px;
            font-weight: 500;
        }

        .form-group input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 13px;
            transition: all 0.3s ease;
        }

        .form-group input:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102,126,234,0.2);
            transform: scale(1.02);
        }

        .register-btn {
            width: 100%;
            padding: 11px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .register-btn:hover {
            transform: translateY(-3px) scale(1.03);
            box-shadow: 0 10px 20px rgba(102,126,234,0.4);
        }

        .register-link, .back-link {
            text-align: center;
            margin-top: 14px;
            font-size: 13px;
        }

        .register-link a, .back-link a {
            color: #667eea;
            text-decoration: none;
            font-weight: 500;
            transition: 0.3s ease;
        }

        .register-link a:hover, .back-link a:hover {
            color: #764ba2;
        }

        .error-msg {
            color: #e53e3e;
            font-size: 12px;
            margin-bottom: 15px;
            text-align: center;
        }

        @keyframes fadeUp {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>
<body>

<canvas id="ballpit-canvas"></canvas>

<div class="register-container">
    <div class="register-box">

        <h1>Crear Cuenta</h1>

        @if ($errors->any())
            <div class="error-msg">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('register') }}" method="POST">
            @csrf

            <div class="form-group">
                <label>Nombre Completo</label>
                <input type="text" name="name" required placeholder="Tu nombre">
            </div>

            <div class="form-group">
                <label>Correo Electrónico</label>
                <input type="email" name="email" required placeholder="tu@email.com">
            </div>

            <div class="form-group">
                <label>Código de Usuario</label>
                <input type="text" name="codigo" required placeholder="Tu código">
            </div>

            <div class="form-group">
                <label>Contraseña</label>
                <input type="password" name="password" required placeholder="••••••••">
            </div>

            <div class="form-group">
                <label>Confirmar Contraseña</label>
                <input type="password" name="password_confirmation" required placeholder="••••••••">
            </div>

            <button type="submit" class="register-btn">Registrarse</button>

        </form>

        <div class="register-link">
            ¿Ya tienes cuenta? <a href="{{ route('login') }}">Inicia sesión</a>
        </div>

        <div class="back-link">
            <a href="/">← Volver al inicio</a>
        </div>

    </div>
</div>

<script type="module">
    import * as THREE from 'three';
    import { RoomEnvironment } from 'three/examples/jsm/environments/RoomEnvironment.js';

    const canvas = document.getElementById('ballpit-canvas');
    const scene = new THREE.Scene();
    scene.background = new THREE.Color(0x000000);

    const camera = new THREE.PerspectiveCamera(45, window.innerWidth / window.innerHeight, 0.1, 1000);
    camera.position.z = 18;

    const renderer = new THREE.WebGLRenderer({ canvas, antialias: true });
    renderer.setSize(window.innerWidth, window.innerHeight);
    renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2));

    const pmremGenerator = new THREE.PMREMGenerator(renderer);
    scene.environment = pmremGenerator.fromScene(new RoomEnvironment(), 0.04).texture;

    const spheres = [];
    const geo = new THREE.SphereGeometry(1, 32, 32);
    const colors = [0x667eea, 0x764ba2, 0x9f7aea, 0x4c51bf];

    for (let i = 0; i < 30; i++) {
        const mat = new THREE.MeshPhysicalMaterial({
            color: colors[i % colors.length],
            roughness: 0.05,
            metalness: 0.1,
            clearcoat: 1
        });

        const mesh = new THREE.Mesh(geo, mat);
        mesh.position.set(
            (Math.random() - 0.5) * 20,
            (Math.random() - 0.5) * 15,
            (Math.random() - 0.5) * 5
        );

        mesh.userData.vel = new THREE.Vector3(
            (Math.random() - 0.5) * 0.04,
            (Math.random() - 0.5) * 0.04,
            (Math.random() - 0.5) * 0.04
        );

        scene.add(mesh);
        spheres.push(mesh);
    }

    function animate(){
        requestAnimationFrame(animate);
        spheres.forEach(s => {
            s.position.add(s.userData.vel);
            if(Math.abs(s.position.x) > 12) s.userData.vel.x *= -1;
            if(Math.abs(s.position.y) > 8) s.userData.vel.y *= -1;
        });
        renderer.render(scene, camera);
    }

    animate();

    window.addEventListener('resize', () => {
        camera.aspect = window.innerWidth / window.innerHeight;
        camera.updateProjectionMatrix();
        renderer.setSize(window.innerWidth, window.innerHeight);
    });
</script>

</body>
</html>