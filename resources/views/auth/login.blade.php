<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Iniciar Sesión - {{ config('app.name', 'IMJCREA') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

    <link rel="stylesheet" href="{{ asset('css/default.css') }}">
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
</head>

<body>
    <div class="login-container">
        <div class="login-box">
            <h1>Iniciar Sesión</h1>
            <div class="back-link">
                <a href="/">← Volver al inicio</a>
            </div>

            @if($errors->any())
                <p style="color:red;">{{ $errors->first('mensaje') ?? 'Error en la autenticación' }}</p>
            @endif

            <form action="{{ route('login.procesar') }}" method="POST">
                @csrf
                
                <div class="form-group">
                    <label for="username">Usuario</label>
                    <input type="text" id="username" name="username" required placeholder="admin">
                </div>

                <div class="form-group">
                    <label for="password">Contraseña</label>
                    <input type="password" id="password" name="password" required placeholder="•••••••">
                </div>

                <div class="button-container">
                    <button type="submit" class="accept-btn">
                        Iniciar Sesión
                    </button>
                </div>
            </form>

            <div class="button-container">
                <p style="text-align: center; margin: 15px 0; font-size: 12px; color: #666;">
                    Cuentas de prueba disponibles
                </p>
            </div>
        </div>
    </div>
</body>
</html>
