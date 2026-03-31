document.addEventListener('DOMContentLoaded', () => {
    const filterButtons = document.querySelectorAll('.filter-btn');
    const bookCards = document.querySelectorAll('#booksGrid .book-card');

    filterButtons.forEach(btn => {
        btn.addEventListener('click', () => {
            // 1. Manejar estado visual de los botones
            filterButtons.forEach(b => b.classList.remove('active'));
            btn.classList.add('active');

            const filterValue = btn.getAttribute('data-filter');

            // 2. Filtrar las tarjetas
            bookCards.forEach(card => {
                const cardType = card.getAttribute('data-type');

                if (filterValue === 'todos' || cardType === filterValue) {
                    card.style.display = 'block'; // O 'flex' según tu CSS
                } else {
                    card.style.display = 'none';
                }
            });
        });
    });
});


let libroActual = {};

function openModal(id) {
    const modal = document.getElementById(id);
    if(modal) modal.style.display = "flex";
}

function closeModal(id) {
    const modal = document.getElementById(id);
    if(modal) modal.style.display = "none";
}

function openBuyModal(libroId, titulo, precio) {
    libroActual = { id: libroId, titulo, precio: parseFloat(precio) };
    
    document.getElementById('modalLibroTitulo').textContent = titulo;
    document.getElementById('totalPrecio').textContent = libroActual.precio.toFixed(2);
    
    // Resetear textarea
    document.getElementById('direccionInput').value = '';
    
    // Generar formatos dinámicos
    const formatos = ['Digital', 'VR', 'Físico'];
    const container = document.getElementById('formatosContainer');
    container.innerHTML = '';
    
    formatos.forEach(f => {
        const btn = document.createElement('button');
        btn.className = 'format-option'; // Asegúrate de darle estilo en CSS
        btn.textContent = f;
        btn.onclick = () => {
            libroActual.formato = f;
            // Lógica visual de selección aquí
        };
        container.appendChild(btn);
    });

    openModal('buyModal');
}

function confirmarCompra() {
    const direccion = document.getElementById('direccionInput').value;
    if (!direccion.trim()) return alert("Por favor, ingresa los datos de entrega.");
    
    alert(`¡Éxito! Has adquirido "${libroActual.titulo}" en formato ${libroActual.formato}.`);
    closeModal('buyModal');
}

window.onclick = (e) => {
    if (e.target.classList.contains('modal')) e.target.style.display = "none";
}

function openPedidosModal() {
    document.getElementById('pedidosModal').style.display = "flex";
}

function closePedidosModal() {
    document.getElementById('pedidosModal').style.display = "none";
}

function openDevolucionModal(detalleId, titulo, cantidadMaxima) {
    document.getElementById('pedidoDetalleId').value = detalleId;
    document.getElementById('libroTitulo').value = titulo;
    document.getElementById('cantidadDevuelta').max = cantidadMaxima;
    document.getElementById('cantidadDevuelta').value = 1;
    document.getElementById('devolucionModal').style.display = "flex";
}

function closeDevolucionModal() {
    document.getElementById('devolucionModal').style.display = "none";
}

function submitDevolucion(event) {
    event.preventDefault();
    
    const formData = new FormData(event.target);
    
    fetch('/devolver', {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast('Solicitud Enviada', 'Tu solicitud de devolución ha sido enviada exitosamente.', 'success');
            closeDevolucionModal();
            // Recargar la página para actualizar el estado
            setTimeout(() => location.reload(), 2000);
        } else {
            showToast('Error', 'Hubo un problema al procesar tu solicitud.', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('Error', 'Hubo un problema al procesar tu solicitud.', 'error');
    });
}
// Función para crear el Toast dinámicamente
function showToast(title, message, type = 'success') {
    const container = document.getElementById('toast-container');
    const toast = document.createElement('div');
    toast.className = `toast ${type}`;
    
    const icon = type === 'success' ? 'fa-circle-check' : 'fa-circle-exclamation';
    
    toast.innerHTML = `
        <div class="toast-icon"><i class="fa-solid ${icon}"></i></div>
        <div class="toast-content">
            <h4>${title}</h4>
            <p>${message}</p>
        </div>
    `;

    container.appendChild(toast);

    // Desaparecer después de 4 segundos
    setTimeout(() => {
        toast.style.animation = 'fadeOut 0.5s forwards';
        setTimeout(() => toast.remove(), 500);
    }, 4000);
}

// Confirmación de compra actualizada
function confirmarCompra() {
    const direccion = document.getElementById('direccionInput').value;
    
    if (!direccion.trim()) {
        showToast('Error de validación', 'Por favor, ingresa los datos de entrega.', 'error');
        return;
    }
    
    // Aquí iría tu petición fetch/axios al servidor
    // Simulamos éxito:
    showToast(
        '¡Adquisición Exitosa!', 
        `"${libroActual.titulo}" (${libroActual.formato}) se ha añadido a tu biblioteca.`,
        'success'
    );
    
    closeModal('buyModal');
    document.getElementById('direccionInput').value = ''; // Limpiar campo
}