
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

        function seleccionarMetodo(metodo) {
            document.querySelectorAll('.metodo-pago-option').forEach(option => {
                option.style.borderColor = '#ddd';
                option.style.backgroundColor = 'white';
            });
            
            event.currentTarget.style.borderColor = '#007bff';
            event.currentTarget.style.backgroundColor = '#f8f9fa';
            
            metodoPagoSeleccionado = metodo;
            document.getElementById('btn-confirmar').disabled = false;
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

            // Enviar el pedido al servidor
            fetch('/procesar-compra', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                },
                body: JSON.stringify({
                    carrito: carrito,
                    metodo_pago: metodoPagoSeleccionado
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        ...swalConfig,
                        icon: 'success',
                        title: 'Compra exitosa',
                        text: data.message,
                        timer: 3000,
                        showConfirmButton: false
                    }).then(() => {
                        carrito = [];
                        localStorage.setItem('carrito', JSON.stringify(carrito));
                        actualizarContadorCarrito();
                        document.getElementById('pagoModal').style.display = 'none';
                        // Redirigir al dashboard o página de confirmación
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
        }