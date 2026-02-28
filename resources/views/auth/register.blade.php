<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Registrarse - {{ config('app.name', 'IMJCREA') }}</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
        <link rel="stylesheet" href="{{ asset('css/default.css') }}">
        <link rel="stylesheet" href="{{ asset('css/auth.css') }}">

        <script type="importmap">
        {
            "imports": {
                "three": "https://unpkg.com/three@0.160.0/build/three.module.js",
                "three/examples/jsm/environments/RoomEnvironment.js": "https://unpkg.com/three@0.160.0/examples/jsm/environments/RoomEnvironment.js"
            }
        }
        </script>

        <style>
            body {
                margin: 0;
                padding: 0;
                background-color: #000000;
                overflow: hidden; 
            }

            #ballpit-canvas {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                z-index: 0;
            }

            .register-container {
                position: relative;
                z-index: 1;
                display: flex;
                justify-content: center;
                align-items: center;
                min-height: 100vh;
                background: transparent;
                padding: 20px;
            }

            .register-box {
                max-width: 440px; 
                width: 100%;
                padding: 25px 40px;
                background: white;
                border-radius: 24px;
                box-shadow: 0 15px 35px rgba(0,0,0,0.5);
                animation: fadeUp 0.8s ease forwards;
            }

            .register-box h1 {
                margin-top: 0;
                margin-bottom: 15px;
                font-size: 26px;
                text-align: center;
                color: #1a1a1a;
            }

            .form-group {
                margin-bottom: 10px;
            }

            .form-group label {
                font-size: 12px;
                font-weight: 600;
                margin-bottom: 4px;
                display: block;
                color: #555;
                text-transform: uppercase;
            }

            .form-group input {
                width: 100%;
                padding: 10px;
                border: 1px solid #ddd;
                border-radius: 8px;
                box-sizing: border-box;
            }

            .animated-button {
                position: relative;
                display: flex;
                align-items: center;
                justify-content: center;
                gap: 4px;
                padding: 14px 36px;
                border: 4px solid transparent;
                font-size: 16px;
                background-color: #1a1a1a;
                border-radius: 100px;
                font-weight: 600;
                color: greenyellow;
                box-shadow: 0 0 0 2px greenyellow;
                cursor: pointer;
                overflow: hidden;
                transition: all 0.6s cubic-bezier(0.23, 1, 0.32, 1);
                width: 100%;
                margin-top: 10px;
                outline: none;
            }

            .animated-button svg {
                position: absolute;
                width: 22px;
                fill: greenyellow;
                z-index: 9;
                transition: all 0.8s cubic-bezier(0.23, 1, 0.32, 1);
            }

            .animated-button .arr-1 { right: 16px; }
            .animated-button .arr-2 { left: -25%; }

            .animated-button .circle {
                position: absolute;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                width: 20px;
                height: 20px;
                background-color: greenyellow;
                border-radius: 50%;
                opacity: 0;
                transition: all 0.8s cubic-bezier(0.23, 1, 0.32, 1);
            }

            .animated-button .text {
                position: relative;
                z-index: 1;
                transform: translateX(-12px);
                transition: all 0.8s cubic-bezier(0.23, 1, 0.32, 1);
            }

            .animated-button:hover {
                box-shadow: 0 0 0 12px transparent;
                color: #212121;
                border-radius: 12px;
            }

            .animated-button:hover .arr-1 { right: -25%; }
            .animated-button:hover .arr-2 { left: 16px; }
            .animated-button:hover .text { transform: translateX(12px); }
            .animated-button:hover svg { fill: #212121; }
            .animated-button:active { scale: 0.95; box-shadow: 0 0 0 4px greenyellow; }
            .animated-button:hover .circle { width: 500px; height: 500px; opacity: 1; }

            .secondary-btn {
                background-color: transparent;
                color: #009688;
                box-shadow: 0 0 0 2px #009688;
                margin-top: 15px;
            }
            .secondary-btn .circle { background-color: #009688; }
            .secondary-btn svg { fill: #009688; }
            .secondary-btn:hover { color: white; }
            .secondary-btn:hover svg { fill: white; }

            @keyframes fadeUp {
                from { opacity: 0; transform: translateY(30px); }
                to { opacity: 1; transform: translateY(0); }
            }
        </style>
    </head>
    <body>
        <canvas id="ballpit-canvas"></canvas>

        <div class="register-container">
            <div class="register-box">
                <h1>Crear Cuenta</h1>
                
                <form action="{{ route('registrar') }}" method="POST">
                    @csrf
                    
                    <div class="form-group">
                        <label for="name">Nombre Completo</label>
                        <input type="text" id="name" name="name" value="{{ old('name') }}" required placeholder="Tu nombre">
                        @error('name') <small style="color: #ff4d4d;">{{ $message }}</small> @enderror
                    </div>

                    <div class="form-group">
                        <label for="email">Correo Electrónico</label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" required placeholder="tu@email.com">
                        @error('email') <small style="color: #ff4d4d;">{{ $message }}</small> @enderror
                    </div>

                    <div class="form-group">
                        <label for="user_code">Código de Usuario</label>
                        <input type="text" id="user_code" name="user_code" value="{{ old('user_code') }}" required placeholder="Tu código">
                        @error('user_code') <small style="color: #ff4d4d;">{{ $message }}</small> @enderror
                    </div>

                    <div class="form-group">
                        <label for="password">Contraseña</label>
                        <input type="password" id="password" name="password" required placeholder="••••••••">
                        @error('password') <small style="color: #ff4d4d;">{{ $message }}</small> @enderror
                    </div>

                    <div class="form-group">
                        <label for="password_confirmation">Confirmar Contraseña</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" required placeholder="••••••••">
                    </div>

                    <button type="submit" class="animated-button">
                        <svg viewBox="0 0 24 24" class="arr-2" xmlns="http://www.w3.org/2000/svg"><path d="M16.172 11l-5.364-5.364 1.414-1.414L20 12l-7.778 7.778-1.414-1.414L16.172 13H4v-2z"></path></svg>
                        <span class="text">Registrarse</span>
                        <span class="circle"></span>
                        <svg viewBox="0 0 24 24" class="arr-1" xmlns="http://www.w3.org/2000/svg"><path d="M16.172 11l-5.364-5.364 1.414-1.414L20 12l-7.778 7.778-1.414-1.414L16.172 13H4v-2z"></path></svg>
                    </button>
                </form>

                <button class="animated-button secondary-btn" onclick="window.location.href='{{ route('mostrarLogin') }}'">
                    <svg viewBox="0 0 24 24" class="arr-2" xmlns="http://www.w3.org/2000/svg"><path d="M16.172 11l-5.364-5.364 1.414-1.414L20 12l-7.778 7.778-1.414-1.414L16.172 13H4v-2z"></path></svg>
                    <span class="text">Iniciar sesión</span>
                    <span class="circle"></span>
                    <svg viewBox="0 0 24 24" class="arr-1" xmlns="http://www.w3.org/2000/svg"><path d="M16.172 11l-5.364-5.364 1.414-1.414L20 12l-7.778 7.778-1.414-1.414L16.172 13H4v-2z"></path></svg>
                </button>

                <div class="back-link" style="margin-top: 15px; text-align: center;">
                    <a href="{{ route('inicio') }}" style="text-decoration: none; color: #666; font-size: 14px;">← Volver al inicio</a>
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
