<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - {{ config('app.name', 'IMJCREA') }}</title>
    
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=playfair-display:700,900|instrument-sans:400,600,700" rel="stylesheet" />
    
    <link rel="stylesheet" href="{{ asset('css/default.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
</head>
<body>

<div class="dashboard-container">
    
    <div class="dashboard-header">
        <div>
            <h1>Panel de Control</h1>
            <p class="aside-desc">Núcleo administrativo de IMJCREA.</p>
        </div>
        <div class="navbar-right">
            <div class="user-info">
                <div class="user-avatar">{{ substr(session('usuario_nombre') ?? 'A', 0, 1) }}</div>
                <span class="user-name">{{ session('usuario_nombre') }}</span>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="logout-link-btn">Cerrar Sesión</button>
            </form>
        </div>
    </div>

    <div class="modal-grid">
        <button onclick="openModal('contactoModal')" class="modal-btn">
            <div class="modal-btn-icon">📧</div>
            <div class="modal-btn-title">Mensajes</div>
            <div class="contact-card-label">Contacto / Soporte</div>
        </button>

        <button onclick="openModal('librosModal')" class="modal-btn">
            <div class="modal-btn-icon">📚</div>
            <div class="modal-btn-title">Biblioteca</div>
            <div class="contact-card-label">CRUD de Libros</div>
        </button>

        <button onclick="openModal('empleadosModal')" class="modal-btn">
            <div class="modal-btn-icon">👥</div>
            <div class="modal-btn-title">Personal</div>
            <div class="contact-card-label">Admin / Empleados</div>
        </button>
    </div>

    <div id="librosModal" class="modal">
        <div class="modal-content-large">
            <div class="modal-header-flex" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <h2 class="modal-title-font" style="margin: 0;">📚 Biblioteca Central</h2>
                <button class="save-btn" style="padding: 8px 16px; font-size: 14px;">+ Nuevo Libro</button>
            </div>
            
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Título</th>
                            <th>Categoría</th>
                            <th>Autor</th>
                            <th style="text-align: right;">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach(\App\Models\Libro::with(['autor', 'categoria', 'formatos'])->get() as $libro)
                        <tr>
                            <td class="td-bold">{{ $libro->titulo }}</td>
                            <td class="td-muted">{{ $libro->categoria->nombre ?? 'N/A' }}</td>
                            <td class="td-muted">{{ $libro->autor->nombre ?? 'Sin Autor' }}</td> 
                            <td class="td-actions" style="text-align: right;">
                               <button class="btn-edit" 
                                    onclick="prepararEdicion(
                                        {{ $libro->id }}, 
                                        '{{ addslashes($libro->titulo) }}', 
                                        '{{ addslashes($libro->descripcion) }}', 
                                        '{{ $libro->nivel_edad }}', 
                                        '{{ $libro->duracion }}', 
                                        {{ $libro->autor_id }},
                                        {{ $libro->categoria_id ?? 'null' }},
                                        {{ json_encode($libro->formatos->pluck('formato')) }}
                                    )">
                                    Editar
                                </button>
                                <form action="{{ route('admin.libro.eliminar', $libro->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-delete" onclick="return confirm('¿Eliminar libro?')">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <button onclick="closeModal('librosModal')" class="btn-close-modal" style="margin-top: 20px;">Cerrar</button>
        </div>
    </div>

    <div id="editarLibroModal" class="modal">
        <div class="modal-content-form">
            <h2 class="aside-title" style="font-family: 'Playfair Display'; margin-bottom: 25px;">Editar Libro</h2>
            
            <form id="editForm" method="POST">
                @csrf
                @method('PUT')
                
                <div class="form-group">
                    <label class="form-label">Título del ejemplar</label>
                    <input type="text" name="titulo" id="edit_titulo" class="form-input" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Categoría</label>
                    <select name="categoria_id" id="edit_categoria" class="form-input">
                        <option value="">Seleccionar Categoría</option>
                        @foreach(\App\Models\Categoria::all() as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->nombre }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Formatos Disponibles</label>
                    <div style="display: flex; gap: 20px; padding: 10px; background: #f9f9f9; border-radius: 8px;">
                        <label><input type="checkbox" name="formatos[]" value="fisico" id="check_fisico"> Físico</label>
                        <label><input type="checkbox" name="formatos[]" value="vr" id="check_vr"> VR</label>
                        <label><input type="checkbox" name="formatos[]" value="ar" id="check_ar"> AR</label>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Descripción</label>
                    <textarea name="descripcion" id="edit_descripcion" class="form-input" rows="3"></textarea>
                </div>

                <div style="display: flex; gap: 15px;">
                    <div class="form-group" style="flex: 1;">
                        <label class="form-label">Nivel Edad</label>
                        <input type="text" name="nivel_edad" id="edit_nivel_edad" class="form-input">
                    </div>
                    <div class="form-group" style="flex: 1;">
                        <label class="form-label">Duración (min)</label>
                        <input type="text" name="duracion" id="edit_duracion" class="form-input">
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Autor</label>
                    <div style="display: flex; gap: 10px;">
                        <select name="autor_id" id="edit_autor" class="form-input" style="flex: 1;">
                            @foreach(\App\Models\Autor::all() as $autor)
                                <option value="{{ $autor->id }}">{{ $autor->nombre }}</option>
                            @endforeach
                        </select>
                        <button type="button" onclick="openModal('nuevoAutorModal')" class="save-btn" style="padding: 0 15px; font-size: 12px;">+ Nuevo</button>
                    </div>
                </div>

                <div class="btn-group">
                    <button type="button" onclick="closeModal('editarLibroModal')" class="cancel-btn">Cancelar</button>
                    <button type="submit" class="btn-save">Guardar Cambios</button>
                </div>
            </form>
        </div>
    </div>

    <div id="nuevoAutorModal" class="modal" style="background: rgba(0,0,0,0.8); z-index: 2000;">
        <div class="modal-content-form" style="max-width: 400px; margin-top: 100px;">
            <h3 class="modal-title-font">Agregar Nuevo Autor</h3>
            <form action="{{ route('admin.autor.crear') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label class="form-label">Nombre Completo</label>
                    <input type="text" name="nombre" class="form-input" placeholder="Ej: Gabriel García Márquez" required>
                </div>
                <div class="btn-group">
                    <button type="button" onclick="closeModal('nuevoAutorModal')" class="cancel-btn">Cerrar</button>
                    <button type="submit" class="btn-save">Registrar</button>
                </div>
            </form>
        </div>
    </div>
<div id="empleadosModal" class="modal">
    <div class="modal-content-large">
        <div class="modal-header-flex" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h2 class="modal-title-font" style="margin: 0;">👥 Gestión de Personal</h2>
            <button onclick="openModal('nuevoEmpleadoModal')" class="save-btn" style="padding: 8px 16px; font-size: 14px;">+ Nuevo Empleado</button>
        </div>
        
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Correo</th>
                        <th>Rol</th>
                        <th style="text-align: right;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach(\App\Models\Usuario::whereIn('rol', ['empleado', 'admin'])->get() as $emp)
                    <tr>
                        <td class="td-bold">{{ $emp->nombre }}</td>
                        <td>{{ $emp->correo }}</td>
                        <td><span class="badge">{{ ucfirst($emp->rol) }}</span></td>
                        <td class="td-actions" style="text-align: right;">
                            <button class="btn-edit" 
                                onclick="prepararEdicionEmpleado({{ $emp->id }}, '{{ $emp->nombre }}', '{{ $emp->correo }}')">
                                Editar
                            </button>
                            <form action="{{ route('admin.empleado.eliminar', $emp->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-delete" onclick="return confirm('¿Eliminar empleado?')">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <button onclick="closeModal('empleadosModal')" class="btn-close-modal" style="margin-top: 20px;">Cerrar</button>
    </div>
</div>
<div id="editarEmpleadoModal" class="modal">
    <div class="modal-content-form" style="max-width: 450px;">
        <h2 class="aside-title" style="font-family: 'Playfair Display'; margin-bottom: 25px;">Editar Personal</h2>
        <form id="editEmpleadoForm" method="POST">
            @csrf
            @method('PUT')
            
            <div class="form-group">
                <label class="form-label">Nombre Completo</label>
                <input type="text" name="nombre" id="emp_edit_nombre" class="form-input" required>
            </div>

           <div class="form-group">
    <label class="form-label">Correo Electrónico</label>
    <input type="email" name="email" id="emp_edit_correo" class="form-input" required>
</div>

            <div class="form-group">
                <label class="form-label">Nueva Contraseña (dejar vacío para no cambiar)</label>
                <input type="password" name="password" class="form-input">
            </div>

            <div class="btn-group">
                <button type="button" onclick="closeModal('editarEmpleadoModal')" class="cancel-btn">Cancelar</button>
                <button type="submit" class="btn-save">Actualizar</button>
            </div>
        </form>
    </div>
</div>
    <div id="contactoModal" class="modal">
        <div class="modal-content-large">
            <h2 class="modal-title-font">📧 Mensajes de Contacto</h2>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Remitente</th>
                            <th>Correo</th>
                            <th>Fecha</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse(\App\Models\Contacto::latest()->get() as $msg)
                        <tr>
                            <td class="td-bold">{{ $msg->nombre }}</td>
                            <td>{{ $msg->correo }}</td>
                            <td>{{ $msg->created_at->diffForHumans() }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="3" style="text-align:center;">No hay mensajes.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <button onclick="closeModal('contactoModal')" class="btn-close-modal">Cerrar</button>
        </div>
    </div>

    <div id="empleadosModal" class="modal">
        <div class="modal-content-large">
            <h2 class="modal-title-font">👥 Gestión de Personal</h2>
            <p>Sección en desarrollo.</p>
            <button onclick="closeModal('empleadosModal')" class="btn-close-modal">Cerrar</button>
        </div>
    </div>

</div>

<script src="{{ asset('js/admin-dashboard.js') }}"></script>
</body>
</html>