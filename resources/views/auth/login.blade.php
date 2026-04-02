<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Iniciar Sesión - {{ config('app.name', 'IMJCREA') }}</title>
<script src="https://apis.google.com/js/platform.js" async defer></script>
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
                <a href="/" class="btn-animated text-white-btn">
    Volver al inicio
</a>
            </div>

            @if($errors->any())
    <p class="login-error">
        {{ $errors->first('mensaje') ?? 'Usuario o contraseña incorrectos' }}
    </p>
@endif

            <form action="{{ route('login.procesar', [], false) }}" method="POST">
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
      <div class="google-login">
        <p>O inicia sesión con Google</p>
      <a href="{{ route('login.google', [], false) }}" class="btn-google">
        <img src="https://developers.google.com/identity/images/g-logo.png"  class="google-img"alt="Google">
        Continuar con Google
    </a>
    </div>      
            <div class="button-container">
    <a href="http://127.0.0.1:8000/register" class="btn-animated">
        Registrarse
    </a>
</div>
        </div>
    </div>
</body>
</html>
