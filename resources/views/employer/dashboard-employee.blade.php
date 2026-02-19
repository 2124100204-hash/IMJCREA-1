<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Dashboard Empleado - {{ config('app.name', 'IMJCREA') }}</title>
        <link rel="stylesheet" href="{{ asset('css/default.css') }}">
        <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    </head>
    <body>
        <div style="padding:40px; font-family: 'Instrument Sans', sans-serif;">
            <h1>Dashboard - Empleado</h1>
            <p>Bienvenido, empleado. Aquí están las herramientas administrativas.</p>
            <a href="/" style="color:#667eea;">← Volver al inicio</a>
        </div>
    </body>
</html>
