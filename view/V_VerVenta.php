<?php
// Obtener el ID de la venta
$id_venta = $_GET['id'] ?? 0;

// Incluir conexión
include_once './config/Cconexion.php';

// Obtener datos de la venta
$sql_venta = "SELECT 
                p.id_pedido,
                p.id_usuario,
                p.total,
                p.estado,
                p.creado_en,
                p.receptor,
                p.correo,
                p.telefono,
                p.identificacion,
                p.metodo_pago,
                u.nombre as cliente_nombre,
                u.correo as cliente_email,
                u.cedula as cliente_cedula
              FROM Pedidos p
              LEFT JOIN Usuarios u ON p.id_usuario = u.id_usuario
              WHERE p.id_pedido = ? AND p.activo = 1";

$stmt = mysqli_prepare($conexion, $sql_venta);
mysqli_stmt_bind_param($stmt, "i", $id_venta);
mysqli_stmt_execute($stmt);
$resultado_venta = mysqli_stmt_get_result($stmt);
$venta = mysqli_fetch_assoc($resultado_venta);

if (!$venta) {
    echo "<script>alert('Venta no encontrada.'); window.location.href = 'index.php?opc=listar_ventas';</script>";
    exit;
}

// Obtener detalles de la venta
$sql_detalles = "SELECT 
                   pd.id_producto,
                   pd.cantidad,
                   pd.precio,
                   (pd.cantidad * pd.precio) as subtotal,
                   pr.nombre as producto_nombre,
                   pr.descripcion as producto_descripcion,
                   c.descripcion as categoria_nombre
                 FROM Pedido_detalles pd
                 LEFT JOIN Productos pr ON pd.id_producto = pr.id_producto
                 LEFT JOIN Categorias c ON pr.id_categoria = c.id_categoria
                 WHERE pd.id_pedido = ?";

$stmt_detalles = mysqli_prepare($conexion, $sql_detalles);
mysqli_stmt_bind_param($stmt_detalles, "i", $id_venta);
mysqli_stmt_execute($stmt_detalles);
$resultado_detalles = mysqli_stmt_get_result($stmt_detalles);
$detalles = mysqli_fetch_all($resultado_detalles, MYSQLI_ASSOC);

// Configuración de la página
$page_title = "Detalle de Venta #" . $venta['id_pedido'];
$page_subtitle = "Información completa de la venta";
$page_icon = "fas fa-receipt";

// Breadcrumbs
$breadcrumbs = [
    ['title' => 'Ventas', 'url' => 'index.php?opc=listar_ventas'],
    ['title' => 'Detalle de Venta', 'active' => true]
];

// Acciones del header
$header_actions = [
    [
        'title' => 'Ver Ventas',
        'url' => 'index.php?opc=listar_ventas',
        'icon' => 'fas fa-list',
        'class' => 'btn-primary-custom'
    ]
];

// Incluir header
include './view/V_Header_Admin.php';
?>

<div class="content-card">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="mb-1">
                <i class="fas fa-receipt text-primary"></i>
                Detalle de Venta #<?php echo $venta['id_pedido']; ?>
            </h3>
            <p class="text-muted mb-0">
                Información completa de la venta
            </p>
        </div>
        <div class="action-buttons">
            <a href="index.php?opc=listar_ventas" class="btn-action btn-secondary-custom">
                <i class="fas fa-arrow-left"></i>
                Volver
            </a>
            <a href="index.php?opc=generar_factura&pedido_id=<?php echo $venta['id_pedido']; ?>" class="btn-action btn-success-custom" target="_blank">
                <i class="fas fa-file-invoice"></i>
                Generar Factura
            </a>
        </div>
    </div>

    <!-- Información general de la venta -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card border-primary">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-info-circle"></i>
                        Información de la Venta
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-6">
                            <strong>ID Venta:</strong>
                        </div>
                        <div class="col-6">
                            <span class="badge bg-primary"><?php echo $venta['id_pedido']; ?></span>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-6">
                            <strong>Estado:</strong>
                        </div>
                        <div class="col-6">
                            <?php 
                            $estado = $venta['estado'];
                            $badge_class = $estado === 'completado' ? 'bg-success' : ($estado === 'cancelado' ? 'bg-danger' : 'bg-warning');
                            ?>
                            <span class="badge <?php echo $badge_class; ?>">
                                <?php echo ucfirst($estado); ?>
                            </span>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-6">
                            <strong>Fecha:</strong>
                        </div>
                        <div class="col-6">
                            <?php echo date('d/m/Y H:i', strtotime($venta['creado_en'])); ?>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-6">
                            <strong>Total:</strong>
                        </div>
                        <div class="col-6">
                            <span class="fw-bold text-success fs-5">
                                $<?php echo number_format($venta['total'], 2); ?>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card border-info">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-user"></i>
                        Información del Cliente
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-4">
                            <strong>Nombre:</strong>
                        </div>
                        <div class="col-8">
                            <?php echo htmlspecialchars($venta['receptor'] ?? $venta['cliente_nombre'] ?? 'No especificado'); ?>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-4">
                            <strong>Identificación:</strong>
                        </div>
                        <div class="col-8">
                            <?php echo htmlspecialchars($venta['identificacion'] ?? $venta['cliente_cedula'] ?? 'No especificado'); ?>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-4">
                            <strong>Correo:</strong>
                        </div>
                        <div class="col-8">
                            <?php echo htmlspecialchars($venta['correo'] ?? $venta['cliente_email'] ?? 'No especificado'); ?>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-4">
                            <strong>Teléfono:</strong>
                        </div>
                        <div class="col-8">
                            <?php echo htmlspecialchars($venta['telefono'] ?? 'No especificado'); ?>
                        </div>
                    </div>
                    <?php if (!empty($venta['metodo_pago'])): ?>
                        <hr>
                        <div class="row">
                            <div class="col-4">
                                <strong>Método de Pago:</strong>
                            </div>
                            <div class="col-8">
                                <?php echo htmlspecialchars($venta['metodo_pago']); ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Detalles de productos -->
    <div class="card">
        <div class="card-header bg-success text-white">
            <h5 class="mb-0">
                <i class="fas fa-shopping-basket"></i>
                Productos Vendidos
            </h5>
        </div>
        <div class="card-body p-0">
            <?php if (!empty($detalles)): ?>
                <div class="table-container">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th width="35%">Producto</th>
                                <th width="15%">Categoría</th>
                                <th width="15%">Precio Unit.</th>
                                <th width="10%">Cantidad</th>
                                <th width="15%">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($detalles as $detalle): ?>
                                <tr>
                                    <td>
                                        <strong><?php echo htmlspecialchars($detalle['producto_nombre']); ?></strong>
                                        <?php if (!empty($detalle['producto_descripcion'])): ?>
                                            <br><small class="text-muted"><?php echo htmlspecialchars($detalle['producto_descripcion']); ?></small>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <span class="badge bg-info">
                                            <?php echo htmlspecialchars($detalle['categoria_nombre'] ?? 'Sin categoría'); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <span class="fw-bold">
                                            $<?php echo number_format($detalle['precio'], 2); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-primary">
                                            <?php echo $detalle['cantidad']; ?>
                                        </span>
                                    </td>
                                    <td>
                                        <span class="fw-bold text-success">
                                            $<?php echo number_format($detalle['subtotal'], 2); ?>
                                        </span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                        <tfoot>
                            <tr class="table-success">
                                <td colspan="4" class="text-end"><strong>Total General:</strong></td>
                                <td><strong class="text-success fs-5">$<?php echo number_format($venta['total'], 2); ?></strong></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            <?php else: ?>
                <div class="text-center py-4">
                    <i class="fas fa-box-open fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">No hay productos en esta venta</h5>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include './view/V_Footer.php'; ?>
</body>
</html>
