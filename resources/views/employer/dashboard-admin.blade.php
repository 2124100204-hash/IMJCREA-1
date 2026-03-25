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

    {{-- HEADER --}}
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
                <button type="submit" class="cancel-btn">Cerrar Sesión</button>
            </form>
        </div>
    </div>

    {{-- ALERTAS --}}
    @if(session('success'))
        <div style="background:#d4edda;color:#155724;padding:12px 20px;border-radius:8px;margin-bottom:16px;border:1px solid #c3e6cb;">✅ {{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div style="background:#f8d7da;color:#721c24;padding:12px 20px;border-radius:8px;margin-bottom:16px;border:1px solid #f5c6cb;">❌ {{ session('error') }}</div>
    @endif

    {{-- GRID DE ACCESOS DIRECTOS --}}
    <div class="modal-grid">
        <button onclick="openModal('contactoModal')" class="modal-btn">
            <div class="modal-btn-icon">📧</div>
            <div class="modal-btn-title">Mensajes</div>
            <div class="contact-card-label">Contacto / Soporte</div>
        </button>
        <button onclick="openModal('librosModal')" class="modal-btn">
            <div class="modal-btn-icon">📚</div>
            <div class="modal-btn-title">Biblioteca</div>
            <div class="contact-card-label">Gestión de Libros</div>
        </button>
        <button onclick="openModal('empleadosModal')" class="modal-btn">
            <div class="modal-btn-icon">👥</div>
            <div class="modal-btn-title">Personal</div>
            <div class="contact-card-label">Admin / Empleados</div>
        </button>
        <button onclick="openModal('ventasModal')" class="modal-btn">
            <div class="modal-btn-icon">📊</div>
            <div class="modal-btn-title">Ventas</div>
            <div class="contact-card-label">Gestión de Ventas</div>
        </button>
    </div>

    {{-- ══ MODAL: BIBLIOTECA (LISTADO) ══ --}}
    <div id="librosModal" class="modal">
    <div class="modal-content-large">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px;">
            <h2 class="modal-title-font" style="margin:0;">📚 Biblioteca Central</h2>
            <button onclick="openModal('nuevoLibroModal')" class="save-btn" style="padding:8px 16px;font-size:14px;">+ Nuevo Libro</button>
        </div>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Portada</th>
                        <th>Título</th>
                        <th>Categoría</th>
                        <th>Autor</th>
                        <th>Formatos</th>
                        <th>Precio desde</th>
                        <th style="text-align:right;">Acciones</th>
                    </tr>
                </thead>
              <tbody>
    @foreach(\App\Models\Libro::with(['autor','categoria','formatos'])->get() as $libro)
    <tr>
        {{-- Portada --}}
        <td>
            @if($libro->portada)
                <img src="{{ asset('storage/' . $libro->portada) }}" 
                     style="width:40px; height:55px; object-fit:cover; border-radius:4px; border:1px solid #eee;">
            @else
                <div style="width:40px; height:55px; background:#f5f5f5; border-radius:4px; display:flex; align-items:center; justify-content:center; font-size:10px; color:#ccc;">NO</div>
            @endif
        </td>

        {{-- Título --}}
        <td class="td-bold">{{ $libro->titulo }}</td>

        {{-- Categoría --}}
        <td>
            <span style="background:#edf2f7; padding:4px 8px; border-radius:6px; font-size:12px;">
                {{ $libro->categoria->nombre ?? 'Sin categoría' }}
            </span>
        </td>

        {{-- Autor --}}
        <td class="td-muted">{{ $libro->autor->nombre ?? 'Anónimo' }}</td>

        {{-- Formatos --}}
        <td>
            <div style="display:flex; gap:4px; flex-wrap:wrap;">
                @foreach($libro->formatos as $formato)
                    <span style="font-size:10px; background:#e2e8f0; padding:2px 5px; border-radius:4px; text-transform:uppercase; font-weight:bold;">
                        {{ $formato->formato }}
                    </span>
                @endforeach
            </div>
        </td>

        {{-- Precio Desde --}}
        <td style="font-weight:bold; color:#2d3748;">
            @if($libro->formatos->count() > 0)
                ${{ number_format($libro->formatos->min('precio'), 2) }}
            @else
                —
            @endif
        </td>

        {{-- Acciones --}}
        <td style="text-align:right; white-space:nowrap;">
          <button type="button" 
    class="btn-editar"
    onclick="prepararEdicionLibro(this)"
    data-id="{{ $libro->id }}"
    data-titulo="{{ $libro->titulo }}"
    data-descripcion="{{ $libro->descripcion }}"
    data-autor="{{ $libro->autor_id }}" 
    data-categoria="{{ $libro->categoria_id }}"
    data-formatos='@json($libro->formatos)'>
    Editar
</button>
            <form action="{{ route('admin.libro.eliminar', $libro->id) }}" method="POST" style="display:inline;">
                @csrf
                <button type="submit" class="btn-delete" style="padding:5px 10px; background:#fff5f5; color:#c53030; border:1px solid #feb2b2; border-radius:4px; cursor:pointer;" onclick="return confirm('¿Eliminar libro?')">Borrar</button>
            </form>
        </td>
    </tr>
    @endforeach
</tbody>
            </table>
        </div>
        {{-- ... --}}
    </div>
</div>
{{-- ══ MODAL: EDITAR LIBRO ══ --}}
<div id="editarLibroModal" class="modal">
    <div class="modal-content-form" style="max-width: 600px;">
        <h2 class="aside-title" style="font-family:'Playfair Display'; margin-bottom:25px;">📝 Editar Libro</h2>
        
     <form id="formEditarLibro" method="POST" enctype="multipart/form-data">
    @csrf
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                <div class="form-group" style="grid-column: span 2;">
                    <label class="form-label">Título</label>
                    <input type="text" name="titulo" id="edit_titulo" class="form-input" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Autor</label>
                    <select name="autor_id" id="edit_autor_id" class="form-input" required>
                        @foreach(\App\Models\Autor::orderBy('nombre')->get() as $autor)
                            <option value="{{ $autor->id }}">{{ $autor->nombre }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Categoría</label>
                    <input type="text" name="categoria_nombre" id="edit_categoria" list="categoriasList" class="form-input">
                </div>
            </div>

            <div style="margin-top: 20px;">
                <h3 style="font-size: 13px; color: #4a5568; margin-bottom: 10px;">FORMATOS Y PRECIOS</h3>
                <div id="edit_formatos_container" style="display: grid; gap: 10px;">
                    @foreach(['fisico' => 'Físico', 'ar' => 'AR', 'vr' => 'VR'] as $key => $label)
                    <div style="display: grid; grid-template-columns: 1.5fr 1fr 1fr; gap: 10px; align-items: center; background: #f8fafc; padding: 8px; border-radius: 8px;">
                        <label style="font-weight: 600; font-size: 13px;">
                            <input type="checkbox" name="formatos[{{ $key }}][activo]" value="1" id="edit_check_{{ $key }}"> {{ $label }}
                        </label>
                        <input type="number" name="formatos[{{ $key }}][precio]" id="edit_precio_{{ $key }}" step="0.01" class="form-input" placeholder="Precio">
                        <input type="number" name="formatos[{{ $key }}][stock]" id="edit_stock_{{ $key }}" class="form-input" placeholder="Stock">
                    </div>
                    @endforeach
                </div>
            </div>

            <div class="form-group" style="margin-top: 15px;">
                <label class="form-label">Descripción</label>
                <textarea name="descripcion" id="edit_descripcion" class="form-input" rows="3" style="resize: none;"></textarea>
            </div>

            <div class="btn-group" style="margin-top:25px; display: flex; justify-content: flex-end; gap: 10px;">
                <button type="button" onclick="closeModal('editarLibroModal')" class="cancel-btn">Cancelar</button>
                <button type="submit" class="save-btn" style="background:#2d3748; padding: 10px 25px;">ACTUALIZAR CAMBIOS</button>
            </div>
        </form>
    </div>
</div>
  {{-- ══ MODAL: NUEVO LIBRO ══ --}}
<div id="nuevoLibroModal" class="modal">
    <div class="modal-content-form" style="max-width: 600px;">
        <h2 class="aside-title" style="font-family:'Playfair Display'; margin-bottom:25px;"> Registrar Nuevo Libro</h2>
        
        <form action="{{ route('admin.libro.crear') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            {{-- Sección de Portada --}}
            <div class="form-group" style="margin-bottom: 20px; border: 2px dashed #cbd5e0; padding: 20px; border-radius: 12px; text-align: center; background: #f7fafc;">
                <label class="form-label" style="font-weight: 700;">Portada del Libro</label>
                <div style="margin-top: 10px;">
                    <input type="file" name="portada" id="portadaInput" accept="image/*" class="form-input" style="border:none; background:transparent;">
                </div>
                <small class="td-muted">Recomendado: JPG o PNG (Máx. 2MB)</small>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                {{-- Título --}}
                <div class="form-group" style="grid-column: span 2;">
                    <label class="form-label">Título <span style="color:#e53e3e;">*</span></label>
                    <input type="text" name="titulo" class="form-input" placeholder="Ej: Cien años de soledad" required>
                </div>

                {{-- Autor con botón de "Nuevo" --}}
                <div class="form-group" style="grid-column: span 2;">
                    <label class="form-label">Autor <span style="color:#e53e3e;">*</span></label>
                    <div style="display:flex; gap:8px;">
                        <select name="autor_id" class="form-input" required style="flex: 1;">
                            <option value="">— Seleccionar Autor —</option>
                            @foreach(\App\Models\Autor::orderBy('nombre')->get() as $autor)
                                <option value="{{ $autor->id }}">{{ $autor->nombre }}</option>
                            @endforeach
                        </select>
                        <button type="button" onclick="openModal('nuevoAutorModal')" class="save-btn" style="padding:0 15px; font-size:12px; white-space:nowrap; height: 42px;">+ Nuevo</button>
                    </div>
                </div>

                {{-- Categoría --}}
               <div class="form-group">
    <label class="form-label">Categoría (Escribe una nueva o elige)</label>
    <input type="text" name="categoria_nombre" list="categoriasList" class="form-input" placeholder="Ej: Fantasía">
    <datalist id="categoriasList">
        @foreach(\App\Models\Categoria::all() as $cat)
            <option value="{{ $cat->nombre }}">
        @endforeach
    </datalist>
</div>

                {{-- Nivel de Edad --}}
                <div class="form-group">
                    <label class="form-label">Nivel de Edad</label>
                    <select name="nivel_edad" class="form-input">
                        <option value="todas">Para todos</option>
                        <option value="ninos">Niños</option>
                        <option value="jovenes">Jóvenes</option>
                        <option value="adultos">Adultos</option>
                    </select>
                </div>

                {{-- Duración (en páginas o minutos según tu lógica) --}}
                <div class="form-group">
                    <label class="form-label">Duración / Páginas</label>
                    <input type="number" name="duracion" class="form-input" placeholder="Ej: 350">
                </div>
            </div>
<div style="margin-top: 25px; border-top: 1px solid #eee; padding-top: 20px;">
    <h3 style="font-size: 14px; color: #4a5568; margin-bottom: 15px; text-transform: uppercase; letter-spacing: 1px;"> Formatos Disponibles</h3>
    
    <div style="display: grid; gap: 15px;">
        @foreach(['fisico' => 'Libro Físico', 'ar' => 'Realidad Aumentada (AR)', 'vr' => 'Realidad Virtual (VR)'] as $key => $label)
        <div style="display: grid; grid-template-columns: 1.5fr 1fr 1fr; gap: 10px; align-items: center; background: #f8fafc; padding: 10px; border-radius: 8px; border: 1px solid #e2e8f0;">
            
            {{-- Checkbox y Nombre del Formato --}}
            <label style="display: flex; align-items: center; gap: 10px; cursor: pointer; font-weight: 600; color: #2d3748;">
                <input type="checkbox" name="formatos[{{ $key }}][activo]" value="1" style="width: 18px; height: 18px;">
                {{ $label }}
            </label>

            {{-- Campo Precio --}}
            <div>
                <label style="font-size: 11px; color: #718096; display: block;">Precio ($)</label>
                <input type="number" name="formatos[{{ $key }}][precio]" step="0.01" class="form-input" placeholder="0.00" style="padding: 5px 8px;">
            </div>

            {{-- Campo Stock --}}
            <div>
                <label style="font-size: 11px; color: #718096; display: block;">Stock (Unidades)</label>
                <input type="number" name="formatos[{{ $key }}][stock]" class="form-input" placeholder="0" value="0" style="padding: 5px 8px;">
            </div>
        </div>
        @endforeach
    </div>
</div>
            {{-- Descripción --}}
            <div class="form-group" style="margin-top: 15px;">
                <label class="form-label">Descripción / Sinopsis <span style="color:#e53e3e;">*</span></label>
                <textarea name="descripcion" class="form-input" rows="4" style="resize: none;" placeholder="Escribe un breve resumen del libro..." required></textarea>
            </div>

            {{-- Botones de acción --}}
<div class="btn-group" style="margin-top:30px; display: flex; justify-content: flex-end; gap: 10px;">
    <button type="button" onclick="closeModal('nuevoLibroModal')" class="cancel-btn">Cancelar</button>
    <button type="submit" 
        style="background: #1a202c; color: white; padding: 12px 30px; border-radius: 8px; font-weight: bold; border: none; cursor: pointer;"
        onclick="console.log('Intentando enviar formulario...')">
    💾 GUARDAR LIBRO AHORA
</button>
</div>
        </form>
    </div>
</div>

    {{-- ══ MODAL: NUEVO AUTOR ══ --}}
    <div id="nuevoAutorModal" class="modal" style="z-index:3000;">
        <div class="modal-content-form" style="max-width:400px;margin-top:100px;">
            <h3 class="modal-title-font">Agregar Nuevo Autor</h3>
            <form action="{{ route('admin.autor.crear') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label class="form-label">Nombre Completo</label>
                    <input type="text" name="nombre" class="form-input" required placeholder="Ej: Gabriel García Márquez">
                </div>
                <div class="btn-group">
                    <button type="button" onclick="closeModal('nuevoAutorModal')" class="cancel-btn">Cerrar</button>
                    <button type="submit" class="btn-save">Registrar</button>
                </div>
            </form>
        </div>
    </div>

{{-- ══ MODAL: PERSONAL (LISTADO) ══ --}}
<div id="empleadosModal" class="modal">{{-- ══ MODAL: PERSONAL (LISTADO) ══ --}}
<div id="empleadosModal" class="modal">
    <div class="modal-content-large">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px;">
            <h2 class="modal-title-font" style="margin:0;">👥 Gestión de Personal</h2>
            <button onclick="openModal('nuevoEmpleadoModal')" class="save-btn" style="padding:8px 16px;font-size:14px;">+ Nuevo Empleado</button>
        </div>

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Empleado</th>
                        <th>Puesto</th>
                        <th>Estado</th>
                        <th style="text-align:right;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach(\App\Models\Empleado::with('usuario')->get() as $emp)
                    <tr>
                        <td class="td-bold">{{ $emp->nombre }}</td>
                        <td class="td-muted">{{ $emp->puesto }}</td>
                        <td>
                            @if($emp->usuario && $emp->usuario->activo)
                                <span style="color:#2f855a; background:#f0fff4; padding:2px 8px; border-radius:10px; font-size:12px; font-weight:600;">Activo</span>
                            @else
                                <span style="color:#c53030; background:#fff5f5; padding:2px 8px; border-radius:10px; font-size:12px; font-weight:600;">Inactivo</span>
                            @endif
                        </td>
                        <td style="text-align:right; white-space:nowrap;">
                            {{-- Botón Ver --}}
                            <button class="btn-ver"
                                data-id="{{ $emp->id }}" 
                                data-nombre="{{ $emp->nombre }}"
                                data-puesto="{{ $emp->puesto }}" 
                                data-curp="{{ $emp->curp }}"
                                data-telefono="{{ $emp->telefono }}"
                                data-salario="{{ $emp->salario }}"
                                data-estado="{{ $emp->usuario ? 'activo' : 'inactivo' }}"
                                onclick="prepararVerEmpleado(this)"
                                style="margin-right:5px;">Ver</button>

                            {{-- Botón Editar --}}
                            <button class="btn-edit" 
                                data-id="{{ $emp->id }}" 
                                data-nombre="{{ $emp->nombre }}"
                                data-puesto="{{ $emp->puesto }}" 
                                data-curp="{{ $emp->curp }}"
                                data-telefono="{{ $emp->telefono }}"
                                data-salario="{{ $emp->salario }}"
                                data-estado="{{ $emp->usuario ? 'activo' : 'inactivo' }}"
                                onclick="prepararEdicionEmpleado(this)">Editar</button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <button onclick="closeModal('empleadosModal')" class="cancel-btn" style="margin-top:20px;">Cerrar</button>
    </div>
</div>
    <div class="modal-content-large">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px;">
            <h2 class="modal-title-font" style="margin:0;">👥 Gestión de Personal</h2>
            <button onclick="openModal('nuevoEmpleadoModal')" class="save-btn" style="padding:8px 16px;font-size:14px;">+ Nuevo Empleado</button>
        </div>

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Empleado</th>
                        <th>Puesto</th>
                        <th>Estado</th>
                        <th style="text-align:right;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach(\App\Models\Empleado::with('usuario')->get() as $emp)
                    <tr>
                        <td class="td-bold">{{ $emp->nombre }}</td>
                        <td class="td-muted">{{ $emp->puesto }}</td>
                        <td>
                            @if($emp->usuario && $emp->usuario->activo)
                                <span style="color:#2f855a; background:#f0fff4; padding:2px 8px; border-radius:10px; font-size:12px; font-weight:600;">Activo</span>
                            @else
                                <span style="color:#c53030; background:#fff5f5; padding:2px 8px; border-radius:10px; font-size:12px; font-weight:600;">Inactivo</span>
                            @endif
                        </td>
                        <td style="text-align:right; white-space:nowrap;">
                            {{-- Botón Ver --}}
                            <button class="btn-ver"
                                data-id="{{ $emp->id }}" 
                                data-nombre="{{ $emp->nombre }}"
                                data-puesto="{{ $emp->puesto }}" 
                                data-curp="{{ $emp->curp }}"
                                data-telefono="{{ $emp->telefono }}"
                                data-salario="{{ $emp->salario }}"
                                data-estado="{{ $emp->usuario ? 'activo' : 'inactivo' }}"
                                onclick="prepararVerEmpleado(this)"
                                style="margin-right:5px;">Ver más</button>

                            {{-- Botón Editar --}}
                            <button class="btn-edit" 
                                data-id="{{ $emp->id }}" 
                                data-nombre="{{ $emp->nombre }}"
                                data-puesto="{{ $emp->puesto }}" 
                                data-curp="{{ $emp->curp }}"
                                data-telefono="{{ $emp->telefono }}"
                                data-salario="{{ $emp->salario }}"
                                data-estado="{{ $emp->usuario ? 'activo' : 'inactivo' }}"
                                onclick="prepararEdicionEmpleado(this)">Editar</button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <button onclick="closeModal('empleadosModal')" class="cancel-btn" style="margin-top:20px;">Cerrar</button>
    </div>
</div>
{{-- ══ MODAL: VISTA DETALLADA DEL EMPLEADO ══ --}}
<div id="verEmpleadoModal" class="modal" style="z-index: 2500;">
    <div class="modal-content-form" style="max-width: 500px;">
        <h2 class="modal-title-font" style="margin-bottom: 20px; border-bottom: 1px solid #eee; padding-bottom: 10px;">
            📄 Ficha del Empleado
        </h2>
        
        <div id="detalleEmpleadoContenido" style="display: grid; gap: 15px;">
            <div class="detail-item">
                <label style="font-size: 11px; color: #999; text-transform: uppercase; font-weight: 700;">Nombre Completo</label>
                <p id="view_nombre" style="font-size: 16px; font-weight: 600; margin: 0; color: #333;"></p>
            </div>

            <div style="display: flex; gap: 20px;">
                <div style="flex: 1;">
                    <label style="font-size: 11px; color: #999; text-transform: uppercase; font-weight: 700;">Puesto</label>
                    <p id="view_puesto" style="margin: 0; color: #555;"></p>
                </div>
                <div style="flex: 1;">
                    <label style="font-size: 11px; color: #999; text-transform: uppercase; font-weight: 700;">Estado</label>
                    <p id="view_estado" style="margin: 0; font-weight: 600;"></p>
                </div>
            </div>

            <div style="display: flex; gap: 20px;">
                <div style="flex: 1;">
                    <label style="font-size: 11px; color: #999; text-transform: uppercase; font-weight: 700;">CURP</label>
                    <p id="view_curp" style="margin: 0; color: #555; font-family: monospace;"></p>
                </div>
                <div style="flex: 1;">
                    <label style="font-size: 11px; color: #999; text-transform: uppercase; font-weight: 700;">Teléfono</label>
                    <p id="view_telefono" style="margin: 0; color: #555;"></p>
                </div>
            </div>

            <div class="detail-item">
                <label style="font-size: 11px; color: #999; text-transform: uppercase; font-weight: 700;">Salario Mensual</label>
                <p id="view_salario" style="margin: 0; color: #2f855a; font-weight: 700; font-size: 18px;"></p>
            </div>
        </div>

        <div class="btn-group" style="margin-top: 30px;">
            <button type="button" onclick="closeModal('verEmpleadoModal')" class="cancel-btn" style="width: 100%;">Cerrar Ficha</button>
        </div>
    </div>
</div>
    {{-- ══ MODAL: NUEVO EMPLEADO ══ --}}
    <div id="nuevoEmpleadoModal" class="modal">
        <div class="modal-content-form">
            <h2 class="aside-title">👤 Nuevo Empleado</h2>
            <form action="{{ route('admin.empleado.crear') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label class="form-label">Nombre completo</label>
                    <input type="text" name="nombre" class="form-input" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Correo electrónico</label>
                    <input type="email" name="email" class="form-input" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Contraseña</label>
                    <input type="password" name="password" class="form-input" required>
                </div>
                <div class="btn-group">
                    <button type="button" onclick="closeModal('nuevoEmpleadoModal')" class="cancel-btn">Cancelar</button>
                    <button type="submit" class="btn-save">Registrar</button>
                </div>
            </form>
        </div>
    </div>

    {{-- ══ MODAL: VENTAS ══ --}}
    <div id="ventasModal" class="modal">
        <div class="modal-content-large">
            <h2 class="modal-title-font">📊 Historial de Ventas</h2>
            <div class="table-container">
                <table>
                    <thead><tr><th>ID</th><th>Libro</th><th>Cliente</th><th>Precio</th><th>Fecha</th></tr></thead>
                    <tbody>
                        @foreach($ventas as $v)
                        <tr>
                            <td>#{{ $v->id }}</td>
                            <td>{{ $v->libro_titulo }}</td>
                            <td>{{ $v->cliente_nombre }}</td>
                            <td>${{ number_format($v->total, 2) }}</td>
                            <td>{{ $v->created_at->format('d/m/Y') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <button onclick="closeModal('ventasModal')" class="cancel-btn" style="margin-top:20px;">Cerrar</button>
        </div>
    </div>

    {{-- ══ MODAL: CONTACTO ══ --}}
    <div id="contactoModal" class="modal">
        <div class="modal-content-large">
            <h2 class="modal-title-font">📧 Mensajes de Contacto</h2>
            <div class="table-container">
                <table>
                    <thead><tr><th>Remitente</th><th>Asunto</th><th>Mensaje</th><th>Fecha</th></tr></thead>
                    <tbody>
                        @foreach(\App\Models\Contacto::latest()->get() as $msg)
                        <tr>
                            <td class="td-bold">{{ $msg->name }}</td>
                            <td>{{ $msg->asunto }}</td>
                            <td style="max-width:300px;">{{ $msg->message }}</td>
                            <td>{{ $msg->created_at->diffForHumans() }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <button onclick="closeModal('contactoModal')" class="cancel-btn" style="margin-top:20px;">Cerrar</button>
        </div>
    </div>

</div>
<script src="{{ asset('js/admin-dashboard.js') }}"></script>
</body>
</html>