<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Empleado - {{ config('app.name', 'IMJCREA') }}</title>
    <link rel="stylesheet" href="{{ asset('css/default.css') }}">
    <link rel="stylesheet" href="{{ asset('css/employer.css') }}">
</head>

<body>

<div class="dashboard-container">

    <div class="dashboard-header">
        <h1>Panel de Empleado</h1>
        <p>Bienvenido, {{ session('usuario_nombre') ?? session('usuario_username') }}</p>

        <form method="POST" action="{{ route('logout') }}">
    @csrf
    <button type="submit" class="logout-btn button">
        <div class="wrap">
            <p>Cerrar Sesión</p>
        </div>
    </button>
</form>

    <div class="dashboard-content">

        @if(session('success'))
            <p class="success-message">✓ {{ session('success') }}</p>
        @endif

        <h2>Mis Opciones</h2>
        <div class="modal-grid">
        <button onclick="openModal('librosModal')" class="card-neon-outer">
            <div class="card-neon-inner">
                <div class="modal-btn-icon">📚</div>
                <div class="modal-btn-title">Mis Libros</div>
                <div class="modal-btn-desc">Gestionar mis libros</div>
            </div>
        </button>
        
        <button onclick="openModal('perfilModal')" class="card-neon-outer">
            <div class="card-neon-inner">
                <div class="modal-btn-icon">👤</div>
                <div class="modal-btn-title">Mi Perfil</div>
                <div class="modal-btn-desc">Ver información personal</div>
            </div>
        </button>
    </div>
</div>

        <!-- MODAL MIS LIBROS -->
        <div id="librosModal" class="modal">
            <div class="modal-content-large" onclick="event.stopPropagation()">
                <div class="modal-header">
                    <h2>📚 Mis Libros</h2>
                    <button onclick="closeModal('librosModal')" class="modal-close-btn">×</button>
                </div>

                <div style="margin-bottom: 30px;">
                    <h3 style="color: #333; margin-bottom: 15px;">Crear Nuevo Libro</h3>
                    <form method="POST" action="{{ route('empleado.libro.crear') }}" class="modal-form">
                        @csrf

                        <div class="form-group">
                            <label>Título</label>
                            <input type="text" name="titulo" placeholder="Ej: Mi Primer Libro" required>
                        </div>

                        <div class="form-group">
                            <label>Autor</label>
                            <input type="text" name="autor" placeholder="Tu nombre" required>
                        </div>

                        <div class="form-group">
                            <label>Descripción</label>
                            <input type="text" name="descripcion" placeholder="Breve descripción">
                        </div>

                        <div class="form-group">
                            <label>Formato</label>
                            <input type="text" name="formato" placeholder="Ej: PDF, EPUB" required>
                        </div>

                        <button type="submit" class="btn-crear-skew">
    <span>Crear Libro</span>
</button>
                    </form>
                </div>

                <hr style="margin: 30px 0; border: none; border-top: 1px solid #eee;">

                <h3 style="color: #333; margin-bottom: 15px;">Mis Libros Creados</h3>

                @php
                    $misLibros = \App\Models\Libro::all();
                @endphp

                @if($misLibros->count() > 0)
                    <div class="table-container">
                        <table>
                            <tr>
                                <th>Título</th>
                                <th>Autor</th>
                                <th>Formato</th>
                                <th>Acciones</th>
                            </tr>

                            @foreach($misLibros as $libro)
                                <tr>
                                    <td>{{ $libro->titulo }}</td>
                                    <td>{{ $libro->autor }}</td>
                                    <td>{{ $libro->formato }}</td>
                                    <td>
                                        <button onclick="editarLibro({{ $libro->id }}, '{{ $libro->titulo }}', '{{ $libro->autor }}', '{{ $libro->descripcion }}', '{{ $libro->formato }}')" class="btn-3d btn-editar">
  <span class="shadow"></span>
  <span class="edge"></span>
  <span class="front">Editar</span>
</button>
                                        <form method="POST" action="{{ route('empleado.libro.eliminar', $libro->id) }}" style="display:inline;" onsubmit="return confirm('¿Estás seguro?');">
                                            @csrf
                                            <button type="submit" class="btn-3d btn-eliminar">
  <span class="shadow"></span>
  <span class="edge"></span>
  <span class="front">Eliminar</span>
</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </table>
                    </div>
                @else
                    <p class="empty-state">No has creado ningún libro aún</p>
                @endif

                <div class="modal-footer">
                    <button type="button" onclick="closeModal('librosModal')" class="btn-3d btn-cerrar-modal">
  <span class="shadow"></span>
  <span class="edge"></span>
  <span class="front">Cerrar</span>
</button>
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

                    <div class="modal-actions" style="display: flex; gap: 15px; justify-content: center; margin-top: 20px;">
    <button type="submit" class="btn-neo">
        Actualizar Libro
    </button>

    <button type="button" onclick="closeModal('editarLibroModal')" class="btn-neo btn-neo-red">
        Cancelar actualizacion
    </button>
</div>
                </form>
            </div>
        </div>

        <!-- MODAL PERFIL -->
        <div id="perfilModal" class="modal">
            <div class="modal-content" onclick="event.stopPropagation()">
                <div class="modal-header">
                    <h2>👤 Mi Perfil</h2>
                    <button onclick="closeModal('perfilModal')" class="modal-close-btn">×</button>
                </div>

                <div class="profile-section">
                    <div class="profile-item">
                        <label>Usuario</label>
                        <p>{{ session('usuario_username') }}</p>
                    </div>

                    <div class="profile-item">
                        <label>Nombre</label>
                        <p>{{ session('usuario_nombre') }}</p>
                    </div>

                    <div class="profile-item">
                        <label>Rol</label>
                        <p>Empleado</p>
                    </div>

                    <div class="profile-item">
                        <label>Estado</label>
                        <p class="active">✓ Activo</p>
                    </div>
                </div>

                <div class="modal-footer" style="text-align: center;">
    <button type="button" onclick="closeModal('perfilModal')" class="btn-cerrar-neumorphic">
        <div class="button-outer">
            <div class="button-inner">
                <span>Cerrar Perfil</span>
            </div>
        </div>
    </button>
            </div>
        </div>

    </div>

</div>

<script src="{{ asset('js/employee-dashboard.js') }}"></script>
<script>
    function editarLibro(id, titulo, autor, descripcion, formato) {
        document.getElementById('edit_titulo').value = titulo;
        document.getElementById('edit_autor').value = autor;
        document.getElementById('edit_descripcion').value = descripcion;
        document.getElementById('edit_formato').value = formato;
        document.getElementById('editForm').action = '/empleado/libro/actualizar/' + id;
        openModal('editarLibroModal');
    }
</script>

</body>
</html>
