/**
 * ARCHIVO: details.js
 * Sistema unificado de Carrito, Favoritos, Cupones y Pagos
 */

// 1. CONFIGURACIÓN DE INTERFAZ
const swalConfig = {
    confirmButtonColor: '#e8820c',
    cancelButtonColor: '#0d7a6e',
    background: '#fffcf7',
    color: '#1a1208',
    customClass: {
        popup: 'swal-custom-popup',
        title: 'swal-custom-title',
        confirmButton: 'swal-custom-confirm',
        cancelButton: 'swal-custom-cancel'
    }
};

// 2. UTILIDADES
function generateIdempotencyKey() {
    return 'idemp_' + Date.now() + '_' + Math.random().toString(36).substr(2, 9);
}

function mostrarLoginModal() { document.getElementById('loginModal').classList.add('show'); }
function cerrarLoginModal() { document.getElementById('loginModal').classList.remove('show'); }

window.onclick = function(event) {
    const modal = document.getElementById('loginModal');
    if (event.target === modal) modal.classList.remove('show');
};

// Selección de formato de libro
document.querySelectorAll('.formato-card').forEach(card => {
    card.addEventListener('click', function() {
        document.querySelectorAll('.formato-card').forEach(c => c.classList.remove('selected'));
        this.classList.add('selected');
    });
});
document.querySelector('.formato-card')?.classList.add('selected');

// 3. GESTIÓN DE FAVORITOS
if (window.isAuthenticated && window.routes?.favoritosObtener) {
    fetch(window.routes.favoritosObtener)
        .then(response => response.json())
        .then(data => {
            const libroId = window.libroId;
            if (data.favoritos && data.favoritos.includes(libroId)) {
                const btn = document.querySelector('.btn-secondary');
                if(btn) {
                    btn.innerHTML = '<i class="fa fa-heart"></i> En Favoritos';
                    btn.classList.add('favorito-agregado');
                }
            }
        })
        .catch(error => console.log('Error al verificar favoritos'));
}

function agregarAFavoritos() {
    fetch(window.routes.favoritoAgregar, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
        },
        body: JSON.stringify({ libro_id: window.libroId })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const btn = document.querySelector('.btn-secondary');
            btn.innerHTML = '<i class="fa fa-heart"></i> En Favoritos';
            btn.classList.add('favorito-agregado');
            Swal.fire({ ...swalConfig, icon: 'success', title: '¡Listo!', text: data.message, timer: 2000, showConfirmButton: false });
        } else {
            Swal.fire({ ...swalConfig, icon: 'error', title: 'Error', text: data.message });
        }
    });
}

// 4. SISTEMA DE CARRITO
let carrito = JSON.parse(localStorage.getItem('carrito')) || [];
actualizarContadorCarrito();

function agregarAlCarrito() {
    const formato = document.querySelector('.formato-card.selected');
    const cantidad = parseInt(document.getElementById('cantidad').value);

    if (!formato) {
        Swal.fire({ ...swalConfig, icon: 'warning', title: 'Formato requerido', text: 'Selecciona un formato' });
        return;
    }

    const item = {
        libroId: window.libroId,
        titulo: window.libroTitulo,
        formato: formato.dataset.formato,
        precio: parseFloat(formato.dataset.precio),
        cantidad: cantidad,
        imagen: window.libroImagen,
        autor: window.libroAutor
    };

    const existingItem = carrito.find(i => i.libroId == item.libroId && i.formato == item.formato);
    if (existingItem) {
        existingItem.cantidad += cantidad;
    } else {
        carrito.push(item);
    }

    localStorage.setItem('carrito', JSON.stringify(carrito));
    actualizarContadorCarrito();
    Swal.fire({ ...swalConfig, icon: 'success', title: '¡Agregado!', text: 'Producto en el carrito', timer: 2000, showConfirmButton: false });
}

function actualizarContadorCarrito() {
    const count = carrito.reduce((total, item) => total + item.cantidad, 0);
    const cartCount = document.getElementById('cart-count');
    if (cartCount) {
        cartCount.textContent = count;
        cartCount.style.display = count > 0 ? 'block' : 'none';
    }
}

function eliminarDelCarrito(index) {
    carrito.splice(index, 1);
    localStorage.setItem('carrito', JSON.stringify(carrito));
    actualizarContadorCarrito();
    mostrarCarrito();
}

function mostrarCarrito() {
    const carritoItems = document.getElementById('carrito-items');
    const carritoTotal = document.getElementById('carrito-total');
    const btnCheckout = document.getElementById('btn-checkout');
    
    if (carrito.length === 0) {
        carritoItems.innerHTML = '<p style="text-align: center; color: #666;">Tu carrito está vacío</p>';
        carritoTotal.textContent = 'Total: $0.00';
        btnCheckout.style.display = 'none';
    } else {
        let html = '';
        let total = 0;
        carrito.forEach((item, index) => {
            const subtotal = item.precio * item.cantidad;
            total += subtotal;
            html += `
                <div style="display: flex; align-items: center; padding: 15px; border: 1px solid #ddd; border-radius: 8px; margin: 10px 0; background: #f9f9f9;">
                    <img src="${item.imagen}" style="width: 50px; height: 70px; object-fit: cover; border-radius: 4px; margin-right: 15px;">
                    <div style="flex: 1;">
                        <h4 style="margin: 0; font-size: 14px;">${item.titulo}</h4>
                        <p style="margin: 0; color: #666; font-size: 12px;">${item.formato} x ${item.cantidad}</p>
                        <p style="margin: 2px 0; font-weight: bold;">$${subtotal.toFixed(2)}</p>
                    </div>
                    <button onclick="eliminarDelCarrito(${index})" style="background: #ff4d4d; color: white; border: none; border-radius: 4px; padding: 5px 10px; cursor: pointer;">
                        <i class="fa fa-trash"></i>
                    </button>
                </div>`;
        });
        carritoItems.innerHTML = html;
        carritoTotal.textContent = `Total: $${total.toFixed(2)}`;
        btnCheckout.style.display = 'block';
    }
    document.getElementById('carritoModal').style.display = 'flex';
}

// 5. FLUJO DE PAGO Y CUPONES
function procederAlPago() {
    const total = carrito.reduce((sum, item) => sum + (item.precio * item.cantidad), 0);
    let resumenHtml = '<div style="text-align: left; padding: 10px; background: #fff; border-radius: 8px;">';
    carrito.forEach(item => {
        resumenHtml += `<p style="margin: 5px 0;">• ${item.titulo} (${item.formato}) x ${item.cantidad} - <b>$${(item.precio * item.cantidad).toFixed(2)}</b></p>`;
    });
    resumenHtml += `<hr><h4 style="margin: 10px 0;">Total a pagar: $${total.toFixed(2)}</h4></div>`;
    
    document.getElementById('pago-resumen').innerHTML = resumenHtml;
    document.getElementById('carritoModal').style.display = 'none';
    document.getElementById('pagoModal').style.display = 'flex';
}

function validarCuponEnPago() {
    const input = document.getElementById('cupon_codigo');
    const codigo = input.value.trim().toUpperCase();
    const mensajeDiv = document.getElementById('cupon-mensaje');
    const btn = document.getElementById('btn-aplicar-cupon');

    if (!codigo) return;
    btn.disabled = true;

    fetch(window.routes.cuponesCanjear, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ codigo: codigo, libro_id: window.libroId })
    })
    .then(response => response.json())
    .then(data => {
        mensajeDiv.style.display = 'block';
        if (data.success) {
            mensajeDiv.innerHTML = `<span style="color: green;">✔ Cupón aplicado: ${data.premio}</span>`;
            Swal.fire({ icon: 'success', title: 'Cupón Válido', text: data.premio, timer: 1500 });
        } else {
            mensajeDiv.innerHTML = `<span style="color: red;">✘ ${data.message}</span>`;
            btn.disabled = false;
        }
    })
    .catch(() => { btn.disabled = false; });
}

let metodoPagoSeleccionado = null;
function seleccionarMetodo(event, metodo) {
    document.querySelectorAll('.metodo-pago-option').forEach(opt => {
        opt.style.borderColor = '#ddd';
        opt.style.background = 'white';
    });
    event.currentTarget.style.borderColor = '#e8820c';
    event.currentTarget.style.background = '#fff9f0';
    metodoPagoSeleccionado = metodo;
    document.getElementById('btn-confirmar').disabled = false;
    renderPaymentDetails(metodo);
}

function renderPaymentDetails(metodo) {
    const container = document.getElementById('paymentDetailsContainer');
    container.style.display = 'block';
    if (metodo === 'tarjeta') {
        container.innerHTML = `
            <input type="text" name="card_name" placeholder="Nombre en tarjeta" class="swal2-input" style="width: 80%; font-size: 14px;">
            <input type="text" name="card_number" placeholder="0000 0000 0000 0000" class="swal2-input" style="width: 80%; font-size: 14px;">`;
    } else if (metodo === 'paypal') {
        container.innerHTML = `<input type="email" name="payment_email" placeholder="Correo PayPal" class="swal2-input" style="width: 80%; font-size: 14px;">`;
    } else {
        container.innerHTML = `<p style="font-size: 13px; color: #666;">Pagarás al recibir tu pedido.</p>`;
    }
}

// 6. PROCESAMIENTO FINAL (FIXED)
function confirmarCompra() {
    if (!metodoPagoSeleccionado) return;

    Swal.fire({
        title: 'Procesando...',
        html: 'Estamos validando tu pedido',
        allowOutsideClick: false,
        didOpen: () => { Swal.showLoading(); }
    });

    fetch('/procesar-compra', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Idempotency-Key': generateIdempotencyKey()
        },
        body: JSON.stringify({
            carrito: carrito,
            metodo_pago: metodoPagoSeleccionado
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            localStorage.removeItem('carrito');
            const totalMostrado = data.total || data.total_final || '---';
            
            Swal.fire({
                icon: 'success',
                title: '¡Pago procesado con éxito!',
                html: `
                    <div style="text-align: center; font-family: sans-serif;">
                        <p>${data.message}</p>
                        <div style="background: #f8f9fa; padding: 15px; border-radius: 8px; border: 1px solid #eee; margin-top: 15px;">
                            <p style="margin:0;"><strong>Total Final: $${totalMostrado}</strong></p>
                        </div>
                    </div>`,
                confirmButtonText: 'Ir a Mis Compras',
                confirmButtonColor: '#e67e22'
            }).then(() => {
          window.location.href = "/dashboard";
            });
        } else {
            Swal.fire('Error', data.message, 'error');
        }
    })
    .catch(() => Swal.fire('Error', 'Hubo un problema de conexión', 'error'));
}

// Funciones de cierre de modal
function cerrarCarritoModal() { document.getElementById('carritoModal').style.display = 'none'; }
function cerrarPagoModal() { document.getElementById('pagoModal').style.display = 'none'; }
function volverAlCarrito() { 
    document.getElementById('pagoModal').style.display = 'none';
    mostrarCarrito();
}