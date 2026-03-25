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
                <button type="submit" class="cancel-btn">Cerrar Sesión</button>
            </form>
        </div>
    </div>

    @if(session('success'))
        <div style="background:#d4edda;color:#155724;padding:12px 20px;border-radius:8px;margin-bottom:16px;border:1px solid #c3e6cb;">✅ {{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div style="background:#f8d7da;color:#721c24;padding:12px 20px;border-radius:8px;margin-bottom:16px;border:1px solid #f5c6cb;">❌ {{ session('error') }}</div>
    @endif

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
    </div>

    {{-- ══ LIBROS ══ --}}
    <div id="librosModal" class="modal">
        <div class="modal-content-large">
            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px;">
                <h2 class="modal-title-font" style="margin:0;">📚 Biblioteca Central</h2>
                <button onclick="openModal('nuevoLibroModal')" class="save-btn" style="padding:8px 16px;font-size:14px;">+ Nuevo Libro</button>
            </div>
            <div class="table-container">
                <table>
                    <thead><tr><th>Título</th><th>Categoría</th><th>Autor</th><th>Formatos</th><th>Precio desde</th><th style="text-align:right;">Acciones</th></tr></thead>
                    <tbody>
                        @foreach(\App\Models\Libro::with(['autor','categoria','formatos'])->get() as $libro)
                        <tr>
                            <td class="td-bold">{{ $libro->titulo }}</td>
                            <td class="td-muted">{{ $libro->categoria->nombre ?? 'N/A' }}</td>
                            <td class="td-muted">{{ $libro->autor->nombre ?? 'Sin Autor' }}</td>
                            <td class="td-muted">
                                @foreach($libro->formatos as $fmt)
                                    <span style="font-size:11px;padding:2px 7px;border-radius:10px;background:#f0f0f0;margin-right:3px;">{{ strtoupper($fmt->formato) }}</span>
                                @endforeach
                            </td>
                            <td class="td-muted">${{ number_format($libro->formatos->min('precio') ?? 0, 2) }}</td>
                            <td class="td-actions" style="text-align:right;">
                                <button class="btn-edit"
                                    data-id="{{ $libro->id }}" data-titulo="{{ $libro->titulo }}"
                                    data-descripcion="{{ $libro->descripcion }}" data-edad="{{ $libro->nivel_edad }}"
                                    data-duracion="{{ $libro->duracion }}" data-autor="{{ $libro->autor_id }}"
                                    data-categoria="{{ $libro->categoria_id ?? '' }}"
                                    data-precio="{{ $libro->formatos->first()->precio ?? 0 }}"
                                    data-formatos="{{ $libro->formatos->pluck('formato')->implode(',') }}"
                                    onclick="prepararEdicionDesdeBtn(this)">Editar</button>
                                <form action="{{ route('admin.libro.eliminar', $libro->id) }}" method="POST" style="display:inline;">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn-delete" onclick="return confirm('¿Eliminar libro?')">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <button onclick="closeModal('librosModal')" class="close-btn" style="margin-top:20px;">Cerrar</button>
        </div>
    </div>

    {{-- ══ NUEVO LIBRO ══ --}}
    <div id="nuevoLibroModal" class="modal">
        <div class="modal-content-form">
            <h2 class="aside-title" style="font-family:'Playfair Display';margin-bottom:25px;">📖 Nuevo Libro</h2>
            <form action="{{ route('admin.libro.crear') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label class="form-label">Título <span style="color:#e53e3e;">*</span></label>
                    <input type="text" name="titulo" class="form-input" placeholder="Ej: El Principito" required value="{{ old('titulo') }}">
                </div>
                <div class="form-group">
                    <label class="form-label">Autor <span style="color:#e53e3e;">*</span></label>
                    <div style="display:flex;gap:10px;">
                        <select name="autor_id" class="form-input" style="flex:1;" required>
                            <option value="">— Seleccionar autor —</option>
                            @foreach(\App\Models\Autor::orderBy('nombre')->get() as $autor)
                                <option value="{{ $autor->id }}" {{ old('autor_id') == $autor->id ? 'selected' : '' }}>{{ $autor->nombre }}</option>
                            @endforeach
                        </select>
                        <button type="button" onclick="openModal('nuevoAutorModal')" class="save-btn" style="padding:0 14px;font-size:12px;white-space:nowrap;">+ Nuevo</button>
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Categoría</label>
                    <select name="categoria_id" class="form-input">
                        <option value="">— Sin categoría —</option>
                        @foreach(\App\Models\Categoria::orderBy('nombre')->get() as $cat)
                            <option value="{{ $cat->id }}" {{ old('categoria_id') == $cat->id ? 'selected' : '' }}>{{ $cat->nombre }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Descripción</label>
                    <textarea name="descripcion" class="form-input" rows="3" placeholder="Breve sinopsis...">{{ old('descripcion') }}</textarea>
                </div>
                <div style="display:flex;gap:15px;">
                    <div class="form-group" style="flex:1;"><label class="form-label">Nivel de Edad</label><input type="text" name="nivel_edad" class="form-input" placeholder="Ej: 12" value="{{ old('nivel_edad') }}"></div>
                    <div class="form-group" style="flex:1;"><label class="form-label">Duración (min)</label><input type="number" name="duracion" class="form-input" placeholder="Ej: 60" min="0" value="{{ old('duracion') }}"></div>
                </div>
                <div class="form-group">
                    <label class="form-label">Formatos Disponibles <span style="color:#e53e3e;">*</span></label>
                    <div style="display:flex;gap:16px;flex-wrap:wrap;padding:12px;background:#f9f9f9;border-radius:8px;border:1px solid #e8e8e8;">
                        <label style="display:flex;align-items:center;gap:6px;cursor:pointer;"><input type="checkbox" name="formatos[]" value="fisico" id="nuevo_check_fisico" {{ in_array('fisico', old('formatos', [])) ? 'checked' : '' }} onchange="togglePrecioFormato('fisico', this.checked)"> <span>📚 Físico</span></label>
                        <label style="display:flex;align-items:center;gap:6px;cursor:pointer;"><input type="checkbox" name="formatos[]" value="ar" id="nuevo_check_ar" {{ in_array('ar', old('formatos', [])) ? 'checked' : '' }} onchange="togglePrecioFormato('ar', this.checked)"> <span>📷 Realidad Aumentada</span></label>
                        <label style="display:flex;align-items:center;gap:6px;cursor:pointer;"><input type="checkbox" name="formatos[]" value="vr" id="nuevo_check_vr" {{ in_array('vr', old('formatos', [])) ? 'checked' : '' }} onchange="togglePrecioFormato('vr', this.checked)"> <span>🥽 Realidad Virtual</span></label>
                    </div>
                </div>
                <div id="precios-formatos" style="display:flex;flex-direction:column;gap:10px;">
                    <div id="precio-fisico" style="display:none;"><div class="form-group" style="margin-bottom:0;"><label class="form-label"><span style="color:#e8820c;">📚</span> Precio Físico</label><div style="display:flex;gap:10px;"><input type="number" name="precio_fisico" class="form-input" placeholder="0.00" min="0" step="0.01" style="flex:1;"><input type="number" name="stock_fisico" class="form-input" placeholder="Stock" min="0" value="999" style="flex:1;"></div></div></div>
                    <div id="precio-ar" style="display:none;"><div class="form-group" style="margin-bottom:0;"><label class="form-label"><span style="color:#0d7a6e;">📷</span> Precio AR</label><div style="display:flex;gap:10px;"><input type="number" name="precio_ar" class="form-input" placeholder="0.00" min="0" step="0.01" style="flex:1;"><input type="number" name="stock_ar" class="form-input" placeholder="Stock" min="0" value="999" style="flex:1;"></div></div></div>
                    <div id="precio-vr" style="display:none;"><div class="form-group" style="margin-bottom:0;"><label class="form-label"><span style="color:#c94f6d;">🥽</span> Precio VR</label><div style="display:flex;gap:10px;"><input type="number" name="precio_vr" class="form-input" placeholder="0.00" min="0" step="0.01" style="flex:1;"><input type="number" name="stock_vr" class="form-input" placeholder="Stock" min="0" value="999" style="flex:1;"></div></div></div>
                </div>
                <div class="btn-group" style="margin-top:24px;">
                    <button type="button" onclick="closeModal('nuevoLibroModal')" class="cancel-btn">Cancelar</button>
                    <button type="submit" class="btn-save">✅ Crear Libro</button>
                </div>
            </form>
        </div>
    </div>

    {{-- ══ EDITAR LIBRO ══ --}}
    <div id="editarLibroModal" class="modal">
        <div class="modal-content-form">
            <h2 class="aside-title" style="font-family:'Playfair Display';margin-bottom:25px;">Editar Libro</h2>
            <form id="editForm" method="POST">
                @csrf @method('PUT')
                <div class="form-group"><label class="form-label">Título</label><input type="text" name="titulo" id="edit_titulo" class="form-input" required></div>
                <div class="form-group"><label class="form-label">Categoría</label>
                    <select name="categoria_id" id="edit_categoria" class="form-input"><option value="">Seleccionar Categoría</option>@foreach(\App\Models\Categoria::all() as $cat)<option value="{{ $cat->id }}">{{ $cat->nombre }}</option>@endforeach</select>
                </div>
                <div class="form-group"><label class="form-label">Formatos</label>
                    <div style="display:flex;gap:20px;padding:10px;background:#f9f9f9;border-radius:8px;">
                        <label><input type="checkbox" name="formatos[]" value="fisico" id="check_fisico"> Físico</label>
                        <label><input type="checkbox" name="formatos[]" value="vr" id="check_vr"> VR</label>
                        <label><input type="checkbox" name="formatos[]" value="ar" id="check_ar"> AR</label>
                    </div>
                </div>
                <div class="form-group"><label class="form-label">Descripción</label><textarea name="descripcion" id="edit_descripcion" class="form-input" rows="3"></textarea></div>
                <div style="display:flex;gap:15px;">
                    <div class="form-group" style="flex:1;"><label class="form-label">Nivel Edad</label><input type="text" name="nivel_edad" id="edit_nivel_edad" class="form-input"></div>
                    <div class="form-group" style="flex:1;"><label class="form-label">Duración (min)</label><input type="text" name="duracion" id="edit_duracion" class="form-input"></div>
                </div>
                <div class="form-group"><label class="form-label">Precio</label><input type="number" name="precio" id="edit_precio" class="form-input" min="0" step="0.01"></div>
                <div class="form-group"><label class="form-label">Autor</label>
                    <div style="display:flex;gap:10px;">
                        <select name="autor_id" id="edit_autor" class="form-input" style="flex:1;">@foreach(\App\Models\Autor::all() as $autor)<option value="{{ $autor->id }}">{{ $autor->nombre }}</option>@endforeach</select>
                        <button type="button" onclick="openModal('nuevoAutorModal')" class="save-btn" style="padding:0 15px;font-size:12px;">+ Nuevo</button>
                    </div>
                </div>
                <div class="btn-group">
                    <button type="button" onclick="closeModal('editarLibroModal')" class="cancel-btn">Cancelar</button>
                    <button type="submit" class="btn-save">Guardar Cambios</button>
                </div>
            </form>
        </div>
    </div>

    {{-- ══ NUEVO AUTOR ══ --}}
    <div id="nuevoAutorModal" class="modal" style="background:rgba(0,0,0,0.8);z-index:2000;">
        <div class="modal-content-form" style="max-width:400px;margin-top:100px;">
            <h3 class="modal-title-font">Agregar Nuevo Autor</h3>
            <form action="{{ route('admin.autor.crear') }}" method="POST">
                @csrf
                <div class="form-group"><label class="form-label">Nombre Completo</label><input type="text" name="nombre" class="form-input" placeholder="Ej: Gabriel García Márquez" required></div>
                <div class="btn-group">
                    <button type="button" onclick="closeModal('nuevoAutorModal')" class="close-btn">Cerrar</button>
                    <button type="submit" class="btn-save">Registrar</button>
                </div>
            </form>
        </div>
    </div>

    {{-- ══ EMPLEADOS ══ --}}
    <div id="empleadosModal" class="modal">
        <div class="modal-content-large">
            @php
                $empleados = \App\Models\Empleado::with('usuario')->get();
                $deptoConfig = [
                    'Ventas'         => ['bg'=>'#E6F1FB','color'=>'#0C447C','label'=>'Ventas libro'],
                    'Experiencias'   => ['bg'=>'#E1F5EE','color'=>'#085041','label'=>'Experiencias'],
                    'Administración' => ['bg'=>'#FAEEDA','color'=>'#633806','label'=>'Administración'],
                    'Bodega'         => ['bg'=>'#F1EFE8','color'=>'#444441','label'=>'Bodega'],
                ];
            @endphp

            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px;">
                <div>
                    <h2 class="modal-title-font" style="margin:0;">👥 Gestión de Personal</h2>
                    <p style="font-size:12px;color:#999;margin-top:3px;">Empleados registrados en el sistema</p>
                </div>
                <button onclick="openModal('nuevoEmpleadoModal')" class="save-btn" style="padding:8px 16px;font-size:14px;">+ Nuevo Empleado</button>
            </div>

            <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:10px;margin-bottom:20px;">
                <div style="background:#f7f7f5;border-radius:8px;padding:12px 14px;">
                    <div style="font-size:11px;color:#999;margin-bottom:4px;">Total empleados</div>
                    <div style="font-size:22px;font-weight:700;">{{ $empleados->count() }}</div>
                    <div style="font-size:11px;color:#bbb;margin-top:2px;">En nómina activa</div>
                </div>
                <div style="background:#f7f7f5;border-radius:8px;padding:12px 14px;">
                    <div style="font-size:11px;color:#999;margin-bottom:4px;">Ventas libro</div>
                    <div style="font-size:22px;font-weight:700;">{{ $empleados->where('departamento','Ventas')->count() }}</div>
                    <div style="font-size:11px;color:#bbb;margin-top:2px;">Asesores activos</div>
                </div>
                <div style="background:#f7f7f5;border-radius:8px;padding:12px 14px;">
                    <div style="font-size:11px;color:#999;margin-bottom:4px;">Experiencias AR/VR</div>
                    <div style="font-size:22px;font-weight:700;">{{ $empleados->where('departamento','Experiencias')->count() }}</div>
                    <div style="font-size:11px;color:#bbb;margin-top:2px;">Guías de sesión</div>
                </div>
                <div style="background:#f7f7f5;border-radius:8px;padding:12px 14px;">
                    <div style="font-size:11px;color:#999;margin-bottom:4px;">Nómina mensual</div>
                    <div style="font-size:22px;font-weight:700;">${{ number_format($empleados->sum('salario'), 0) }}</div>
                    <div style="font-size:11px;color:#bbb;margin-top:2px;">Total MXN</div>
                </div>
            </div>

            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Empleado</th><th>Correo</th><th>Puesto</th><th>Departamento</th><th>Teléfono</th><th>Salario</th><th style="text-align:right;">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($empleados as $emp)
                            @php
                                $dc = $deptoConfig[$emp->departamento] ?? ['bg'=>'#f0f0f0','color'=>'#555','label'=> $emp->departamento ?? 'N/A'];
                                $palabras = explode(' ', trim($emp->nombre));
                                $iniciales = strtoupper(substr($palabras[0] ?? '', 0, 1) . substr($palabras[1] ?? '', 0, 1));
                            @endphp
                            <tr>
                                <td>
                                    <div style="display:flex;align-items:center;gap:10px;">
                                        <div style="width:34px;height:34px;border-radius:50%;background:{{ $dc['bg'] }};color:{{ $dc['color'] }};display:flex;align-items:center;justify-content:center;font-size:11px;font-weight:700;flex-shrink:0;">{{ $iniciales }}</div>
                                        <div>
                                            <div style="font-weight:600;font-size:13px;">{{ $emp->nombre }}</div>
                                            <div style="font-size:11px;color:#aaa;">#EMP-{{ str_pad($emp->id, 3, '0', STR_PAD_LEFT) }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td style="font-size:12px;color:#555;">{{ $emp->usuario->email ?? '—' }}</td>
                                <td style="font-size:13px;color:#555;">{{ $emp->puesto ?? '—' }}</td>
                                <td>
                                    <span style="background:{{ $dc['bg'] }};color:{{ $dc['color'] }};font-size:11px;padding:3px 10px;border-radius:20px;font-weight:600;">{{ $dc['label'] }}</span>
                                </td>
                                <td style="font-size:12px;color:#777;">{{ $emp->telefono ?? '—' }}</td>
                                <td style="font-size:13px;font-weight:600;">${{ number_format($emp->salario ?? 0, 0) }}</td>
                                <td style="text-align:right;">
                                    <button class="btn-edit"
                                        data-id="{{ $emp->id }}"
                                        data-nombre="{{ $emp->nombre }}"
                                        data-correo="{{ $emp->usuario->email ?? '' }}"
                                        data-puesto="{{ $emp->puesto }}"
                                        data-departamento="{{ $emp->departamento }}"
                                        data-salario="{{ $emp->salario }}"
                                        data-telefono="{{ $emp->telefono }}"
                                        data-curp="{{ $emp->curp }}"
                                        data-domicilio="{{ $emp->domicilio }}"
                                        onclick="prepararEdicionEmpleadoDesdeBtn(this)">Editar</button>
                                    <form action="{{ route('admin.empleado.eliminar', $emp->id) }}" method="POST" style="display:inline;">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn-delete" onclick="return confirm('¿Eliminar a {{ addslashes($emp->nombre) }}?')">Eliminar</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="7" style="text-align:center;color:#aaa;padding:40px;">No hay empleados registrados aún.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <button onclick="closeModal('empleadosModal')" class="close-btn" style="margin-top:20px;">Cerrar</button>
        </div>
    </div>

    {{-- ══ NUEVO EMPLEADO ══ --}}
    <div id="nuevoEmpleadoModal" class="modal">
        <div class="modal-content-form">
            <h2 class="aside-title" style="font-family:'Playfair Display';margin-bottom:25px;">👤 Nuevo Empleado</h2>
            <form action="{{ route('admin.empleado.crear') }}" method="POST">
                @csrf
                <div style="display:flex;gap:15px;">
                    <div class="form-group" style="flex:1;"><label class="form-label">Nombre completo <span style="color:#e53e3e;">*</span></label><input type="text" name="nombre" class="form-input" placeholder="Ej: Laura Pérez" required value="{{ old('nombre') }}"></div>
                    <div class="form-group" style="flex:1;"><label class="form-label">Teléfono</label><input type="text" name="telefono" class="form-input" placeholder="Ej: 3312345678" maxlength="15" value="{{ old('telefono') }}"></div>
                </div>
                <div style="display:flex;gap:15px;">
                    <div class="form-group" style="flex:1;"><label class="form-label">Correo electrónico <span style="color:#e53e3e;">*</span></label><input type="email" name="email" class="form-input" placeholder="correo@inmersia.com" required value="{{ old('email') }}"></div>
                    <div class="form-group" style="flex:1;"><label class="form-label">Contraseña <span style="color:#e53e3e;">*</span></label><input type="password" name="password" class="form-input" placeholder="Mínimo 6 caracteres" required></div>
                </div>
                <div class="form-group"><label class="form-label">CURP</label><input type="text" name="curp" class="form-input" placeholder="Ej: PELP850101HDFRRR09" maxlength="18" style="text-transform:uppercase;" oninput="this.value=this.value.toUpperCase()" value="{{ old('curp') }}"></div>
                <div class="form-group"><label class="form-label">Domicilio</label><textarea name="domicilio" class="form-input" rows="2" placeholder="Calle, número, colonia, ciudad">{{ old('domicilio') }}</textarea></div>
                <div style="display:flex;gap:15px;">
                    <div class="form-group" style="flex:1;"><label class="form-label">Puesto <span style="color:#e53e3e;">*</span></label><input type="text" name="puesto" class="form-input" placeholder="Ej: Guía de experiencia VR" required value="{{ old('puesto') }}"></div>
                    <div class="form-group" style="flex:1;"><label class="form-label">Departamento <span style="color:#e53e3e;">*</span></label>
                        <select name="departamento" class="form-input" required>
                            <option value="">— Seleccionar —</option>
                            <option value="Ventas" {{ old('departamento')==='Ventas' ? 'selected' : '' }}>📚 Ventas libro</option>
                            <option value="Experiencias" {{ old('departamento')==='Experiencias' ? 'selected' : '' }}>🥽 Experiencias AR/VR</option>
                            <option value="Administración" {{ old('departamento')==='Administración' ? 'selected' : '' }}>🗂 Administración</option>
                            <option value="Bodega" {{ old('departamento')==='Bodega' ? 'selected' : '' }}>📦 Bodega</option>
                        </select>
                    </div>
                </div>
                <div class="form-group"><label class="form-label">Salario mensual (MXN)</label><input type="number" name="salario" class="form-input" placeholder="Ej: 9500" min="0" step="100" value="{{ old('salario') }}"></div>
                <div class="btn-group" style="margin-top:24px;">
                    <button type="button" onclick="closeModal('nuevoEmpleadoModal')" class="cancel-btn">Cancelar</button>
                    <button type="submit" class="btn-save">✅ Registrar Empleado</button>
                </div>
            </form>
        </div>
    </div>

    {{-- ══ EDITAR EMPLEADO ══ --}}
    <div id="editarEmpleadoModal" class="modal">
        <div class="modal-content-form" style="max-width:520px;">
            <h2 class="aside-title" style="font-family:'Playfair Display';margin-bottom:25px;">✏️ Editar Empleado</h2>
            <form id="editEmpleadoForm" method="POST">
                @csrf @method('PUT')
                <div style="display:flex;gap:15px;">
                    <div class="form-group" style="flex:1;"><label class="form-label">Nombre completo</label><input type="text" name="nombre" id="emp_edit_nombre" class="form-input" required></div>
                    <div class="form-group" style="flex:1;"><label class="form-label">Teléfono</label><input type="text" name="telefono" id="emp_edit_telefono" class="form-input" maxlength="15"></div>
                </div>
                <div style="display:flex;gap:15px;">
                    <div class="form-group" style="flex:1;"><label class="form-label">Correo electrónico</label><input type="email" name="email" id="emp_edit_correo" class="form-input" required></div>
                    <div class="form-group" style="flex:1;"><label class="form-label">Nueva contraseña <span style="color:#aaa;font-size:11px;">(vacío = sin cambio)</span></label><input type="password" name="password" class="form-input" placeholder="••••••"></div>
                </div>
                <div class="form-group"><label class="form-label">CURP</label><input type="text" name="curp" id="emp_edit_curp" class="form-input" maxlength="18" style="text-transform:uppercase;" oninput="this.value=this.value.toUpperCase()"></div>
                <div class="form-group"><label class="form-label">Domicilio</label><textarea name="domicilio" id="emp_edit_domicilio" class="form-input" rows="2"></textarea></div>
                <div style="display:flex;gap:15px;">
                    <div class="form-group" style="flex:1;"><label class="form-label">Puesto</label><input type="text" name="puesto" id="emp_edit_puesto" class="form-input" required></div>
                    <div class="form-group" style="flex:1;"><label class="form-label">Departamento</label>
                        <select name="departamento" id="emp_edit_departamento" class="form-input" required>
                            <option value="">— Seleccionar —</option>
                            <option value="Ventas">📚 Ventas libro</option>
                            <option value="Experiencias">🥽 Experiencias AR/VR</option>
                            <option value="Administración">🗂 Administración</option>
                            <option value="Bodega">📦 Bodega</option>
                        </select>
                    </div>
                </div>
                <div class="form-group"><label class="form-label">Salario mensual (MXN)</label><input type="number" name="salario" id="emp_edit_salario" class="form-input" min="0" step="100"></div>
                <div class="btn-group" style="margin-top:24px;">
                    <button type="button" onclick="closeModal('editarEmpleadoModal')" class="cancel-btn">Cancelar</button>
                    <button type="submit" class="btn-save">Guardar Cambios</button>
                </div>
            </form>
        </div>
    </div>

    {{-- ══ CONTACTO ══ --}}
    <div id="contactoModal" class="modal">
        <div class="modal-content-large">
            <h2 class="modal-title-font">📧 Mensajes de Contacto</h2>
            <div class="table-container">
                <table>
                    <thead><tr><th>Remitente</th><th>Correo</th><th>Asunto</th><th>Mensaje</th><th>Fecha</th><th>Acción</th></tr></thead>
                    <tbody>
                        @forelse(\App\Models\Contacto::latest()->get() as $msg)
                        <tr>
                            <td class="td-bold">{{ $msg->name }}</td>
                            <td>{{ $msg->email }}</td>
                            <td>{{ $msg->asunto ?? '---' }}</td>
                            <td style="max-width:240px;white-space:pre-wrap;word-break:break-word;">{{ $msg->message }}</td>
                            <td>{{ $msg->created_at->diffForHumans() }}</td>
                            <td><button class="btn-edit" onclick="verMensajeContacto(@json($msg))">Ver</button></td>
                        </tr>
                        @empty
                        <tr><td colspan="6" style="text-align:center;">No hay mensajes.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <button onclick="closeModal('contactoModal')" class="close-btn">Cerrar</button>
        </div>
    </div>

    <div id="contactoDetalleModal" class="modal">
        <div class="modal-content-form" style="max-width:500px;width:95%;">
            <h3 class="modal-title-font">Mensaje Completo</h3>
            <div class="form-group"><strong>Remitente:</strong> <span id="contacto_nombre"></span></div>
            <div class="form-group"><strong>Correo:</strong> <span id="contacto_email"></span></div>
            <div class="form-group"><strong>Asunto:</strong> <span id="contacto_asunto"></span></div>
            <div class="form-group"><strong>Mensaje:</strong><div id="contacto_mensaje" style="padding:10px;background:#fff;border-radius:6px;border:1px solid #ccc;white-space:pre-wrap;margin-top:6px;"></div></div>
            <div class="btn-group"><button onclick="closeModal('contactoDetalleModal')" class="cancel-btn">Cerrar</button></div>
        </div>
    </div>

</div>
<script src="{{ asset('js/admin-dashboard.js') }}"></script>
</body>
</html>