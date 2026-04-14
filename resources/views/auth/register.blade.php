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

             

                {{-- Errores de validación --}}
                @if($errors->any())
                    <div class="alert-error">
                        <ul>
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('register') }}" method="POST">
                    @csrf

                    <div class="form-group">
                        <label for="name">Nombre Completo</label>
                        <input type="text" id="name" name="name" 
                               value="{{ old('name') }}"
                               required placeholder="Tu nombre">
                    </div>

                    <div class="form-group">
                        <label for="email">Correo Electrónico</label>
                        <input type="email" id="email" name="email" 
                               value="{{ old('email') }}"
                               required placeholder="tu@email.com">
                    </div>

                    <div class="form-group">
                        <label for="password">Contraseña</label>
                        <input type="password" id="password" name="password" 
                               required placeholder="Mínimo 8 caracteres">
                    </div>

                    <div class="form-group">
                        <label for="password_confirmation">Confirmar Contraseña</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" 
                               required placeholder="Repite tu contraseña">
                    </div>

                    <button type="submit" class="login-btn">Registrarse</button>
                </form>

                <div class="button-container">
                    <button class="accept-btn" onclick="window.location.href='{{ route('login') }}'">
                        Iniciar sesión
                    </button>
                </div>

                <div class="back-link">
                    <a href="/">← Volver al inicio</a>
                </div>
            </div>
        </div>
        <!-- Modal de confirmación -->
<div id="modal-confirmacion" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.5); z-index:999; justify-content:center; align-items:center;">
    <div style="background:#fff; border-radius:12px; padding:2rem; max-width:400px; width:90%; text-align:center; box-shadow: 0 10px 30px rgba(0,0,0,0.2);">
        <h2 style="margin-bottom:0.5rem;">✅ ¡Registro exitoso!</h2>
        <p style="color:#555; margin-bottom:1.5rem;">Tu cuenta ha sido creada correctamente. Ahora puedes iniciar sesión.</p>
        <button onclick="window.location.href='{{ route('login') }}'" 
                style="background:#4f46e5; color:#fff; border:none; padding:0.75rem 2rem; border-radius:8px; font-size:1rem; cursor:pointer;">
            Ir a iniciar sesión
        </button>
    </div>
</div>

<script>
    @if(session('success'))
        document.addEventListener('DOMContentLoaded', function() {
            const modal = document.getElementById('modal-confirmacion');
            modal.style.display = 'flex';
        });
    @endif
</script>
    </body>
</html>