<?php
// Iniciar sesión si no está iniciada
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Verificar si hay sesión activa y es admin
if (!isset($_SESSION['sesion_iniciada']) || $_SESSION['sesion_iniciada'] !== "iniciado" || !$_SESSION['esAdmin']) {
    header("Location: index.php?opc=login");
    exit;
}

// Incluir la conexión a la base de datos
require_once './config/Cconexion.php';

// Obtener todos los pedidos
$sql = "SELECT p.*, u.nombre as nombre_cliente,
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
        LEFT JOIN Usuarios u ON p.id_usuario = u.id_usuario
        WHERE p.activo = 1
        ORDER BY p.creado_en DESC";

$resultado = mysqli_query($conexion, $sql);
$pedidos = mysqli_fetch_all($resultado, MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Pedidos - Admin</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .admin-header {
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
        .status-badge {
            font-size: 0.9rem;
            padding: 8px 15px;
            border-radius: 30px;
        }
        .action-buttons .btn {
            margin: 2px;
        }
        .order-details {
            padding: 10px 0;
            border-bottom: 1px solid #eee;
        }
        .order-details:last-child {
            border-bottom: none;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <section class="admin-header">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="mb-2"><i class="fas fa-clipboard-list me-2"></i>Gestión de Pedidos</h1>
                    <p class="lead">Administra los pedidos de clientes</p>
                </div>
                <div>
                    <a href="index.php?opc=dashboard" class="btn btn-light me-2">
                        <i class="fas fa-arrow-left"></i> Dashboard
                    </a>
                    <a href="index.php?opc=checkout_admin" class="btn btn-success">
                        <i class="fas fa-plus"></i> Nuevo Pedido
                    </a>
                </div>
            </div>
        </div>
    </section>

    <div class="container mb-5">
        <!-- Filtros -->
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="btn-group" role="group">
                    <button type="button" class="btn btn-outline-primary active" onclick="filterOrders('all')">
                        Todos (<?php echo count($pedidos); ?>)
                    </button>
                    <button type="button" class="btn btn-outline-warning" onclick="filterOrders('pendiente')">
                        Pendientes (<?php echo count(array_filter($pedidos, fn($p) => $p['estado'] == 'pendiente')); ?>)
                    </button>
                    <button type="button" class="btn btn-outline-success" onclick="filterOrders('aceptado')">
                        Aceptados (<?php echo count(array_filter($pedidos, fn($p) => $p['estado'] == 'aceptado')); ?>)
                    </button>
                    <button type="button" class="btn btn-outline-danger" onclick="filterOrders('cancelado')">
                        Cancelados (<?php echo count(array_filter($pedidos, fn($p) => $p['estado'] == 'cancelado')); ?>)
                    </button>
                </div>
            </div>
        </div>

        <?php if (empty($pedidos)): ?>
            <div class="text-center py-5">
                <i class="fas fa-clipboard-list text-muted" style="font-size: 4rem;"></i>
                <h3 class="mt-3">No hay pedidos</h3>
                <p class="text-muted">Aún no se han realizado pedidos en el sistema.</p>
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

                <div class="card order-card" data-status="<?php echo $pedido['estado']; ?>">
                    <div class="card-header bg-light d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="mb-0">Pedido #<?php echo str_pad($pedido['id_pedido'], 6, '0', STR_PAD_LEFT); ?></h5>
                            <small class="text-muted">
                                <i class="fas fa-calendar-alt"></i> 
                                <?php echo date('d/m/Y H:i', strtotime($pedido['creado_en'])); ?>
                                | Cliente: <strong><?php echo htmlspecialchars($pedido['nombre_cliente'] ?? $pedido['receptor']); ?></strong>
                            </small>
                        </div>
                        <div class="text-end">
                            <span class="badge bg-<?php echo $pedido['estado_class']; ?> status-badge">
                                <?php echo $pedido['estado_formatted']; ?>
                            </span>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Detalles de productos -->
                        <div class="row mb-3">
                            <div class="col-md-8">
                                <h6>Productos:</h6>
                                <?php foreach ($detalles as $detalle): ?>
                                    <div class="order-details d-flex justify-content-between align-items-center">
                                        <div>
                                            <strong><?php echo htmlspecialchars($detalle['nombre_producto']); ?></strong>
                                            <small class="text-muted d-block">
                                                Cantidad: <?php echo $detalle['cantidad']; ?> × 
                                                $<?php echo number_format($detalle['precio'], 2); ?>
                                            </small>
                                        </div>
                                        <div class="fw-bold">
                                            $<?php echo number_format($detalle['subtotal'], 2); ?>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            <div class="col-md-4">
                                <h6>Información de contacto:</h6>
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
                                    <?php echo ucfirst(htmlspecialchars($pedido['metodo_pago'])); ?>
                                </p>
                            </div>
                        </div>

                        <!-- Total y acciones -->
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="fs-5 fw-bold">
                                Total: $<?php echo number_format($pedido['total'], 2); ?>
                            </div>
                            <div class="action-buttons">
                                <?php if ($pedido['estado'] == 'pendiente'): ?>
                                    <button class="btn btn-success" onclick="updateOrderStatus(<?php echo $pedido['id_pedido']; ?>, 'aceptado')">
                                        <i class="fas fa-check"></i> Aceptar
                                    </button>
                                    <button class="btn btn-danger" onclick="updateOrderStatus(<?php echo $pedido['id_pedido']; ?>, 'cancelado')">
                                        <i class="fas fa-times"></i> Cancelar
                                    </button>
                                <?php elseif ($pedido['estado'] == 'aceptado'): ?>
                                    <button class="btn btn-primary" onclick="generateInvoice(<?php echo $pedido['id_pedido']; ?>)">
                                        <i class="fas fa-file-invoice"></i> Generar Factura
                                    </button>
                                    <button class="btn btn-warning" onclick="updateOrderStatus(<?php echo $pedido['id_pedido']; ?>, 'cancelado')">
                                        <i class="fas fa-times"></i> Cancelar
                                    </button>
                                <?php else: ?>
                                    <span class="text-muted">No hay acciones disponibles</span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function filterOrders(status) {
            const cards = document.querySelectorAll('.order-card');
            const buttons = document.querySelectorAll('.btn-group .btn');
            
            // Update active button
            buttons.forEach(btn => btn.classList.remove('active'));
            event.target.classList.add('active');
            
            // Filter cards
            cards.forEach(card => {
                if (status === 'all' || card.dataset.status === status) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        }

        async function updateOrderStatus(orderId, newStatus) {
            const confirmMessages = {
                'aceptado': '¿Está seguro de aceptar este pedido? Se reducirá el stock de los productos.',
                'cancelado': '¿Está seguro de cancelar este pedido?'
            };
            
            if (!confirm(confirmMessages[newStatus])) {
                return;
            }
            
            try {
                const response = await fetch('./model/M_ActualizarEstadoPedido.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        pedido_id: orderId,
                        nuevo_estado: newStatus
                    })
                });
                
                const result = await response.json();
                
                if (result.success) {
                    showAlert('Estado actualizado correctamente', 'success');
                    setTimeout(() => {
                        location.reload();
                    }, 1500);
                } else {
                    showAlert('Error: ' + result.error, 'danger');
                }
            } catch (error) {
                showAlert('Error al actualizar el estado', 'danger');
                console.error('Error:', error);
            }
        }

        function generateInvoice(orderId) {
            // Abrir la factura en una nueva ventana
            window.open(`index.php?opc=generar_factura&pedido_id=${orderId}`, '_blank');
        }

        function showAlert(message, type = 'info') {
            const alertDiv = document.createElement('div');
            alertDiv.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
            alertDiv.style.cssText = 'top: 20px; right: 20px; z-index: 9999; max-width: 400px;';
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
