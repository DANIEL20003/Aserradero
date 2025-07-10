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

// Incluir la conexión a la base de datos
require_once './config/Cconexion.php';

// Obtener los pedidos del usuario actual
$id_cliente = $_SESSION['id_usuario'];
$sql = "SELECT p.*, 
               CASE 
                  WHEN p.estado = 'pendiente' THEN 'Pendiente'
                  WHEN p.estado = 'aceptado' THEN 'Aceptado'
                  WHEN p.estado = 'cancelado' THEN 'Cancelado'
                  ELSE p.estado
               END AS estado_formatted,
               CASE 
                  WHEN p.estado = 'pendiente' THEN 'warning' 
                  WHEN p.estado = 'aceptado' THEN 'success'
                  WHEN p.estado = 'cancelado' THEN 'danger'
                  ELSE 'secondary'
               END AS estado_class
        FROM Pedidos p
        WHERE p.id_usuario = ?
        ORDER BY p.creado_en DESC";

$stmt = mysqli_prepare($conexion, $sql);
mysqli_stmt_bind_param($stmt, "i", $id_cliente);
mysqli_stmt_execute($stmt);
$resultado = mysqli_stmt_get_result($stmt);
$pedidos = mysqli_fetch_all($resultado, MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis Pedidos - Aserradero</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .navbar-brand i {
            color: #28a745;
            margin-right: 8px;
        }
        .hero-section {
            background: linear-gradient(135deg, #2d5016 0%, #3d6b1f 100%);
            color: white;
            padding: 40px 0;
            margin-bottom: 30px;
        }
        .order-card {
            border: none;
            border-radius: 12px;
            overflow: hidden;
            margin-bottom: 20px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
            transition: transform 0.3s ease;
        }
        .order-card:hover {
            transform: translateY(-5px);
        }
        .order-card .card-header {
            border-bottom: 0;
            background: #f8f9fa;
            padding: 15px 20px;
        }
        .order-details {
            padding: 15px 0;
            border-bottom: 1px solid #eee;
        }
        .order-details:last-child {
            border-bottom: none;
        }
        .badge-large {
            font-size: 0.9rem;
            padding: 8px 15px;
            border-radius: 30px;
        }
        .empty-state {
            text-align: center;
            padding: 40px 20px;
        }
        .empty-state i {
            font-size: 4rem;
            color: #adb5bd;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold fs-3" href="index.php">
                <i class="fas fa-tree"></i>
                Aserradero
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">Inicio</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="index.php#productos">Productos</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="index.php?opc=mis_pedidos">Mis Pedidos</a>
                    </li>
                </ul>
                
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link position-relative cart-icon" href="#" onclick="toggleCart()">
                            <i class="fas fa-shopping-cart"></i>
                            <span class="cart-badge" id="cartBadge" style="display: none;">0</span>
                        </a>
                    </li>
                    <li class="nav-item d-lg-none">
                        <a class="nav-link" href="./model/M_Logout.php" onclick="return confirm('¿Desea cerrar la sesión?')">
                            <i class="fas fa-sign-out-alt"></i> Cerrar Sesión
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-user"></i>
                            <?php echo htmlspecialchars($_SESSION['nombre']); ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="index.php?opc=mis_pedidos">
                                <i class="fas fa-shopping-bag"></i> Mis Pedidos
                            </a></li>
                            <?php if (isset($_SESSION['esAdmin']) && $_SESSION['esAdmin']): ?>
                                <li><a class="dropdown-item" href="index.php?opc=dashboard">
                                    <i class="fas fa-tachometer-alt"></i> Dashboard
                                </a></li>
                            <?php endif; ?>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="./model/M_Logout.php">
                                <i class="fas fa-sign-out-alt"></i> Cerrar Sesión
                            </a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <h1 class="mb-2"><i class="fas fa-shopping-bag me-2"></i>Mis Pedidos</h1>
            <p class="lead">Consulta el estado de tus pedidos y revisa tu historial de compras</p>
        </div>
    </section>

    <div class="container mb-5">
        <?php if (empty($pedidos)): ?>
            <div class="empty-state">
                <i class="fas fa-shopping-bag"></i>
                <h3>No tienes pedidos todavía</h3>
                <p class="text-muted">Parece que aún no has realizado ningún pedido.</p>
                <a href="index.php#productos" class="btn btn-success mt-3">
                    <i class="fas fa-shopping-cart"></i> Comprar ahora
                </a>
            </div>
        <?php else: ?>
            <?php foreach ($pedidos as $pedido): ?>
                <?php
                    // Obtener los detalles del pedido
                    $id_pedido = $pedido['id_pedido'];
                    $sql_detalles = "SELECT d.*, p.nombre as nombre_producto 
                                     FROM Pedido_detalles d
                                     JOIN Productos p ON d.id_producto = p.id_producto
                                     WHERE d.id_pedido = ?";
                    $stmt_detalles = mysqli_prepare($conexion, $sql_detalles);
                    mysqli_stmt_bind_param($stmt_detalles, "i", $id_pedido);
                    mysqli_stmt_execute($stmt_detalles);
                    $resultado_detalles = mysqli_stmt_get_result($stmt_detalles);
                    $detalles = mysqli_fetch_all($resultado_detalles, MYSQLI_ASSOC);
                ?>

                <div class="card order-card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="mb-0">Pedido #<?php echo str_pad($pedido['id_pedido'], 6, '0', STR_PAD_LEFT); ?></h5>
                            <small class="text-muted">
                                <i class="fas fa-calendar-alt"></i> 
                                <?php echo date('d/m/Y H:i', strtotime($pedido['creado_en'])); ?>
                            </small>
                        </div>
                        <div class="text-end">
                            <span class="badge bg-<?php echo $pedido['estado_class']; ?> badge-large">
                                <?php echo $pedido['estado_formatted']; ?>
                            </span>
                        </div>
                    </div>
                    <div class="card-body">
                        <?php foreach ($detalles as $detalle): ?>
                            <div class="order-details row">
                                <div class="col-lg-2 col-md-3 mb-3 mb-md-0">
                                    <div class="bg-light rounded d-flex align-items-center justify-content-center" 
                                         style="width: 70px; height: 70px;">
                                        <i class="fas fa-box text-muted" style="font-size: 2rem;"></i>
                                    </div>
                                </div>
                                <div class="col-lg-7 col-md-6 mb-3 mb-md-0">
                                    <h6 class="mb-1"><?php echo htmlspecialchars($detalle['nombre_producto']); ?></h6>
                                    <div class="text-muted small">
                                        Cantidad: <?php echo $detalle['cantidad']; ?> × 
                                        $<?php echo number_format($detalle['precio'], 2); ?>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-3 text-end">
                                    <div class="fw-bold">$<?php echo number_format($detalle['subtotal'], 2); ?></div>
                                </div>
                            </div>
                        <?php endforeach; ?>

                        <div class="mt-4">
                            <div class="row">
                                <div class="col-md-6">
                                    <h6>Información de contacto</h6>
                                    <p class="mb-1 small">
                                        <i class="fas fa-user text-primary"></i> 
                                        <?php echo htmlspecialchars($pedido['receptor']); ?>
                                    </p>
                                    <p class="mb-1 small">
                                        <i class="fas fa-envelope text-info"></i> 
                                        <?php echo htmlspecialchars($pedido['correo']); ?>
                                    </p>
                                    <p class="mb-1 small">
                                        <i class="fas fa-phone text-primary"></i> 
                                        <?php echo htmlspecialchars($pedido['telefono']); ?>
                                    </p>
                                    <p class="mb-1 small">
                                        <i class="fas fa-credit-card text-success"></i> 
                                        Método de pago: <?php echo ucfirst(htmlspecialchars($pedido['metodo_pago'])); ?>
                                    </p>
                                </div>
                                <div class="col-md-6 text-md-end">
                                    <div class="fw-bold fs-5">Total: $<?php echo number_format($pedido['total'], 2); ?></div>
                                </div>
                            </div>
                        </div>

                        <?php if ($pedido['estado'] == 'aceptado'): ?>
                        <div class="mt-3 p-3 bg-light rounded">
                            <h6 class="text-success">
                                <i class="fas fa-truck"></i> Información de entrega
                            </h6>
                            <p class="mb-0 small">
                                Fecha estimada de entrega: 
                                <?php 
                                    $fecha_aceptacion = date('Y-m-d', strtotime($pedido['creado_en']));
                                    $fecha_entrega = date('d/m/Y', strtotime($fecha_aceptacion . ' + 3 days'));
                                    echo $fecha_entrega;
                                ?>
                            </p>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <!-- Carrito Modal -->
    <div class="modal fade" id="cartModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-shopping-cart"></i> Mi Carrito
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div id="cartItems">
                        <!-- Items del carrito se cargarán aquí -->
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="w-100">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5>Total: <span id="cartTotal">$0.00</span></h5>
                        </div>
                        <div class="d-grid gap-2">
                            <button type="button" class="btn btn-success btn-lg" onclick="proceedToCheckout()">
                                <i class="fas fa-credit-card"></i> Proceder al Pago
                            </button>
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                Continuar Comprando
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-dark text-white py-4 mt-5">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h5><i class="fas fa-tree"></i> Aserradero</h5>
                    <p>Los mejores productos de madera para tu hogar y negocio.</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <p>&copy; 2025 Aserradero. Todos los derechos reservados.</p>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        let cart = JSON.parse(localStorage.getItem('cart')) || [];

        document.addEventListener('DOMContentLoaded', function() {
            updateCartBadge();
            
            // Initialize Bootstrap components
            // Enable dropdowns
            document.querySelectorAll('.dropdown-toggle').forEach(function(element) {
                new bootstrap.Dropdown(element);
            });
            
            // Add click event to dropdown items to ensure they work
            document.querySelectorAll('.dropdown-item').forEach(function(element) {
                element.addEventListener('click', function(e) {
                    if (this.getAttribute('href') === '#') {
                        e.preventDefault();
                    }
                });
            });
        });

        function updateCartBadge() {
            const badge = document.getElementById('cartBadge');
            const totalItems = cart.reduce((sum, item) => sum + item.quantity, 0);
            
            if (totalItems > 0) {
                badge.style.display = 'flex';
                badge.textContent = totalItems;
            } else {
                badge.style.display = 'none';
            }
        }

        function toggleCart() {
            updateCartDisplay();
            new bootstrap.Modal(document.getElementById('cartModal')).show();
        }

        function updateCartDisplay() {
            const cartItems = document.getElementById('cartItems');
            const cartTotal = document.getElementById('cartTotal');
            
            if (cart.length === 0) {
                cartItems.innerHTML = '<p class="text-center text-muted">Tu carrito está vacío</p>';
                cartTotal.textContent = '$0.00';
                return;
            }

            let total = 0;
            cartItems.innerHTML = cart.map(item => {
                const itemTotal = item.price * item.quantity;
                total += itemTotal;
                
                return `
                    <div class="d-flex align-items-center border-bottom py-3">
                        <img src="${item.image || './public/img/no-image.svg'}" 
                             class="me-3" 
                             width="60" 
                             height="60" 
                             style="object-fit: cover; border-radius: 8px;"
                             onerror="this.src='./public/img/no-image.svg'">
                        <div class="flex-grow-1">
                            <h6 class="mb-1">${item.name}</h6>
                            <div class="d-flex align-items-center gap-2">
                                <button class="btn btn-sm btn-outline-secondary" onclick="updateCartQuantity(${item.id}, -1)">-</button>
                                <span class="mx-2">${item.quantity}</span>
                                <button class="btn btn-sm btn-outline-secondary" onclick="updateCartQuantity(${item.id}, 1)">+</button>
                            </div>
                        </div>
                        <div class="text-end">
                            <div class="fw-bold">$${itemTotal.toFixed(2)}</div>
                            <button class="btn btn-sm btn-outline-danger" onclick="removeFromCart(${item.id})">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                `;
            }).join('');

            cartTotal.textContent = `$${total.toFixed(2)}`;
        }

        function updateCartQuantity(productId, change) {
            const item = cart.find(item => item.id == productId);
            if (!item) return;

            item.quantity += change;
            
            if (item.quantity <= 0) {
                removeFromCart(productId);
                return;
            }

            localStorage.setItem('cart', JSON.stringify(cart));
            updateCartBadge();
            updateCartDisplay();
        }

        function removeFromCart(productId) {
            cart = cart.filter(item => item.id != productId);
            localStorage.setItem('cart', JSON.stringify(cart));
            updateCartBadge();
            updateCartDisplay();
        }

        function proceedToCheckout() {
            if (cart.length === 0) {
                showAlert('Tu carrito está vacío', 'warning');
                return;
            }
            window.location.href = 'index.php?opc=checkout';
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
