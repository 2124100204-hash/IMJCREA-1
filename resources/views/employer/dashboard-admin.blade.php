<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Admin - {{ config('app.name', 'IMJCREA') }}</title>
    <link rel="stylesheet" href="{{ asset('css/default.css') }}">
    <link rel="stylesheet" href="{{ asset('css/employer.css') }}">
</head>

<body>

<div class="dashboard-container">

    <div class="dashboard-header">
        <h1>Panel de Administración</h1>
        <p>Bienvenido, {{ session('usuario_nombre') ?? session('usuario_username') }}</p>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="logout-btn">Cerrar Sesión</button>
        </form>
    </div>

    <div class="dashboard-content">

        @if(session('success'))
            <p class="success-message">✓ {{ session('success') }}</p>
        @endif

        @if(session('error'))
            <p class="error-message">✗ {{ session('error') }}</p>
        @endif

        <h2>Opciones Principales</h2>
        <div class="modal-grid">
            <button onclick="openModal('crearEmpleadoModal')" class="modal-btn">
                <div class="modal-btn-icon">👥</div>
                <div class="modal-btn-title">Crear Empleado</div>
                <div class="modal-btn-desc">Agregar nuevo empleado</div>
            </button>
            
            <button onclick="openModal('empleadosModal')" class="modal-btn">
                <div class="modal-btn-icon">📋</div>
                <div class="modal-btn-title">Ver Empleados</div>
                <div class="modal-btn-desc">Lista de empleados</div>
            </button>

            <button onclick="openModal('librosModal')" class="modal-btn">
                <div class="modal-btn-icon">📚</div>
                <div class="modal-btn-title">Gestión de Libros</div>
                <div class="modal-btn-desc">Administrar libros</div>
            </button>

            <button onclick="openModal('formatosModal')" class="modal-btn">
                <div class="modal-btn-icon">📋</div>
                <div class="modal-btn-title">Gestión de Formatos</div>
                <div class="modal-btn-desc">Configurar formatos</div>
            </button>
        </div>

        <!-- MODAL CREAR EMPLEADO -->
        <div id="crearEmpleadoModal" class="modal">
            <div class="modal-content" onclick="event.stopPropagation()">
                <div class="modal-header">
                    <h2>Crear Nuevo Empleado</h2>
                    <button onclick="closeModal('crearEmpleadoModal')" class="modal-close-btn">×</button>
                </div>

                <form method="POST" action="{{ route('admin.empleado.crear') }}" class="modal-form">
                    @csrf

                    <div class="form-group">
                        <label>Usuario</label>
                        <input type="text" name="username" placeholder="Ej: empleado_01" required>
                    </div>

                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" name="email" placeholder="Ej: empleado@empresa.com" required>
                    </div>

                    <div class="form-group">
                        <label>Nombre Completo</label>
                        <input type="text" name="nombre" placeholder="Ej: Juan Pérez" required>
                    </div>

                    <div class="form-group">
                        <label>Contraseña</label>
                        <input type="password" name="password" placeholder="Mínimo 6 caracteres" required>
                    </div>

                    <div class="modal-actions">
                        <button type="submit" class="btn-primary">Crear Empleado</button>
                        <button type="button" onclick="closeModal('crearEmpleadoModal')" class="btn-secondary">Cancelar</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- MODAL VER EMPLEADOS -->
        <div id="empleadosModal" class="modal">
            <div class="modal-content-large" onclick="event.stopPropagation()">
                <div class="modal-header">
                    <h2>Lista de Empleados</h2>
                    <button onclick="closeModal('empleadosModal')" class="modal-close-btn">×</button>
                </div>

                @php
                    $empleados = \App\Models\Usuario::where('rol', 'empleado')->get();
                @endphp

                @if($empleados->count() > 0)
                    <div class="table-container">
                        <table>
                            <tr>
                                <th>Usuario</th>
                                <th>Nombre</th>
                                <th>Email</th>
                                <th>Acción</th>
                            </tr>

                            @foreach($empleados as $empleado)
                                <tr>
                                    <td>{{ $empleado->username }}</td>
                                    <td>{{ $empleado->nombre }}</td>
                                    <td>{{ $empleado->email }}</td>
                                    <td>
                                        <form method="POST" action="{{ route('admin.empleado.eliminar', $empleado->id) }}" style="display:inline;" onsubmit="return confirm('¿Estás seguro?');">
                                            @csrf
                                            <button type="submit" class="btn-danger">Eliminar</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </table>
                    </div>
                @else
                    <p class="empty-state">No hay empleados registrados</p>
                @endif

                <div class="modal-footer">
                    <button type="button" onclick="closeModal('empleadosModal')" class="btn-close">Cerrar</button>
                </div>
            </div>
        </div>

        <!-- MODAL GESTIÓN DE LIBROS -->
        <div id="librosModal" class="modal">
            <div class="modal-content-large" onclick="event.stopPropagation()">
                <div class="modal-header">
                    <h2>📚 Gestión de Libros</h2>
                    <button onclick="closeModal('librosModal')" class="modal-close-btn">×</button>
                </div>

                <div style="margin-bottom: 30px;">
                    <h3 style="color: #333; margin-bottom: 15px;">Crear Nuevo Libro</h3>
                    <form method="POST" action="{{ route('admin.libro.crear') }}" class="modal-form">
                        @csrf

                        <div class="form-group">
                            <label>Título</label>
                            <input type="text" name="titulo" placeholder="Ej: El Quijote" required>
                        </div>

                        <div class="form-group">
                            <label>Autor</label>
                            <input type="text" name="autor" placeholder="Ej: Miguel de Cervantes" required>
                        </div>

                        <div class="form-group">
                            <label>Descripción</label>
                            <input type="text" name="descripcion" placeholder="Breve descripción del libro">
                        </div>

                        <div class="form-group">
                            <label>Formato</label>
                            <input type="text" name="formato" placeholder="Ej: PDF, EPUB" required>
                        </div>

                        <button type="submit" class="btn-primary">Crear Libro</button>
                    </form>
                </div>

                <hr style="margin: 30px 0; border: none; border-top: 1px solid #eee;">

                <h3 style="color: #333; margin-bottom: 15px;">Lista de Libros</h3>

                @php
                    $libros = \App\Models\Libro::all();
                @endphp

                @if($libros->count() > 0)
                    <div class="table-container">
                        <table>
                            <tr>
                                <th>Título</th>
                                <th>Autor</th>
                                <th>Formato</th>
                                <th>Acciones</th>
                            </tr>

                            @foreach($libros as $libro)
                                <tr>
                                    <td>{{ $libro->titulo }}</td>
                                    <td>{{ $libro->autor }}</td>
                                    <td>{{ $libro->formato }}</td>
                                    <td>
                                        <button onclick="editarLibro({{ $libro->id }}, '{{ $libro->titulo }}', '{{ $libro->autor }}', '{{ $libro->descripcion }}', '{{ $libro->formato }}')" class="btn-secondary" style="padding: 6px 12px; font-size: 12px;">Editar</button>
                                        <form method="POST" action="{{ route('admin.libro.eliminar', $libro->id) }}" style="display:inline;" onsubmit="return confirm('¿Estás seguro?');">
                                            @csrf
                                            <button type="submit" class="btn-danger">Eliminar</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </table>
                    </div>
                @else
                    <p class="empty-state">No hay libros registrados</p>
                @endif

                <div class="modal-footer">
                    <button type="button" onclick="closeModal('librosModal')" class="btn-close">Cerrar</button>
                </div>
            </div>
        </div>

        <!-- MODAL EDITAR LIBRO -->
        <div id="editarLibroModal" class="modal">
            <div class="modal-content" onclick="event.stopPropagation()">
                <div class="modal-header">
                    <h2>Editar Libro</h2>
                    <button onclick="closeModal('editarLibroModal')" class="modal-close-btn">×</button>
                </div>

                <form method="POST" id="editForm" class="modal-form">
                    @csrf

                    <div class="form-group">
                        <label>Título</label>
                        <input type="text" id="edit_titulo" name="titulo" required>
                    </div>

                    <div class="form-group">
                        <label>Autor</label>
                        <input type="text" id="edit_autor" name="autor" required>
                    </div>

                    <div class="form-group">
                        <label>Descripción</label>
                        <input type="text" id="edit_descripcion" name="descripcion">
                    </div>

                    <div class="form-group">
                        <label>Formato</label>
                        <input type="text" id="edit_formato" name="formato" required>
                    </div>

                    <div class="modal-actions">
                        <button type="submit" class="btn-primary">Actualizar Libro</button>
                        <button type="button" onclick="closeModal('editarLibroModal')" class="btn-secondary">Cancelar</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- MODAL GESTIÓN DE FORMATOS -->
        <div id="formatosModal" class="modal">
            <div class="modal-content-large" onclick="event.stopPropagation()">
                <div class="modal-header">
                    <h2>📋 Gestión de Formatos</h2>
                    <button onclick="closeModal('formatosModal')" class="modal-close-btn">×</button>
                </div>

                <div class="modal-body">
                    <p>Aquí podrás configurar los formatos disponibles del sistema.</p>
                    <p style="margin-top: 20px; color: #999; font-size: 14px;">Funcionalidad en desarrollo...</p>
                </div>

                <div class="modal-footer">
                    <button type="button" onclick="closeModal('formatosModal')" class="btn-close">Cerrar</button>
                </div>
            </div>
        </div>

    </div>

</div>

<script src="{{ asset('js/admin-dashboard.js') }}"></script>
<script>
    function editarLibro(id, titulo, autor, descripcion, formato) {
        document.getElementById('edit_titulo').value = titulo;
        document.getElementById('edit_autor').value = autor;
        document.getElementById('edit_descripcion').value = descripcion;
        document.getElementById('edit_formato').value = formato;
        document.getElementById('editForm').action = '/admin/libro/actualizar/' + id;
        openModal('editarLibroModal');
    }
</script>

</body>
</html>
