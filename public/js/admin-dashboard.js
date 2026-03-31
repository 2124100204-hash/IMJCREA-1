/**
 * Lógica del Dashboard Administrativo - Versión Mejorada
 */

/* ── MODALES ── */
function openModal(id) {
    const modal = document.getElementById(id);
    if (modal) { 
        modal.style.display = 'flex'; 
        document.body.style.overflow = 'hidden'; 
    }
}

function closeModal(id) {
    const modal = document.getElementById(id);
    if (modal) { 
        modal.style.display = 'none'; 
        document.body.style.overflow = 'auto'; 
    }
}
window.onclick = function (event) {
    if (event.target.classList.contains('modal')) {
        event.target.style.display = 'none';
        document.body.style.overflow = 'auto';
    }
};

function prepararEdicionLibro(btn) {
    const id = btn.getAttribute('data-id');

    const form = document.getElementById('formEditarLibro');
    if (form) form.action = `/admin/libro/actualizar/${id}`;

    const camposTexto = {
        'edit_titulo': 'data-titulo',
        'edit_descripcion': 'data-descripcion'
    };

    for (let [idInput, attr] of Object.entries(camposTexto)) {
        const input = document.getElementById(idInput);
        if (input) input.value = btn.getAttribute(attr) || '';
    }

    // 3. Llenar Selects (Autor y Categoría)
    const autorId = btn.getAttribute('data-autor');
    const catId = btn.getAttribute('data-categoria');

    const selectAutor = document.getElementById('edit_autor_id');
    const selectCat = document.getElementById('edit_categoria');

    if (selectAutor) selectAutor.value = autorId;
    if (selectCat) selectCat.value = catId;

    // 4. Limpiar y llenar Formatos (Físico, AR, VR)
    ['fisico', 'ar', 'vr'].forEach(f => {
        const check = document.getElementById(`edit_check_${f}`);
        const precio = document.getElementById(`edit_precio_${f}`);
        const stock = document.getElementById(`edit_stock_${f}`);
        
        if(check) check.checked = false;
        if(precio) precio.value = '';
        if(stock) stock.value = 0;
    });

    const formatosRaw = btn.getAttribute('data-formatos');
    if (formatosRaw) {
        try {
            const formatos = JSON.parse(formatosRaw);
            formatos.forEach(f => {
                const tipo = f.formato.toLowerCase();
                const check = document.getElementById(`edit_check_${tipo}`);
                const inputPrecio = document.getElementById(`edit_precio_${tipo}`);
                const inputStock = document.getElementById(`edit_stock_${tipo}`);

                if (check) {
                    check.checked = true;
                    if (inputPrecio) inputPrecio.value = f.precio;
                    if (inputStock) inputStock.value = f.stock;
                }
            });
        } catch (e) {
            console.error("Error al parsear formatos:", e);
        }
    }

    openModal('editarLibroModal');
}

/* ── EMPLEADOS ── */
function prepararEdicionEmpleadoDesdeBtn(btn) {
    const id = btn.dataset.id;
    const form = document.getElementById('editEmpleadoForm');
    
    if (form) form.action = `/admin/empleado/actualizar/${id}`;

    document.getElementById('emp_edit_nombre').value = btn.dataset.nombre || '';
    document.getElementById('emp_edit_correo').value = btn.dataset.correo || '';
    document.getElementById('emp_edit_puesto').value = btn.dataset.puesto || '';
    document.getElementById('emp_edit_salario').value = btn.dataset.salario || '';
    
    const deptoSelect = document.getElementById('emp_edit_departamento');
    if (deptoSelect) deptoSelect.value = btn.dataset.departamento || '';
    
    openModal('editarEmpleadoModal');
}

/* ── UTILIDADES ── */
function togglePrecioFormato(formato, visible) {
    const el = document.getElementById('precio-' + formato);
    if (el) el.style.display = visible ? 'block' : 'none';
}

document.addEventListener('DOMContentLoaded', function () {
    // Inicializar visibilidad de precios en "Nuevo Libro"
    ['fisico', 'ar', 'vr'].forEach(f => {
        const cb = document.getElementById('nuevo_check_' + f);
        if (cb) {
            cb.addEventListener('change', (e) => togglePrecioFormato(f, e.target.checked));
            if (cb.checked) togglePrecioFormato(f, true);
        }
    });
});