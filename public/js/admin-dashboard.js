/**
 * Lógica del Dashboard Administrativo
 */

/* ── MODALES ── */
function openModal(id) {
    const modal = document.getElementById(id);
    if (modal) { modal.style.display = 'flex'; document.body.style.overflow = 'hidden'; }
}
function closeModal(id) {
    const modal = document.getElementById(id);
    if (modal) { modal.style.display = 'none'; document.body.style.overflow = 'auto'; }
}
window.onclick = function (event) {
    if (event.target.classList.contains('modal')) {
        event.target.style.display = 'none';
        document.body.style.overflow = 'auto';
    }
};

/* ── LIBROS ── */
function prepararEdicionDesdeBtn(btn) {
    const formatos = btn.dataset.formatos ? btn.dataset.formatos.split(',') : [];
    prepararEdicion(btn.dataset.id, btn.dataset.titulo, btn.dataset.descripcion,
        btn.dataset.edad, btn.dataset.duracion, btn.dataset.autor,
        btn.dataset.categoria, btn.dataset.precio, formatos);
}

function prepararEdicion(id, titulo, descripcion, edad, duracion, autorId, catId, precio, formatosArr = []) {
    const form = document.getElementById('editForm');
    if (!form) return;
    form.action = `/admin/libro/actualizar/${id}`;
    document.getElementById('edit_titulo').value      = titulo      || '';
    document.getElementById('edit_descripcion').value = descripcion || '';
    document.getElementById('edit_nivel_edad').value  = edad        || '';
    document.getElementById('edit_duracion').value    = duracion    || '';
    document.getElementById('edit_precio').value      = precio      || '';
    const autorSelect = document.getElementById('edit_autor');
    if (autorSelect) autorSelect.value = autorId || '';
    const catSelect = document.getElementById('edit_categoria');
    if (catSelect) catSelect.value = catId || '';
    ['fisico', 'vr', 'ar'].forEach(f => {
        const cb = document.getElementById(`check_${f}`);
        if (cb) cb.checked = formatosArr.includes(f);
    });
    openModal('editarLibroModal');
}

function togglePrecioFormato(formato, visible) {
    const el = document.getElementById('precio-' + formato);
    if (el) el.style.display = visible ? 'block' : 'none';
}

/* ── EMPLEADOS ── */
function prepararEdicionEmpleadoDesdeBtn(btn) {
    prepararEdicionEmpleado(
        btn.dataset.id,
        btn.dataset.nombre,
        btn.dataset.correo,
        btn.dataset.puesto,
        btn.dataset.departamento,
        btn.dataset.salario
    );
}

function prepararEdicionEmpleado(id, nombre, email, puesto, departamento, salario) {
    const form = document.getElementById('editEmpleadoForm');
    if (!form) return;
    form.action = `/admin/empleado/actualizar/${id}`;
    document.getElementById('emp_edit_nombre').value  = nombre       || '';
    document.getElementById('emp_edit_correo').value  = email        || '';
    document.getElementById('emp_edit_puesto').value  = puesto       || '';
    document.getElementById('emp_edit_salario').value = salario      || '';
    const deptoSelect = document.getElementById('emp_edit_departamento');
    if (deptoSelect) deptoSelect.value = departamento || '';
    openModal('editarEmpleadoModal');
}

/* ── CONTACTO ── */
function verMensajeContacto(contacto) {
    document.getElementById('contacto_nombre').innerText  = contacto.name    || 'Sin nombre';
    document.getElementById('contacto_email').innerText   = contacto.email   || 'Sin correo';
    document.getElementById('contacto_asunto').innerText  = contacto.asunto  || 'Sin asunto';
    document.getElementById('contacto_mensaje').innerText = contacto.message || 'Sin mensaje';
    openModal('contactoDetalleModal');
}

/* ── INIT ── */
document.addEventListener('DOMContentLoaded', function () {
    ['fisico', 'ar', 'vr'].forEach(f => {
        const cb = document.getElementById('nuevo_check_' + f);
        if (cb && cb.checked) togglePrecioFormato(f, true);
    });
});