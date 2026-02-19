<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Admin - {{ config('app.name', 'IMJCREA') }}</title>
    <link rel="stylesheet" href="{{ asset('css/employer.css') }}">
    <link rel="stylesheet" href="{{ asset('css/default.css') }}">
</head>

<body>

<div class="dashboard-container">

    <div class="dashboard-header">
        <h1>Panel de Administración</h1>
        <p>Bienvenido, {{ session('usuario')->codigo }}</p>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="cancel-btn">
                Cerrar Sesión
            </button>
        </form>
    </div>

    <div class="dashboard-content">

        {{-- MENSAJES --}}
        @if(session('success'))
            <p style="color:green;">{{ session('success') }}</p>
        @endif

        {{-- FORMULARIO CREAR EMPLEADO --}}
        <h2>Agregar Empleado</h2>

        <form method="POST" action="{{ route('admin.empleado.crear') }}">
            @csrf

            <input type="text" name="codigo" placeholder="Código del empleado" required>
            <input type="password" name="password" placeholder="Contraseña" required>

            <button type="submit">Crear Empleado</button>
        </form>

        <hr style="margin:30px 0;">

        {{-- LISTA DE EMPLEADOS --}}
        <h2>Lista de Empleados</h2>

        @php
            $empleados = \App\Models\Usuario::where('tipo_usuario', 'empleado')->get();
        @endphp

        @if($empleados->count() > 0)

            <table border="1" cellpadding="10" width="100%">
                <tr>
                    <th>ID</th>
                    <th>Código</th>
                    <th>Acción</th>
                </tr>

                @foreach($empleados as $empleado)
                    <tr>
                        <td>{{ $empleado->id }}</td>
                        <td>{{ $empleado->codigo }}</td>
                        <td>
                            <form method="POST" action="{{ route('admin.empleado.eliminar', $empleado->id) }}">
                                @csrf
                                <button type="submit" class="cancel-btn" >>
                                    Eliminar
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </table>

        @else
            <p>No hay empleados registrados.</p>
        @endif

    </div>

</div>

</body>
</html>
