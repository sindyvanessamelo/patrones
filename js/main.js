document.addEventListener('DOMContentLoaded', () => {
    console.log('JavaScript cargado correctamente');
    setTimeout(mostrarVentanaEmergente, 3000);  // Mostrar ventana emergente después de 3 segundos
    iniciarCarrusel();  // Iniciar el carrusel

    // Manejar el formulario de checkout
    const checkoutForm = document.getElementById('checkout-form');
    if (checkoutForm) {
        checkoutForm.addEventListener('submit', (event) => {
            event.preventDefault();  // Prevenir la acción por defecto del formulario
            procesarPago();
        });
    }

    // Cargar carrito de localStorage
    cargarCarrito();

    // Mostrar resumen de pago en checkout
    if (window.location.pathname.includes('checkout.html')) {
        mostrarResumenPago();
    }
});

function validarFormulario() {
    let usuario = document.getElementById('usuario').value;
    let password = document.getElementById('password').value;

    if (usuario === '' || password === '') {
        alert('Todos los campos son obligatorios');
        return false;
    }
    return true;
}

function mostrarVentanaEmergente() {
    const ventana = document.getElementById('ventana-emergente');
    if (ventana) {
        ventana.style.display = 'block';
    }
}

function cerrarVentana() {
    const ventana = document.getElementById('ventana-emergente');
    if (ventana) {
        ventana.style.display = 'none';
    }
}

function iniciarCarrusel() {
    let index = 0;
    const items = document.querySelectorAll('.carousel-item');
    const totalItems = items.length;

    if (totalItems > 0) {
        items[index].classList.add('active');
        setInterval(() => {
            items[index].classList.remove('active');
            index = (index + 1) % totalItems;
            items[index].classList.add('active');
        }, 3000);
    }
}

// Funcionalidad del carrito de compras
let carrito = JSON.parse(localStorage.getItem('carrito')) || [];

function cargarCarrito() {
    actualizarCarrito();
}

function agregarAlCarrito(idProducto) {
    fetch(`obtener_productos.php?id=${idProducto}`)
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(producto => {
            const nombreProducto = producto.nombre.split(' ')[0]; // Tomar solo la primera palabra
            const precioProducto = producto.precio;
            const cantidadStock = producto.cantidad_stock;

            if (cantidadStock === 0) {
                alert('Producto sin stock');
                return;
            }

            const productoExistente = carrito.find(producto => producto.id === idProducto);

            if (productoExistente) {
                productoExistente.cantidad += 1;
            } else {
                carrito.push({ id: idProducto, nombre: nombreProducto, precio: precioProducto, cantidad: 1, stock: cantidadStock });
            }

            localStorage.setItem('carrito', JSON.stringify(carrito));
            actualizarCarrito();
        })
        .catch(error => console.error('Error:', error));
}

function eliminarDelCarrito(index) {
    carrito.splice(index, 1);
    localStorage.setItem('carrito', JSON.stringify(carrito));
    actualizarCarrito();
}

function actualizarCantidad(index, nuevaCantidad) {
    carrito[index].cantidad = nuevaCantidad;
    localStorage.setItem('carrito', JSON.stringify(carrito));
    actualizarCarrito();
}

function actualizarCarrito() {
    const carritoElement = document.getElementById('productos-carrito');
    if (!carritoElement) {
        return; // Salir si no existe el elemento
    }
    const cantidadCarrito = document.getElementById('cantidad-carrito');
    carritoElement.innerHTML = '';
    carrito.forEach((producto, index) => {
        const advertenciaStock = producto.stock <= 5 ? '<span class="advertencia-stock">Stock bajo</span>' : '';
        carritoElement.innerHTML += `
            <div>
                ${producto.nombre} - Cantidad: 
                <input type="number" value="${producto.cantidad}" min="1" onchange="actualizarCantidad(${index}, this.value)">
                ${advertenciaStock}
                <button onclick="eliminarDelCarrito(${index})">Eliminar</button>
            </div>`;
    });
    cantidadCarrito.textContent = carrito.length;
    document.getElementById('carrito').style.display = carrito.length > 0 ? 'block' : 'none';
}

function mostrarOcultarCarrito() {
    const carrito = document.getElementById('carrito');
    if (carrito.style.display === 'none' || carrito.style.display === '') {
        carrito.style.display = 'block';
    } else {
        carrito.style.display = 'none';
    }
}

function irACheckout() {
    // Verificar si el usuario está autenticado
    fetch('verificar_sesion.php')
        .then(response => response.json())
        .then(data => {
            if (data.autenticado) {
                window.location.href = 'checkout.html';
            } else {
                window.location.href = 'login.html';
            }
        })
        .catch(error => console.error('Error:', error));
}

function actualizarStock(idProducto, accion) {
    // Realizar una solicitud AJAX para actualizar el stock en la base de datos
    fetch(`actualizar_stock.php?id=${idProducto}&accion=${accion}`)
        .then(response => response.text())
        .then(data => console.log(data))
        .catch(error => console.error('Error:', error));
}

function procesarPago() {
    const loader = document.getElementById('loader');
    if (loader) {
        loader.style.display = 'flex';
    }

    const checkoutForm = document.getElementById('checkout-form');
    const formData = new FormData(checkoutForm);
    formData.append('carrito', JSON.stringify(carrito));

    console.log("Carrito enviado:", JSON.stringify(carrito));  // Añade esto para verificar el carrito

    // Simular un retraso de 3 segundos
    setTimeout(() => {
        fetch('procesar_pago.php', {
            method: 'POST',
            body: formData
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.text();
        })
        .then(data => {
            console.log("Respuesta del servidor:", data);  // Añade esto para verificar la respuesta
            mostrarConfirmacionCompra();
            vaciarCarrito();
        })
        .catch(error => {
            console.error('Error:', error);  // Añade esto para verificar errores
        })
        .finally(() => {
            if (loader) {
                loader.style.display = 'none';
            }
        });
    }, 3000); // 3 segundos de retraso
}

function mostrarConfirmacionCompra() {
    const mainElement = document.querySelector('main');
    mainElement.innerHTML = `
        <h2>Compra Confirmada</h2>
        <p>Gracias por su compra. Aquí están los detalles de su pedido:</p>
        <div class="resumen-pago">
            <table>
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th>Cantidad</th>
                        <th>Precio</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    ${carrito.map(producto => `
                        <tr>
                            <td>${producto.nombre || 'Producto desconocido'}</td>
                            <td>${producto.cantidad}</td>
                            <td>$${Math.floor(producto.precio)}</td>
                            <td>$${Math.floor(producto.precio * producto.cantidad)}</td>
                        </tr>`).join('')}
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3" class="total">Total a Pagar</td>
                        <td class="total">$${carrito.reduce((total, producto) => total + Math.floor(producto.precio * producto.cantidad), 0)}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
        <button onclick="volverAlInicio()">Volver al Inicio</button>
    `;
}

function volverAlInicio() {
    window.location.href = 'index.html';
}

function vaciarCarrito() {
    carrito = [];
    localStorage.removeItem('carrito'); // Limpiar el almacenamiento local
    actualizarCarrito();
    const carritoElement = document.getElementById('carrito');
    if (carritoElement) {
        carritoElement.style.display = 'none'; // Cerrar el carrito
    }
}

function mostrarResumenPago() {
    const resumenPago = document.getElementById('resumen-pago');
    if (!resumenPago) {
        return; // Salir si no existe el elemento
    }

    resumenPago.innerHTML = `
        <h2>Resumen del Pago</h2>
        <table>
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>Cantidad</th>
                    <th>Precio</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                ${carrito.map(producto => `
                    <tr>
                        <td>${producto.nombre || 'Producto desconocido'}</td>
                        <td>${producto.cantidad}</td>
                        <td>$${Math.floor(producto.precio)}</td>
                        <td>$${Math.floor(producto.precio * producto.cantidad)}</td>
                    </tr>`).join('')}
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3" class="total">Total a Pagar</td>
                    <td class="total">$${carrito.reduce((total, producto) => total + Math.floor(producto.precio * producto.cantidad), 0)}</td>
                </tr>
            </tfoot>
        </table>
    `;

    resumenPago.style.display = 'block';
}
