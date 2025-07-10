<?php
// Configuración de la página
$page_title = "Lista de Ventas";
$page_subtitle = "Gestiona las ventas realizadas";
$page_icon = "fas fa-cash-register";

// Breadcrumbs
$breadcrumbs = [
    ['title' => 'Ventas', 'url' => '#'],
    ['title' => 'Lista de Ventas', 'active' => true]
];

// Acciones del header
$header_actions = [
    [
        'title' => 'Nueva Venta',
        'url' => 'index.php?opc=agregar_venta',
        'icon' => 'fas fa-plus',
        'class' => 'btn-success-custom'
    ]
];

// Incluir header
include './view/V_Header_Admin.php';

// Iniciar sesión si no está iniciada
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include_once './model/M_ListarVentas.php';

$ventas = isset($_SESSION['ventas']) ? $_SESSION['ventas'] : [];
?>

<div class="content-card">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="mb-1">
                <i class="fas fa-cash-register text-primary"></i>
                Ventas Registradas
            </h3>
            <p class="text-muted mb-0">
                Total de ventas: <strong><?php echo count($ventas); ?></strong>
            </p>
        </div>
        <div class="action-buttons">
            <a href="index.php?opc=agregar_venta" class="btn-action btn-success-custom">
                <i class="fas fa-plus"></i>
                Nueva Venta
            </a>
        </div>
    </div>

    <?php if (!empty($ventas)): ?>
        <div class="table-container">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th width="8%">ID</th>
                        <th width="25%">Cliente</th>
                        <th width="15%">Total</th>
                        <th width="12%">Estado</th>
                        <th width="20%">Fecha</th>
                        <th width="20%">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($ventas as $venta): ?>
                        <tr>
                            <td>
                                <span class="badge bg-primary"><?php echo htmlspecialchars($venta['id_pedido']); ?></span>
                            </td>
                            <td>
                                <strong><?php echo htmlspecialchars($venta['cliente_nombre'] ?? 'Cliente no especificado'); ?></strong>
                                <?php if (!empty($venta['cliente_cedula'])): ?>
                                    <br><small class="text-muted">Cédula: <?php echo htmlspecialchars($venta['cliente_cedula']); ?></small>
                                <?php endif; ?>
                            </td>
                            <td>
                                <span class="fw-bold text-success">
                                    $<?php echo number_format(floatval($venta['total'] ?? 0), 2); ?>
                                </span>
                            </td>
                            <td>
                                <?php 
                                $estado = $venta['estado'] ?? 'pendiente';
                                $badge_class = $estado === 'completado' ? 'bg-success' : ($estado === 'cancelado' ? 'bg-danger' : 'bg-warning');
                                ?>
                                <span class="badge <?php echo $badge_class; ?>">
                                    <?php echo ucfirst(htmlspecialchars($estado)); ?>
                                </span>
                            </td>
                            <td>
                                <small class="text-muted">
                                    <?php echo date('d/m/Y H:i', strtotime($venta['creado_en'])); ?>
                                </small>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="index.php?opc=ver_venta&id=<?php echo $venta['id_pedido']; ?>" 
                                       class="btn btn-sm btn-outline-info" 
                                       title="Ver detalles">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <?php if ($estado !== 'completado'): ?>
                                        <a href="index.php?opc=editar_venta&id=<?php echo $venta['id_pedido']; ?>" 
                                           class="btn btn-sm btn-outline-primary" 
                                           title="Editar venta">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    <?php endif; ?>
                                    <a href="index.php?opc=generar_factura&pedido_id=<?php echo $venta['id_pedido']; ?>" 
                                       class="btn btn-sm btn-outline-success" 
                                       title="Generar factura"
                                       target="_blank">
                                        <i class="fas fa-file-invoice"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Resumen de ventas -->
        <div class="row mt-4">
            <div class="col-md-3">
                <div class="card border-success">
                    <div class="card-body text-center">
                        <h5 class="card-title text-success">
                            <i class="fas fa-check-circle"></i>
                            Completadas
                        </h5>
                        <h3 class="text-success">
                            <?php echo count(array_filter($ventas, fn($v) => ($v['estado'] ?? '') === 'completado')); ?>
                        </h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-warning">
                    <div class="card-body text-center">
                        <h5 class="card-title text-warning">
                            <i class="fas fa-clock"></i>
                            Pendientes
                        </h5>
                        <h3 class="text-warning">
                            <?php echo count(array_filter($ventas, fn($v) => ($v['estado'] ?? '') === 'pendiente')); ?>
                        </h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-danger">
                    <div class="card-body text-center">
                        <h5 class="card-title text-danger">
                            <i class="fas fa-times-circle"></i>
                            Canceladas
                        </h5>
                        <h3 class="text-danger">
                            <?php echo count(array_filter($ventas, fn($v) => ($v['estado'] ?? '') === 'cancelado')); ?>
                        </h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-primary">
                    <div class="card-body text-center">
                        <h5 class="card-title text-primary">
                            <i class="fas fa-dollar-sign"></i>
                            Total Ventas
                        </h5>
                        <h3 class="text-primary">
                            $<?php echo number_format(array_sum(array_column($ventas, 'total')), 2); ?>
                        </h3>
                    </div>
                </div>
            </div>
        </div>
    <?php else: ?>
        <div class="text-center py-5">
            <i class="fas fa-cash-register fa-3x text-muted mb-3"></i>
            <h4 class="text-muted">No hay ventas registradas</h4>
            <p class="text-muted">Comienza registrando tu primera venta.</p>
            <a href="index.php?opc=agregar_venta" class="btn-action btn-success-custom">
                <i class="fas fa-plus"></i>
                Registrar Primera Venta
            </a>
        </div>
    <?php endif; ?>
</div>

<script>
// Efecto de carga para la tabla
document.addEventListener('DOMContentLoaded', function() {
    const rows = document.querySelectorAll('tbody tr');
    rows.forEach((row, index) => {
        row.style.opacity = '0';
        row.style.transform = 'translateY(20px)';
        
        setTimeout(() => {
            row.style.transition = 'all 0.4s ease';
            row.style.opacity = '1';
            row.style.transform = 'translateY(0)';
        }, index * 50);
    });
});

// Tooltip para botones
var tooltipTriggerList = [].slice.call(document.querySelectorAll('[title]'))
var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
    return new bootstrap.Tooltip(tooltipTriggerEl)
});
</script>

<?php include './view/V_Footer.php'; ?>
</body>
</html>
