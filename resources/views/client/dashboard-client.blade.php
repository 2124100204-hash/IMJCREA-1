<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard Cliente - {{ config('app.name', 'IMJCREA') }}</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body style="margin:0; font-family: Arial, sans-serif; background:#f4f6f9;">

    <div style="background:#667eea; padding:20px; color:white;">
        <h2>IMJCREA - Panel Cliente</h2>
    </div>

    <div style="padding:40px;">
        @if(session()->has('usuario'))
            <h1>Bienvenido, {{ session('usuario')->codigo }}</h1>
        @else
            <script>
                window.location = "{{ route('login') }}";
            </script>
        @endif

        <hr style="margin:30px 0;">

        <p>Aquí podrás ver tus pedidos, servicios o información personal.</p>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" style="
                padding:10px 20px;
                background:#e53e3e;
                color:white;
                border:none;
                border-radius:5px;
                cursor:pointer;
            ">
                Cerrar sesión
            </button>
        </form>

        <br>

        <a href="/" style="color:#667eea;">← Volver al inicio</a>
    </div>

</body>
</html>
