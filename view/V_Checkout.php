<?php
// Iniciar sesión si no está iniciada
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Verificar si hay sesión activa
if (!isset($_SESSION['sesion_iniciada']) || $_SESSION['sesion_iniciada'] !== "iniciado") {
    header("Location: index.php?opc=login");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - Aserradero</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .checkout-container {
            background: #f8f9fa;
            min-height: 100vh;
            padding: 30px 0;
        }
        .checkout-card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        .step-indicator {
            background: white;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 30px;
        }
        .step {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
        }
        .step-number {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background: #28a745;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 10px;
            font-weight: bold;
        }
        .order-summary {
            background: #fff;
            border-radius: 10px;
            padding: 20px;
            border: 1px solid #dee2e6;
        }
        .product-item {
            border-bottom: 1px solid #eee;
            padding: 15px 0;
        }
        .product-item:last-child {
            border-bottom: none;
        }
        .btn-complete {
            background: linear-gradient(135deg, #28a745, #20c997);
            border: none;
            padding: 15px 30px;
            font-size: 1.1rem;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="checkout-container">
        <div class="container">
            <!-- Header -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h2><i class="fas fa-shopping-cart text-success"></i> Finalizar Compra</h2>
                            <p class="text-muted">Complete su pedido de forma segura</p>
                        </div>
                        <a href="index.php" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left"></i> Volver al inicio
                        </a>
                    </div>
                </div>
            </div>

            <!-- Step Indicator -->
            <div class="step-indicator">
                <div class="step">
                    <div class="step-number">1</div>
                    <span>Revisión del pedido</span>
                </div>
            </div>

            <div class="row">
                <!-- Formulario de datos -->
                <div class="col-lg-7">
                    <div class="checkout-card card">
                        <div class="card-header bg-white">
                            <h5 class="mb-0"><i class="fas fa-user"></i> Información del cliente</h5>
                        </div>
                        <div class="card-body">
                            <form id="checkoutForm">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Nombre completo *</label>
                                        <input type="text" class="form-control" id="nombre" 
                                               value="<?php echo htmlspecialchars($_SESSION['nombre']); ?>" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Correo electrónico *</label>
                                        <input type="email" class="form-control" id="email" 
                                               value="<?php echo htmlspecialchars($_SESSION['correo']); ?>" required>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Teléfono *</label>
                                        <input type="tel" class="form-control" id="telefono" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Documento de identidad</label>
                                        <input type="text" class="form-control" id="documento">
                                    </div>
                                </div>

                                <h6 class="mt-4 mb-3"><i class="fas fa-map-marker-alt"></i> Dirección de entrega</h6>
                                
                                <div class="mb-3">
                                    <label class="form-label">Dirección completa *</label>
                                    <textarea class="form-control" id="direccion" rows="3" 
                                              placeholder="Ingrese la dirección completa de entrega" required></textarea>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Ciudad *</label>
                                        <input type="text" class="form-control" id="ciudad" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Código postal</label>
                                        <input type="text" class="form-control" id="codigo_postal">
                                    </div>
                                </div>

                                <h6 class="mt-4 mb-3"><i class="fas fa-credit-card"></i> Método de pago</h6>
                                
                                <div class="mb-3">
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="radio" name="metodo_pago" id="efectivo" value="efectivo" checked>
                                        <label class="form-check-label" for="efectivo">
                                            <i class="fas fa-money-bill text-success"></i> Pago en efectivo (contra entrega)
                                        </label>
                                    </div>
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="radio" name="metodo_pago" id="transferencia" value="transferencia">
                                        <label class="form-check-label" for="transferencia">
                                            <i class="fas fa-university text-primary"></i> Transferencia bancaria
                                        </label>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Comentarios adicionales</label>
                                    <textarea class="form-control" id="comentarios" rows="3" 
                                              placeholder="Instrucciones especiales, horarios de entrega, etc."></textarea>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Resumen del pedido -->
                <div class="col-lg-5">
                    <div class="order-summary">
                        <h5 class="mb-3"><i class="fas fa-list"></i> Resumen del pedido</h5>
                        
                        <div id="orderItems">
                            <!-- Los items se cargarán aquí dinámicamente -->
                        </div>
                        
                        <hr>
                        
                        <div class="d-flex justify-content-between mb-2">
                            <span>Subtotal:</span>
                            <span id="subtotal">$0.00</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Envío:</span>
                            <span id="envio">$10.00</span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between mb-3">
                            <strong>Total:</strong>
                            <strong id="total" class="text-success">$0.00</strong>
                        </div>
                        
                        <button type="button" class="btn btn-complete btn-success w-100" onclick="completePurchase()">
                            <i class="fas fa-check-circle"></i> Confirmar Pedido
                        </button>
                        
                        <div class="text-center mt-3">
                            <small class="text-muted">
                                <i class="fas fa-shield-alt"></i> 
                                Sus datos están protegidos y seguros
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de confirmación -->
    <div class="modal fade" id="confirmModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title">
                        <i class="fas fa-check-circle"></i> ¡Pedido Confirmado!
                    </h5>
                </div>
                <div class="modal-body text-center">
                    <div class="mb-3">
                        <i class="fas fa-check-circle text-success" style="font-size: 4rem;"></i>
                    </div>
                    <h4>¡Gracias por su compra!</h4>
                    <p>Su pedido ha sido procesado exitosamente.</p>
                    <p><strong>Número de pedido: <span id="orderNumber"></span></strong></p>
                    <p class="text-muted">Recibirá un correo de confirmación con los detalles de su pedido.</p>
                </div>
                <div class="modal-footer">
                    <a href="index.php" class="btn btn-success">Volver al inicio</a>
                    <a href="index.php?opc=dashboard" class="btn btn-outline-success">Ver mis pedidos</a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        let cart = JSON.parse(localStorage.getItem('cart')) || [];
        const costoEnvio = 10.00;

        document.addEventListener('DOMContentLoaded', function() {
            if (cart.length === 0) {
                window.location.href = 'index.php';
                return;
            }
            
            displayOrderSummary();
        });

        function displayOrderSummary() {
            const orderItems = document.getElementById('orderItems');
            const subtotalElement = document.getElementById('subtotal');
            const totalElement = document.getElementById('total');
            
            let subtotal = 0;
            
            orderItems.innerHTML = cart.map(item => {
                const itemTotal = item.price * item.quantity;
                subtotal += itemTotal;
                
                return `
                    <div class="product-item">
                        <div class="d-flex align-items-center">
                            <img src="${item.image || './public/img/no-image.svg'}" 
                                 width="50" 
                                 height="50" 
                                 class="me-3 rounded"
                                 style="object-fit: cover;"
                                 onerror="this.src='./public/img/no-image.svg'">
                            <div class="flex-grow-1">
                                <h6 class="mb-1">${item.name}</h6>
                                <small class="text-muted">Cantidad: ${item.quantity}</small>
                            </div>
                            <div class="text-end">
                                <div>$${itemTotal.toFixed(2)}</div>
                            </div>
                        </div>
                    </div>
                `;
            }).join('');
            
            const total = subtotal + costoEnvio;
            
            subtotalElement.textContent = `$${subtotal.toFixed(2)}`;
            totalElement.textContent = `$${total.toFixed(2)}`;
        }

        async function completePurchase() {
            // Validar formulario
            const form = document.getElementById('checkoutForm');
            if (!form.checkValidity()) {
                form.reportValidity();
                return;
            }

            // Obtener datos del formulario
            const formData = {
                nombre: document.getElementById('nombre').value,
                email: document.getElementById('email').value,
                telefono: document.getElementById('telefono').value,
                documento: document.getElementById('documento').value,
                direccion: document.getElementById('direccion').value,
                ciudad: document.getElementById('ciudad').value,
                codigo_postal: document.getElementById('codigo_postal').value,
                metodo_pago: document.querySelector('input[name="metodo_pago"]:checked').value,
                comentarios: document.getElementById('comentarios').value,
                items: cart,
                subtotal: cart.reduce((sum, item) => sum + (item.price * item.quantity), 0),
                costo_envio: costoEnvio,
                total: cart.reduce((sum, item) => sum + (item.price * item.quantity), 0) + costoEnvio
            };

            try {
                // Mostrar loading
                const btn = document.querySelector('.btn-complete');
                const originalText = btn.innerHTML;
                btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Procesando...';
                btn.disabled = true;

                const response = await fetch('./model/M_ProcesarPedido.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(formData)
                });

                const result = await response.json();

                if (result.success) {
                    // Limpiar carrito
                    localStorage.removeItem('cart');
                    cart = [];

                    // Mostrar modal de confirmación
                    document.getElementById('orderNumber').textContent = result.order_id;
                    new bootstrap.Modal(document.getElementById('confirmModal')).show();
                } else {
                    throw new Error(result.error || 'Error al procesar el pedido');
                }

            } catch (error) {
                console.error('Error:', error);
                showAlert('Error al procesar el pedido: ' + error.message, 'danger');
                
                // Restaurar botón
                btn.innerHTML = originalText;
                btn.disabled = false;
            }
        }

        function showAlert(message, type = 'info') {
            const alertDiv = document.createElement('div');
            alertDiv.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
            alertDiv.style.cssText = 'top: 20px; right: 20px; z-index: 9999; max-width: 300px;';
            alertDiv.innerHTML = `
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;
            
            document.body.appendChild(alertDiv);
            
            setTimeout(() => {
                alertDiv.remove();
            }, 5000);
        }
    </script>
</body>
</html>
