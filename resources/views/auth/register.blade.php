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
    </head>
    <body>
        <div class="register-container">
            <div class="register-box">
                <h1>Crear Cuenta</h1>
                
                <form action="{{ route('register') }}" method="POST">
                    @csrf
                    
                    <div class="form-group">
                        <label for="name">Nombre Completo</label>
                        <input type="text" id="name" name="name" required placeholder="Tu nombre">
                    </div>

                    <div class="form-group">
                        <label for="email">Correo Electrónico</label>
                        <input type="email" id="email" name="email" required placeholder="tu@email.com">
                    </div>

                    <div class="form-group">
                        <label for="password">Contraseña</label>
                        <input type="password" id="password" name="password" required placeholder="••••••••">
                    </div>

                    <div class="form-group">
                        <label for="password_confirmation">Confirmar Contraseña</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" required placeholder="••••••••">
                    </div>

                    <button type="submit" class="login-btn">Registrarse</button>
                </form>

                    <div class="button-container">
                        <button class="accept-btn" onclick="window.location.href='{{ route('login') }}'">
                             Iniciar sesión
                        </button>

                <div class="back-link">
                    <a href="/">← Volver al inicio</a>
                </div>
            </div>
        </div>
    </body>
</html>
