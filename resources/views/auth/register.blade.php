<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Registrarse - {{ config('app.name', 'IMJCREA') }}</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
        <link rel="stylesheet" href="{{ asset('css/app.css') }}">
        <style>
            .register-container {
                width: 100%;
                min-height: 100vh;
                display: flex;
                justify-content: center;
                align-items: center;
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                font-family: 'Instrument Sans', sans-serif;
                padding: 20px;
            }

            .register-box {
                background: white;
                padding: 40px;
                border-radius: 10px;
                box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
                width: 100%;
                max-width: 400px;
            }

            .register-box h1 {
                text-align: center;
                margin-bottom: 30px;
                color: #333;
                font-size: 28px;
            }

            .form-group {
                margin-bottom: 20px;
            }

            .form-group label {
                display: block;
                margin-bottom: 8px;
                color: #333;
                font-weight: 500;
            }

            .form-group input {
                width: 100%;
                padding: 12px;
                border: 1px solid #ddd;
                border-radius: 5px;
                font-size: 14px;
                font-family: 'Instrument Sans', sans-serif;
                transition: border-color 0.3s;
            }

            .form-group input:focus {
                outline: none;
                border-color: #667eea;
                box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
            }

            .register-btn {
                width: 100%;
                padding: 12px;
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                color: white;
                border: none;
                border-radius: 5px;
                font-size: 16px;
                font-weight: 600;
                cursor: pointer;
                transition: transform 0.2s;
            }

            .register-btn:hover {
                transform: translateY(-2px);
            }

            .register-link {
                text-align: center;
                margin-top: 20px;
                font-size: 14px;
            }

            .register-link a {
                color: #667eea;
                text-decoration: none;
                font-weight: 500;
            }

            .back-link {
                text-align: center;
                margin-top: 20px;
            }

            .back-link a {
                color: #667eea;
                text-decoration: none;
                font-weight: 500;
            }
        </style>
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
    </body>
</html>
