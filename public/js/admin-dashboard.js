/**
 * Lógica del Dashboard Administrativo
 */

// Abrir cualquier modal por ID
function openModal(id) {
    const modal = document.getElementById(id);
    if (modal) {
        modal.style.display = 'flex';
        document.body.style.overflow = 'hidden'; // Evita scroll de fondo
    }
}

// Cerrar cualquier modal por ID
function closeModal(id) {
    const modal = document.getElementById(id);
    if (modal) {
        modal.style.display = 'none';
        document.body.style.overflow = 'auto';
    }
}
function prepararEdicionEmpleado(id, nombre, email) { 
    const form = document.getElementById('editEmpleadoForm');
    if (form) {
        form.action = `/admin/empleado/actualizar/${id}`;
        
        document.getElementById('emp_edit_nombre').value = nombre;
        document.getElementById('emp_edit_correo').value = email; 
        
        openModal('editarEmpleadoModal');
    }
}
function prepararEdicion(id, titulo, descripcion, edad, duracion, autorId, catId, formatosArr = []) {
    const form = document.getElementById('editForm');
    // Ajustamos la URL para que use el ID
    form.action = `/admin/libro/actualizar/${id}`;
    
    // Llenar datos
    document.getElementById('edit_titulo').value = titulo;
    document.getElementById('edit_descripcion').value = descripcion;
    document.getElementById('edit_nivel_edad').value = edad;
    document.getElementById('edit_duracion').value = duracion;
    document.getElementById('edit_autor').value = autorId;
    document.getElementById('edit_categoria').value = catId;

    // Desmarcar todos y luego marcar los que vienen del libro
    ['fisico', 'vr', 'ar'].forEach(f => {
        document.getElementById(`check_${f}`).checked = formatosArr.includes(f);
    });

    openModal('editarLibroModal');
}
// Cerrar modal si el usuario hace clic fuera del contenido blanco
window.onclick = function(event) {
    if (event.target.classList.contains('modal')) {
        event.target.style.display = "none";
        document.body.style.overflow = "auto";
    }
};
