
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

        function prestarAtencion() {
            const formato = document.querySelector('.formato-card.selected');
            const cantidad = document.getElementById('cantidad').value;

            if (!formato) {
                alert('Por favor selecciona un formato');
                return;
            }

            // Enviar AJAX para comprar
            fetch('/comprar', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                },
                body: JSON.stringify({
                    libro_id: window.libroId,
                    formato: formato.dataset.formato,
                    cantidad: parseInt(cantidad)
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('✓ ' + data.message);
                    // Actualizar stock en la UI
                    const stockElement = formato.querySelector('.formato-stock');
                    if (stockElement) {
                        const currentStock = parseInt(stockElement.textContent.replace('Stock: ', '').replace('∞', '999'));
                        if (currentStock !== 999) {
                            const newStock = Math.max(0, currentStock - parseInt(cantidad));
                            stockElement.textContent = `Stock: ${newStock}`;
                        }
                    }
                } else {
                    alert('ℹ ' + (data.message || 'Error al procesar la compra'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error al procesar la compra');
            });
        }

        function agregarAlCarrito() {
            const formato = document.querySelector('.formato-card.selected');
            if (!formato) {
                alert('Por favor selecciona un formato');
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
                    alert('✓ ' + data.message);
                    const btn = document.querySelector('.btn-secondary');
                    btn.innerHTML = '<i class="fa fa-heart"></i> En Favoritos';
                    btn.classList.add('favorito-agregado');
                } else {
                    alert('ℹ ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error al agregar a favoritos');
            });
        }