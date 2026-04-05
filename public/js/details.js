
        // Configuración de SweetAlert con colores de la página
        const swalConfig = {
            confirmButtonColor: '#e8820c', // --amber
            cancelButtonColor: '#0d7a6e',  // --teal
            background: '#fffcf7',         // --paper
            color: '#1a1208',              // --ink
            customClass: {
                popup: 'swal-custom-popup',
                title: 'swal-custom-title',
                confirmButton: 'swal-custom-confirm',
                cancelButton: 'swal-custom-cancel'
            }
        };

        // Función para generar clave de idempotencia
        function generateIdempotencyKey() {
            return 'idemp_' + Date.now() + '_' + Math.random().toString(36).substr(2, 9);
        }

        function mostrarLoginModal() {
            document.getElementById('loginModal').classList.add('show');
        }

        function cerrarLoginModal() {
            document.getElementById('loginModal').classList.remove('show');
        }

    
        window.onclick = function(event) {
            const modal = document.getElementById('loginModal');
            if (event.target === modal) {
                modal.classList.remove('show');
            }
        };

        document.querySelectorAll('.formato-card').forEach(card => {
            card.addEventListener('click', function() {
                document.querySelectorAll('.formato-card').forEach(c => c.classList.remove('selected'));
                this.classList.add('selected');
            });
        });

        document.querySelector('.formato-card')?.classList.add('selected');

        
        if (window.isAuthenticated && window.routes?.favoritosObtener) {
            fetch(window.routes.favoritosObtener)
                .then(response => response.json())
                .then(data => {
                    const libroId = window.libroId;
                    if (data.favoritos && data.favoritos.includes(libroId)) {
                        const btn = document.querySelector('.btn-secondary');
                        btn.innerHTML = '<i class="fa fa-heart"></i> En Favoritos';
                        btn.classList.add('favorito-agregado');
                    }
                })
                .catch(error => console.log('No se pudo verificar favoritos'));
        }

        // Sistema de Carrito
        let carrito = JSON.parse(localStorage.getItem('carrito')) || [];
        actualizarContadorCarrito();

        function agregarAlCarrito() {
            const formato = document.querySelector('.formato-card.selected');
            const cantidad = parseInt(document.getElementById('cantidad').value);

            if (!formato) {
                Swal.fire({
                    ...swalConfig,
                    icon: 'warning',
                    title: 'Formato requerido',
                    text: 'Por favor selecciona un formato antes de agregar al carrito'
                });
                return;
            }

            if (cantidad < 1) {
                Swal.fire({
                    ...swalConfig,
                    icon: 'warning',
                    title: 'Cantidad inválida',
                    text: 'La cantidad debe ser al menos 1'
                });
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

            // Verificar si ya existe en el carrito
            const existingItem = carrito.find(i => i.libroId == item.libroId && i.formato == item.formato);
            if (existingItem) {
                existingItem.cantidad += cantidad;
            } else {
                carrito.push(item);
            }

            localStorage.setItem('carrito', JSON.stringify(carrito));
            actualizarContadorCarrito();
            
            Swal.fire({
                ...swalConfig,
                icon: 'success',
                title: '¡Producto agregado!',
                text: 'El libro se ha agregado correctamente a tu carrito',
                timer: 2000,
                showConfirmButton: false
            });
        }

        function agregarAFavoritos() {
            const formato = document.querySelector('.formato-card.selected');
            if (!formato) {
                Swal.fire({
                    ...swalConfig,
                    icon: 'warning',
                    title: 'Formato requerido',
                    text: 'Por favor selecciona un formato'
                });
                return;
            }
            
            const libroId = window.libroId;
            
            // Enviar AJAX al servidor
            fetch(window.routes.favoritoAgregar, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                },
                body: JSON.stringify({ libro_id: libroId })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const btn = document.querySelector('.btn-secondary');
                    btn.innerHTML = '<i class="fa fa-heart"></i> En Favoritos';
                    btn.classList.add('favorito-agregado');
                    
                    Swal.fire({
                        ...swalConfig,
                        icon: 'success',
                        title: '¡Agregado a favoritos!',
                        text: data.message,
                        timer: 2000,
                        showConfirmButton: false
                    });
                } else {
                    Swal.fire({
                        ...swalConfig,
                        icon: 'error',
                        title: 'Error',
                        text: data.message || 'Error al agregar a favoritos'
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    ...swalConfig,
                    icon: 'error',
                    title: 'Error de conexión',
                    text: 'Error al agregar a favoritos'
                });
            });
        }

        function actualizarContadorCarrito() {
            const count = carrito.reduce((total, item) => total + item.cantidad, 0);
            const cartCount = document.getElementById('cart-count');
            if (count > 0) {
                cartCount.textContent = count;
                cartCount.style.display = 'block';
            } else {
                cartCount.style.display = 'none';
            }
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
                            <img src="${item.imagen}" alt="${item.titulo}" style="width: 60px; height: 80px; object-fit: cover; border-radius: 4px; margin-right: 15px;">
                            <div style="flex: 1;">
                                <h4 style="margin: 0 0 5px 0; font-size: 16px;">${item.titulo}</h4>
                                <p style="margin: 0; color: #666; font-size: 14px;">Autor: ${item.autor}</p>
                                <p style="margin: 0; color: #666; font-size: 14px;">Formato: ${item.formato.charAt(0).toUpperCase() + item.formato.slice(1)}</p>
                                <p style="margin: 0; color: #666; font-size: 14px;">Cantidad: ${item.cantidad}</p>
                                <p style="margin: 5px 0 0 0; font-weight: bold;">$${subtotal.toFixed(2)}</p>
                            </div>
                            <button onclick="eliminarDelCarrito(${index})" style="background: #e74c3c; color: white; border: none; border-radius: 4px; padding: 5px 10px; cursor: pointer;">
                                <i class="fa fa-trash"></i>
                            </button>
                        </div>
                    `;
                });
                
                carritoItems.innerHTML = html;
                carritoTotal.textContent = `Total: $${total.toFixed(2)}`;
                btnCheckout.style.display = 'block';
            }
            
            document.getElementById('carritoModal').style.display = 'flex';
        }

        function cerrarCarritoModal() {
            document.getElementById('carritoModal').style.display = 'none';
        }

        function eliminarDelCarrito(index) {
            carrito.splice(index, 1);
            localStorage.setItem('carrito', JSON.stringify(carrito));
            actualizarContadorCarrito();
            mostrarCarrito();
        }

        function procederAlPago() {
            const total = carrito.reduce((sum, item) => sum + (item.precio * item.cantidad), 0);
            
            let resumenHtml = '<h3>Resumen del Pedido</h3>';
            resumenHtml += '<div style="margin: 15px 0;">';
            
            carrito.forEach(item => {
                const subtotal = item.precio * item.cantidad;
                resumenHtml += `<p>${item.titulo} (${item.formato}) x ${item.cantidad} - $${subtotal.toFixed(2)}</p>`;
            });
            
            resumenHtml += `</div><h4>Total: $${total.toFixed(2)}</h4>`;
            
            document.getElementById('pago-resumen').innerHTML = resumenHtml;
            document.getElementById('carritoModal').style.display = 'none';
            document.getElementById('pagoModal').style.display = 'flex';
        }

        function cerrarPagoModal() {
            document.getElementById('pagoModal').style.display = 'none';
        }

        function volverAlCarrito() {
            document.getElementById('pagoModal').style.display = 'none';
            mostrarCarrito();
        }

        let metodoPagoSeleccionado = null;

        function seleccionarMetodo(event, metodo) {
            document.querySelectorAll('.metodo-pago-option').forEach(option => {
                option.style.borderColor = '#ddd';
                option.style.backgroundColor = 'white';
            });
            
            event.currentTarget.style.borderColor = '#007bff';
            event.currentTarget.style.backgroundColor = '#f8f9fa';
            
            metodoPagoSeleccionado = metodo;
            document.getElementById('btn-confirmar').disabled = false;
            document.getElementById('paymentDetailsContainer').style.display = 'block';
            renderPaymentDetails(metodo);
        }

        function validarPagoSeleccionado() {
            const container = document.getElementById('paymentDetailsContainer');
            const inputs = container.querySelectorAll('input:not([type="button"]):not([type="hidden"])');
            for (let input of inputs) {
                if (input.dataset.optional === 'true' && !input.value.trim()) {
                    continue;
                }

                if (!input.value.trim()) {
                    return {
                        valid: false,
                        message: `Completa el campo ${input.dataset.label || input.name}`
                    };
                }

                const value = input.value.trim();
                if (input.name === 'payment_email' && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value)) {
                    return {
                        valid: false,
                        message: 'Ingresa un correo electrónico válido para PayPal'
                    };
                }

                if (input.name === 'card_number') {
                    const cardNumber = value.replace(/\s+/g, '');
                    if (!/^\d{13,19}$/.test(cardNumber)) {
                        return {
                            valid: false,
                            message: 'Ingresa un número de tarjeta válido'
                        };
                    }
                }

                if (input.name === 'card_expiry' && !/^(0[1-9]|1[0-2])\/(\d{2})$/.test(value)) {
                    return {
                        valid: false,
                        message: 'Ingresa la fecha de expiración en formato MM/AA'
                    };
                }

                if (input.name === 'card_cvv' && !/^\d{3,4}$/.test(value)) {
                    return {
                        valid: false,
                        message: 'Ingresa un CVV válido de 3 o 4 dígitos'
                    };
                }
            }

            const selector = container.querySelector('select[name="cash_method"]');
            if (selector && !selector.value) {
                return {
                    valid: false,
                    message: 'Selecciona cómo quieres pagar en efectivo'
                };
            }

            return { valid: true };
        }

        function renderPaymentDetails(metodo) {
            const container = document.getElementById('paymentDetailsContainer');
            let html = '';

            if (metodo === 'paypal') {
                html = `
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px;">
                        <div class="payment-detail-row">
                            <label style="display: block; margin-bottom: 4px; font-weight: 600; color: #273849; font-size: 12px;">Correo PayPal</label>
                            <input type="email" name="payment_email" data-label="correo PayPal" placeholder="email@ejemplo.com" style="width: 100%; padding: 8px 10px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 13px; color: #0f172a; background: #f8fafc;" />
                        </div>
                        <div class="payment-detail-row">
                            <label style="display: block; margin-bottom: 4px; font-weight: 600; color: #273849; font-size: 12px;">ID Transacción</label>
                            <input type="text" name="payment_transaction" data-label="ID de transacción" placeholder="7ED12345AB6" style="width: 100%; padding: 8px 10px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 13px; color: #0f172a; background: #f8fafc;" />
                        </div>
                    </div>
                    <div style="margin-top: 6px; font-size: 12px; color: #475569;">
                        Completa los datos para continuar.
                    </div>
                `;
            } else if (metodo === 'tarjeta') {
                html = `
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px;">
                        <div class="payment-detail-row" style="grid-column: 1 / -1;">
                            <label style="display: block; margin-bottom: 4px; font-weight: 600; color: #273849; font-size: 12px;">Nombre en tarjeta</label>
                            <input type="text" name="card_name" data-label="nombre en tarjeta" placeholder="Nombre completo" style="width: 100%; padding: 8px 10px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 13px; color: #0f172a; background: #f8fafc;" />
                        </div>
                        <div class="payment-detail-row" style="grid-column: 1 / -1;">
                            <label style="display: block; margin-bottom: 4px; font-weight: 600; color: #273849; font-size: 12px;">Número de tarjeta</label>
                            <input type="text" name="card_number" data-label="número de tarjeta" placeholder="0000 0000 0000 0000" maxlength="19" style="width: 100%; padding: 8px 10px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 13px; color: #0f172a; background: #f8fafc;" />
                        </div>
                        <div>
                            <label style="display: block; margin-bottom: 4px; font-weight: 600; color: #273849; font-size: 12px;">Expiración</label>
                            <input type="text" name="card_expiry" data-label="fecha de expiración" placeholder="MM/AA" maxlength="5" style="width: 100%; padding: 8px 10px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 13px; color: #0f172a; background: #f8fafc;" />
                        </div>
                        <div>
                            <label style="display: block; margin-bottom: 4px; font-weight: 600; color: #273849; font-size: 12px;">CVV</label>
                            <input type="text" name="card_cvv" data-label="CVV" placeholder="123" maxlength="4" style="width: 100%; padding: 8px 10px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 13px; color: #0f172a; background: #f8fafc;" />
                        </div>
                    </div>
                    <div style="margin-top: 6px; font-size: 12px; color: #475569;">
                        Completa los datos para continuar con el pago.
                    </div>
                `;
            } else if (metodo === 'efectivo') {
                html = `
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px;">
                        <div class="payment-detail-row" style="grid-column: 1 / -1;">
                            <p style="margin: 0 0 6px 0; color: #475569; font-size: 12px;">¿Cómo deseas realizar el pago?</p>
                            <select name="cash_method" data-label="método de efectivo" style="width: 100%; padding: 8px 10px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 13px; color: #0f172a; background: #f8fafc;">
                                <option value="">Selecciona</option>
                                <option value="efectivo">Efectivo</option>
                                <option value="transferencia">Transferencia</option>
                            </select>
                        </div>
                        <div class="payment-detail-row" style="grid-column: 1 / -1;">
                            <label style="display: block; margin-bottom: 4px; font-weight: 600; color: #273849; font-size: 12px;">Referencia (opcional)</label>
                            <input type="text" name="payment_info" data-label="referencia" data-optional="true" placeholder="Nota" style="width: 100%; padding: 8px 10px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 13px; color: #0f172a; background: #f8fafc;" />
                        </div>
                    </div>
                `;
            }

            container.innerHTML = html;
        }

        function buildOrderSummary() {
            let summaryHtml = '<div style="text-align: left; margin-top: 10px;">';
            carrito.forEach(item => {
                const subtotal = item.precio * item.cantidad;
                summaryHtml += `<p style="margin: 5px 0;"><strong>${item.titulo}</strong> (${item.formato}) x ${item.cantidad} - $${subtotal.toFixed(2)}</p>`;
            });
            const total = carrito.reduce((sum, item) => sum + item.precio * item.cantidad, 0);
            summaryHtml += `<p style="margin: 12px 0 0 0; font-weight: 700;">Total: $${total.toFixed(2)}</p>`;
            summaryHtml += '</div>';
            return summaryHtml;
        }

        function obtenerDatosPago() {
            const container = document.getElementById('paymentDetailsContainer');
            const inputs = container.querySelectorAll('input, select');
            const paymentData = {};

            inputs.forEach(input => {
                if (!input.name) return;
                paymentData[input.name] = input.value.trim();
            });

            return paymentData;
        }

        function confirmarCompra() {
            if (!metodoPagoSeleccionado) {
                Swal.fire({
                    ...swalConfig,
                    icon: 'warning',
                    title: 'Método de pago requerido',
                    text: 'Por favor selecciona un método de pago'
                });
                return;
            }

            const validation = validarPagoSeleccionado();
            if (!validation.valid) {
                Swal.fire({
                    ...swalConfig,
                    icon: 'warning',
                    title: 'Datos incompletos',
                    html: `${validation.message}. Puedes continuar de todos modos para procesar el pago.`,
                    showCancelButton: true,
                    confirmButtonText: 'Continuar de todos modos',
                    cancelButtonText: 'Corregir datos'
                }).then(result => {
                    if (result.isConfirmed) {
                        procesarCompra();
                    }
                });
                return;
            }

            procesarCompra();
        }

        function procesarCompra() {
            Swal.fire({
                ...swalConfig,
                title: 'Procesando pago',
                html: 'Estamos confirmando los datos de pago. Esto puede tardar unos segundos.',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                },
                timer: 1500
            }).then(() => {
                fetch('/procesar-compra', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
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
                        const summaryHtml = buildOrderSummary();
                        Swal.fire({
                            ...swalConfig,
                            icon: 'success',
                            title: 'Pago procesado con éxito',
                            html: `${data.message || 'Tu pedido se ha enviado correctamente.'}${summaryHtml}`,
                            confirmButtonText: 'Ir al Dashboard',
                            showCloseButton: true
                        }).then(() => {
                            carrito = [];
                            localStorage.setItem('carrito', JSON.stringify(carrito));
                            actualizarContadorCarrito();
                            document.getElementById('pagoModal').style.display = 'none';
                            window.location.href = '/cliente/dashboard';
                        });
                    } else {
                        Swal.fire({
                            ...swalConfig,
                            icon: 'error',
                            title: 'Error en la compra',
                            text: data.message || 'Error al procesar la compra'
                        });
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire({
                        ...swalConfig,
                        icon: 'error',
                        title: 'Error de conexión',
                        text: 'Error al procesar la compra'
                    });
                });
            });
        }