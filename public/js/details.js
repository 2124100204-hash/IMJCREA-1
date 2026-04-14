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

function generateIdempotencyKey() {
    return 'idemp_' + Date.now() + '_' + Math.random().toString(36).substr(2, 9);
}

function mostrarLoginModal() { document.getElementById('loginModal').classList.add('show'); }
function cerrarLoginModal() { document.getElementById('loginModal').classList.remove('show'); }

window.onclick = function(event) {
    const modal = document.getElementById('loginModal');
    if (event.target === modal) modal.classList.remove('show');
};

document.querySelectorAll('.formato-card').forEach(card => {
    card.addEventListener('click', function() {
        document.querySelectorAll('.formato-card').forEach(c => c.classList.remove('selected'));
        this.classList.add('selected');
    });
});
document.querySelector('.formato-card')?.classList.add('selected');

// 2. FAVORITOS
if (window.isAuthenticated && window.routes?.favoritosObtener) {
    fetch(window.routes.favoritosObtener)
        .then(r => r.json())
        .then(data => {
            if (data.favoritos && data.favoritos.includes(window.libroId)) {
                const btn = document.querySelector('.btn-secondary');
                if (btn) {
                    btn.innerHTML = '<i class="fa fa-heart"></i> En Favoritos';
                    btn.classList.add('favorito-agregado');
                }
            }
        })
        .catch(() => {});
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
    .then(r => r.json())
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

// 3. CARRITO
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
    const existing = carrito.find(i => i.libroId == item.libroId && i.formato == item.formato);
    if (existing) {
        existing.cantidad += cantidad;
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
        carritoItems.innerHTML = '<p style="text-align:center;color:#666;">Tu carrito está vacío</p>';
        carritoTotal.textContent = 'Total: $0.00';
        btnCheckout.style.display = 'none';
    } else {
        let html = '';
        let total = 0;
        carrito.forEach((item, index) => {
            const subtotal = item.precio * item.cantidad;
            total += subtotal;
            html += `
                <div style="display:flex;align-items:center;padding:15px;border:1px solid #ddd;border-radius:8px;margin:10px 0;background:#f9f9f9;">
                    <img src="${item.imagen}" style="width:50px;height:70px;object-fit:cover;border-radius:4px;margin-right:15px;">
                    <div style="flex:1;">
                        <h4 style="margin:0;font-size:14px;">${item.titulo}</h4>
                        <p style="margin:0;color:#666;font-size:12px;">${item.formato} x ${item.cantidad}</p>
                        <p style="margin:2px 0;font-weight:bold;">$${subtotal.toFixed(2)}</p>
                    </div>
                    <button onclick="eliminarDelCarrito(${index})" style="background:#ff4d4d;color:white;border:none;border-radius:4px;padding:5px 10px;cursor:pointer;">
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

// 4. CUPÓN (estado global)
let cuponAplicado = null; // { codigo, premio }

function procederAlPago() {
    // Resetear cupón al abrir pago
    cuponAplicado = null;
    document.getElementById('cupon_codigo').value = '';
    document.getElementById('cupon-mensaje').style.display = 'none';
    document.getElementById('btn-aplicar-cupon').disabled = false;

    actualizarResumenPago();

    document.getElementById('carritoModal').style.display = 'none';
    document.getElementById('pagoModal').style.display = 'flex';
}

function actualizarResumenPago() {
    const total = carrito.reduce((sum, item) => sum + (item.precio * item.cantidad), 0);
    let html = '<div style="text-align:left;padding:10px;background:#fff;border-radius:8px;">';
    carrito.forEach(item => {
        html += `<p style="margin:5px 0;">• ${item.titulo} (${item.formato}) x ${item.cantidad} — <b>$${(item.precio * item.cantidad).toFixed(2)}</b></p>`;
    });
    html += `<hr style="margin:10px 0;">`;

    if (cuponAplicado) {
        html += `
            <div style="background:#fff8e1;border:1px solid #f9a825;border-radius:6px;padding:10px;margin:8px 0;display:flex;align-items:center;gap:10px;">
                <i class="fa fa-gift" style="color:#e67e22;font-size:18px;"></i>
                <div>
                    <p style="margin:0;font-size:13px;font-weight:bold;color:#e67e22;">🎁 Premio canjeado</p>
                    <p style="margin:0;font-size:13px;color:#555;">Código <b>${cuponAplicado.codigo}</b>: ${cuponAplicado.premio}</p>
                </div>
            </div>`;
    }

    html += `<h4 style="margin:10px 0;">Total a pagar: $${total.toFixed(2)}</h4></div>`;
    document.getElementById('pago-resumen').innerHTML = html;
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
    .then(r => r.json())
    .then(data => {
        mensajeDiv.style.display = 'block';
        if (data.success) {
            // Guardar cupón aplicado
            cuponAplicado = { codigo: codigo, premio: data.premio };

            mensajeDiv.style.background = '#e8f5e9';
            mensajeDiv.style.color = '#2e7d32';
            mensajeDiv.innerHTML = `<i class="fa fa-check-circle"></i> Cupón aplicado: <b>${data.premio}</b>`;

            // Actualizar resumen con el cupón
            actualizarResumenPago();

            Swal.fire({
                ...swalConfig,
                icon: 'success',
                title: '¡Cupón válido!',
                html: `<p>Tu premio: <b>${data.premio}</b></p><p style="font-size:13px;color:#666;">Se ha registrado en tu pedido.</p>`,
                timer: 2500,
                showConfirmButton: false
            });
        } else {
            cuponAplicado = null;
            mensajeDiv.style.background = '#ffebee';
            mensajeDiv.style.color = '#c62828';
            mensajeDiv.innerHTML = `<i class="fa fa-times-circle"></i> ${data.message}`;
            btn.disabled = false;
        }
    })
    .catch(() => { btn.disabled = false; });
}

// 5. MÉTODOS DE PAGO
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
            <div style="padding:16px;background:#f8f9fa;border-radius:10px;margin-top:10px;">
                <p style="font-size:12px;font-weight:bold;color:#555;margin-bottom:12px;letter-spacing:1px;">DATOS DE TARJETA</p>
                <input type="text" id="card_name" placeholder="Nombre en la tarjeta"
                    style="width:100%;box-sizing:border-box;padding:10px 12px;border:1px solid #ddd;border-radius:6px;font-size:13px;margin-bottom:10px;">
                <div style="position:relative;margin-bottom:10px;">
                    <input type="text" id="card_number" placeholder="0000 0000 0000 0000" maxlength="19"
                        style="width:100%;box-sizing:border-box;padding:10px 40px 10px 12px;border:1px solid #ddd;border-radius:6px;font-size:13px;">
                    <i class="fa fa-credit-card" style="position:absolute;right:12px;top:50%;transform:translateY(-50%);color:#aaa;"></i>
                </div>
                <div style="display:flex;gap:10px;">
                    <input type="text" id="card_expiry" placeholder="MM/AA" maxlength="5"
                        style="flex:1;padding:10px 12px;border:1px solid #ddd;border-radius:6px;font-size:13px;">
                    <input type="text" id="card_cvv" placeholder="CVV" maxlength="3"
                        style="flex:1;padding:10px 12px;border:1px solid #ddd;border-radius:6px;font-size:13px;">
                </div>
                <p style="font-size:11px;color:#aaa;margin-top:8px;"><i class="fa fa-lock"></i> Pago seguro con encriptación SSL</p>
            </div>`;

        // Formatear número de tarjeta automáticamente
        document.getElementById('card_number').addEventListener('input', function () {
            let val = this.value.replace(/\D/g, '').substring(0, 16);
            this.value = val.replace(/(.{4})/g, '$1 ').trim();
        });
        document.getElementById('card_expiry').addEventListener('input', function () {
            let val = this.value.replace(/\D/g, '').substring(0, 4);
            if (val.length >= 3) val = val.substring(0, 2) + '/' + val.substring(2);
            this.value = val;
        });

    } else if (metodo === 'paypal') {
        container.innerHTML = `
            <div style="padding:16px;background:#f0f7ff;border:1px solid #b3d4f5;border-radius:10px;margin-top:10px;">
                <!-- Logo PayPal simulado -->
                <div style="text-align:center;margin-bottom:16px;">
                    <div style="display:inline-block;background:#003087;color:white;font-size:20px;font-weight:900;padding:8px 20px;border-radius:6px;letter-spacing:-1px;">
                        Pay<span style="color:#009cde;">Pal</span>
                    </div>
                </div>
                <p style="font-size:12px;color:#555;text-align:center;margin-bottom:12px;">Ingresa tu cuenta PayPal para continuar</p>
                <input type="email" id="paypal_email" placeholder="tu@correo.com"
                    style="width:100%;box-sizing:border-box;padding:10px 12px;border:1px solid #b3d4f5;border-radius:6px;font-size:13px;margin-bottom:10px;">
                <input type="password" id="paypal_password" placeholder="Contraseña PayPal"
                    style="width:100%;box-sizing:border-box;padding:10px 12px;border:1px solid #b3d4f5;border-radius:6px;font-size:13px;margin-bottom:10px;">
            </div>`;
    }
}

// 6. CONFIRMAR COMPRA
function confirmarCompra() {
    if (!metodoPagoSeleccionado) return;

    Swal.fire({
        title: 'Procesando pago...',
        html: metodoPagoSeleccionado === 'paypal'
            ? '<p style="font-size:13px;">Conectando con PayPal...</p>'
            : '<p style="font-size:13px;">Validando datos de tarjeta...</p>',
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
            metodo_pago: metodoPagoSeleccionado,
            cupon: cuponAplicado  // Enviar cupón al backend
        })
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            localStorage.removeItem('carrito');
            const totalMostrado = data.total || '---';
            const cuponHtml = cuponAplicado
                ? `<div style="background:#fff8e1;border:1px solid #f9a825;border-radius:6px;padding:8px 12px;margin-top:10px;font-size:13px;">
                    🎁 Premio incluido: <b>${cuponAplicado.premio}</b>
                   </div>`
                : '';

            Swal.fire({
                icon: 'success',
                title: '¡Pago realizado con éxito!',
                html: `
                    <div style="text-align:center;font-family:sans-serif;">
                        <p>${data.message}</p>
                        <div style="background:#f8f9fa;padding:15px;border-radius:8px;border:1px solid #eee;margin-top:15px;">
                            <p style="margin:0;"><strong>Total pagado: $${totalMostrado}</strong></p>
                            ${cuponHtml}
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

// 7. CIERRE DE MODALES
function cerrarCarritoModal() { document.getElementById('carritoModal').style.display = 'none'; }
function cerrarPagoModal() { document.getElementById('pagoModal').style.display = 'none'; }
function volverAlCarrito() {
    document.getElementById('pagoModal').style.display = 'none';
    mostrarCarrito();
}