<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Dashboard Admin - {{ config('app.name', 'IMJCREA') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=playfair-display:700,900|instrument-sans:400,600,700" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('css/default.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.0/dist/sweetalert2.all.min.js"></script>
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
        <button onclick="openModal('devolucionesModal')" class="modal-btn">
            <div class="modal-btn-icon">📦</div>
            <div class="modal-btn-title">Devoluciones</div>
            <div class="contact-card-label">Gestión de Devoluciones</div>
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
    data-nivel-edad="{{ $libro->nivel_edad }}"
    data-duracion="{{ $libro->duracion }}"
    data-autor="{{ $libro->autor_id }}" 
    data-categoria-nombre="{{ $libro->categoria ? $libro->categoria->nombre : '' }}"
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
                    <button class="add-btn" type="button" onclick="openModal('nuevoAutorModal')" style="margin-top: 8px; padding: 6px 12px; font-size: 12px;">+ Nuevo Autor</button>
                </div>

                <div class="form-group">
                    <label class="form-label">Categoría</label>
                    <input type="text" name="categoria_nombre" id="edit_categoria" list="categoriasList" class="form-input">
                <button class="add-btn" type="button" onclick="openModal('nuevaCategoriaModal')" style="margin-top: 8px; padding: 6px 12px; font-size: 12px;">+ Nueva Categoría</button>
                </div>

                <div class="form-group">
                    <label class="form-label">Nivel de Edad</label>
                    <select name="nivel_edad" id="edit_nivel_edad" class="form-input">
                        <option value="">Seleccionar...</option>
                        <option value="3+">3+</option>
                        <option value="6+">6+</option>
                        <option value="8+">8+</option>
                        <option value="10+">10+</option>
                        <option value="12+">12+</option>
                        <option value="14+">14+</option>
                        <option value="16+">16+</option>
                        <option value="18+">18+</option>
                        <option value="adulto">Adulto</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Duración</label>
                    <input type="text" name="duracion" id="edit_duracion" class="form-input" placeholder="Ej: 45 min, 1.5 horas">
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
{{-- ══ MODAL: NUEVA CATEGORÍA ══ --}}
<div id="nuevaCategoriaModal" class="modal" style="z-index:3000;">
    <div class="modal-content-form" style="max-width:400px;margin-top:100px;">
        <h3 class="modal-title-font">Agregar Nueva Categoría</h3>
        <form action="{{ route('admin.categoria.crear') }}" method="POST">
            @csrf
            <div class="form-group">
                <label class="form-label">Nombre de Categoría</label>
                <input type="text" name="nombre" class="form-input" required placeholder="Ej: Ciencia Ficción">
            </div>
            <div class="btn-group">
                <button type="button" onclick="closeModal('nuevaCategoriaModal')" class="cancel-btn">Cerrar</button>
                <button type="submit" class="btn-save">Registrar</button>
            </div>
        </form>
    </div>
</div>

{{-- ══ MODAL: PERSONAL (LISTADO) ══ --}}
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

{{-- ══ MODAL: DEVOLUCIONES (LISTADO) ══ --}}
<div id="devolucionesModal" class="modal">
    <div class="modal-content-large">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px;">
            <h2 class="modal-title-font" style="margin:0;">📦 Gestión de Devoluciones</h2>
        </div>

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Cliente</th>
                        <th>Libro</th>
                        <th>Cantidad</th>
                        <th>Monto</th>
                        <th>Razón</th>
                        <th>Estado</th>
                        <th>Fecha</th>
                        <th style="text-align:right;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($devoluciones ?? [] as $devolucion)
                    <tr>
                        <td class="td-bold">
                            {{ $devolucion->pedidoDetalle->pedido->usuario->nombre ?? 'N/A' }}
                            <br><small style="color:#666;">{{ $devolucion->pedidoDetalle->pedido->usuario->email ?? '' }}</small>
                        </td>
                        <td class="td-bold">{{ $devolucion->pedidoDetalle->libro->titulo ?? 'N/A' }}</td>
                        <td>{{ $devolucion->cantidad_devuelta }}</td>
                        <td>${{ number_format($devolucion->monto_reembolsado, 2) }}</td>
                        <td>{{ $devolucion->razon }}</td>
                        <td>
                            @switch($devolucion->estado)
                                @case('solicitada')
                                    <span style="color:#d69e2e; background:#fefcbf; padding:2px 8px; border-radius:10px; font-size:12px; font-weight:600;">Pendiente</span>
                                    @break
                                @case('procesada')
                                    <span style="color:#2f855a; background:#f0fff4; padding:2px 8px; border-radius:10px; font-size:12px; font-weight:600;">Aprobada</span>
                                    @break
                                @case('rechazada')
                                    <span style="color:#c53030; background:#fff5f5; padding:2px 8px; border-radius:10px; font-size:12px; font-weight:600;">Rechazada</span>
                                    @break
                                @default
                                    <span style="color:#666; background:#f7fafc; padding:2px 8px; border-radius:10px; font-size:12px; font-weight:600;">{{ $devolucion->estado }}</span>
                            @endswitch
                        </td>
                        <td>{{ $devolucion->created_at->format('d/m/Y H:i') }}</td>
                        <td style="text-align:right; white-space:nowrap;">
                            @if($devolucion->estado === 'solicitada')
                            <form action="{{ route('admin.devolucion.procesar', $devolucion->id) }}" method="POST" style="display:inline;">
                                @csrf
                                <input type="hidden" name="accion" value="aprobar">
                                <button type="submit" class="btn-edit" style="background:#2f855a; margin-right:5px;">Aprobar</button>
                            </form>
                            <form action="{{ route('admin.devolucion.procesar', $devolucion->id) }}" method="POST" style="display:inline;">
                                @csrf
                                <input type="hidden" name="accion" value="rechazar">
                                <button type="submit" class="btn-edit" style="background:#c53030;">Rechazar</button>
                            </form>
                            @else
                            <span style="color:#666; font-size:12px;">Procesada</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" style="text-align:center; color:#666; padding:20px;">
                            No hay devoluciones registradas
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <button onclick="closeModal('devolucionesModal')" class="cancel-btn" style="margin-top:20px;">Cerrar</button>
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

            <div class="empleado-form-container">
                <form action="{{ route('admin.empleado.crear') }}" method="POST">
                    @csrf

                    <div class="empleado-form-grid">
                        {{-- Información Personal --}}
                        <div class="empleado-form-section">
                            <h3> Información Personal</h3>
                            <div class="form-group">
                                <label class="form-label">Nombre completo *</label>
                                <input type="text" name="nombre" class="form-input" required placeholder="Juan Pérez García">
                            </div>
                            <div class="form-group">
                                <label class="form-label">CURP</label>
                                <input type="text" name="curp" class="form-input" maxlength="18" placeholder="PEGJ900101HDFRRN00">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Teléfono</label>
                                <input type="tel" name="telefono" class="form-input" placeholder="555-123-4567">
                            </div>
                        </div>

                        {{-- Información Laboral --}}
                        <div class="empleado-form-section">
                            <h3> Información Laboral</h3>
                            <div class="form-group">
                                <label class="form-label">Puesto *</label>
                                <select name="puesto" class="form-input" required>
                                    <option value="">Seleccionar</option>
                                    <option value="Gerente">Gerente</option>
                                    <option value="Supervisor">Supervisor</option>
                                    <option value="Vendedor">Vendedor</option>
                                    <option value="Cajero">Cajero</option>
                                    <option value="Almacenista">Almacenista</option>
                                    <option value="Auxiliar">Auxiliar</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Departamento</label>
                                <select name="departamento" class="form-input">
                                    <option value="">Seleccionar</option>
                                    <option value="Ventas">Ventas</option>
                                    <option value="Almacén">Almacén</option>
                                    <option value="Administración">Administración</option>
                                    <option value="Atención al Cliente">Atención al Cliente</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Salario mensual (MXN)</label>
                                <input type="number" name="salario" class="form-input" step="0.01" min="0" placeholder="8500.00">
                            </div>
                        </div>

                        {{-- Información de Acceso --}}
                        <div class="empleado-form-section empleado-form-full">
                            <h3>🔐 Información de Acceso</h3>
                            <div class="form-group">
                                <label class="form-label">Correo electrónico *</label>
                                <input type="email" name="email" class="form-input" required placeholder="empleado@empresa.com">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Contraseña *</label>
                                <input type="password" name="password" class="form-input" required minlength="8" placeholder="Mínimo 8 caracteres">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Confirmar contraseña *</label>
                                <input type="password" name="password_confirmation" class="form-input" required minlength="8" placeholder="Repetir contraseña">
                            </div>
                        </div>

                        {{-- Domicilio --}}
                        <div class="empleado-form-section empleado-form-full">
                            <h3>🏠 Domicilio</h3>
                            <div class="form-group">
                                <label class="form-label">Dirección completa</label>
                                <textarea name="domicilio" class="form-input" rows="2" placeholder="Calle, número, colonia, ciudad, estado, CP"></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="btn-group">
                        <button type="button" onclick="closeModal('nuevoEmpleadoModal')" class="cancel-btn">Cancelar</button>
                        <button type="submit" class="btn-save">Registrar Empleado</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- ══ MODAL: VENTAS ══ --}}
    <div id="ventasModal" class="modal">
        <div class="modal-content-large">
            <h2 class="modal-title-font">📊 Gestión de Pedidos y Ventas</h2>
            <div style="margin-bottom: 20px; padding: 15px; background: #f8f9fa; border-radius: 8px;">
                <p style="margin: 0; color: #666; font-size: 14px;">
                    <strong>Total de pedidos:</strong> {{ $pedidos->count() }} | 
                    <strong>Ingresos totales:</strong> ${{ number_format($pedidos->sum('total'), 2) }}
                </p>
            </div>
            
            @if($pedidos->isEmpty())
                <div style="padding: 30px; text-align: center; color: #999;">
                    <p>📭 No hay pedidos registrados aún</p>
                </div>
            @else
                <div class="table-container" style="max-height: 600px; overflow-y: auto;">
                    <table style="width: 100%; border-collapse: collapse;">
                        <thead style="position: sticky; top: 0; background: #f5f5f5;">
                            <tr style="border-bottom: 2px solid #ddd;">
                                <th style="padding: 12px; text-align: left;"> ID</th>
                                <th style="padding: 12px; text-align: left;"> Cliente</th>
                                <th style="padding: 12px; text-align: left;"> Libros</th>
                                <th style="padding: 12px; text-align: right;">Total</th>
                                <th style="padding: 12px; text-align: center;"> Fecha</th>
                                <th style="padding: 12px; text-align: center;">Estado</th>
                                <th style="padding: 12px; text-align: center;">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pedidos as $pedido)
                            <tr style="border-bottom: 1px solid #eee; hover-background: #f9f9f9;">
                                <td style="padding: 12px;"><strong>#{{ $pedido->id }}</strong></td>
                                <td style="padding: 12px;">
                                    <div style="font-weight: 500;">{{ $pedido->usuario->name ?? 'Desconocido' }}</div>
                                    <div style="font-size: 12px; color: #999;">{{ $pedido->usuario->email ?? '' }}</div>
                                </td>
                                <td style="padding: 12px;">
                                    <div style="max-width: 250px;">
                                        @foreach($pedido->detalles as $detalle)
                                            <div style="font-size: 13px; margin: 4px 0;">
                                                • <strong>{{ $detalle->libro->titulo ?? 'Libro desconocido' }}</strong>
                                                <br>&nbsp;&nbsp;<span style="color: #999; font-size: 12px;">
                                                    {{ ucfirst($detalle->formato) }} × {{ $detalle->cantidad }}
                                                </span>
                                            </div>
                                        @endforeach
                                    </div>
                                </td>
                                <td style="padding: 12px; text-align: right; font-weight: bold; color: var(--amber);">
                                    ${{ number_format($pedido->total, 2) }}
                                </td>
                                <td style="padding: 12px; text-align: center; font-size: 12px; color: #666;">
                                    {{ $pedido->created_at->format('d/m/Y H:i') }}
                                </td>
                                <td style="padding: 12px; text-align: center;">
                                    <span style="
                                        padding: 6px 12px;
                                        border-radius: 20px;
                                        font-size: 12px;
                                        font-weight: 600;
                                        background: {{ $pedido->estado === 'entregado' ? '#d4edda' : ($pedido->estado === 'cancelado' ? '#f8d7da' : ($pedido->estado === 'enviado' ? '#cfe2ff' : '#fff3cd')) }};
                                        color: {{ $pedido->estado === 'entregado' ? '#155724' : ($pedido->estado === 'cancelado' ? '#721c24' : ($pedido->estado === 'enviado' ? '#084298' : '#856404')) }};
                                    ">
                                        {{ ucfirst($pedido->estado) }}
                                    </span>
                                </td>
                                <td style="padding: 12px; text-align: center;">
                                    <button onclick="toggleDetallesPedido(this, {{ $pedido->id }})" class="save-btn" style="padding: 6px 12px; font-size: 12px; margin-bottom: 5px;">
                                         Ver
                                    </button>
                                    <div class="detalles-pedido-{{ $pedido->id }}" style="display: none; margin-top: 10px; padding: 15px; background: #f9f9f9; border-radius: 8px; border: 1px solid #eee;">
                                        <div style="margin-bottom: 12px;">
                                            <label style="display: block; font-weight: 600; margin-bottom: 8px; font-size: 13px;">Cambiar estado:</label>
                                            <select id="estado-{{ $pedido->id }}" style="
                                                width: 100%;
                                                padding: 8px;
                                                border: 1px solid #ddd;
                                                border-radius: 4px;
                                                font-size: 13px;
                                                background: white;
                                                cursor: pointer;
                                            ">
                                                <option value="pendiente" {{ $pedido->estado === 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                                                <option value="confirmado" {{ $pedido->estado === 'confirmado' ? 'selected' : '' }}>Confirmado</option>
                                                <option value="enviado" {{ $pedido->estado === 'enviado' ? 'selected' : '' }}>Enviado</option>
                                                <option value="entregado" {{ $pedido->estado === 'entregado' ? 'selected' : '' }}>Entregado</option>
                                                <option value="cancelado" {{ $pedido->estado === 'cancelado' ? 'selected' : '' }}>Cancelado</option>
                                            </select>
                                        </div>
                                        <button onclick="guardarEstadoPedido({{ $pedido->id }})" class="save-btn" style="width: 100%; padding: 8px; font-size: 13px;">
                                            ✓ Guardar cambios
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
            <button onclick="closeModal('ventasModal')" class="cancel-btn" style="margin-top:20px; width: 100%;">Cerrar</button>
        </div>
    </div>

    <script>
        function toggleDetallesPedido(button, pedidoId) {
            const detalles = document.querySelector('.detalles-pedido-' + pedidoId);
            if (detalles.style.display === 'none') {
                detalles.style.display = 'block';
                button.textContent = '▼ Ocultar';
            } else {
                detalles.style.display = 'none';
                button.textContent = '📋 Ver';
            }
        }

        function guardarEstadoPedido(pedidoId) {
            const select = document.getElementById('estado-' + pedidoId);
            const nuevoEstado = select.value;

            fetch('/admin/pedido/' + pedidoId + '/estado', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                },
                body: JSON.stringify({
                    estado: nuevoEstado
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Éxito',
                        text: data.message,
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'No se pudo actualizar el estado'
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error de conexión',
                    text: 'No se pudo procesar la solicitud'
                });
            });
        }
    </script>

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