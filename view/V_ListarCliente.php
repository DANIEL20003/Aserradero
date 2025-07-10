<?php
// Configuración de la página
$page_title = "Lista de Clientes";
$page_subtitle = "Gestiona los clientes de la empresa";
$page_icon = "fas fa-users";

// Breadcrumbs
$breadcrumbs = [
    ['title' => 'Clientes', 'url' => '#'],
    ['title' => 'Lista de Clientes', 'active' => true]
];

// Acciones del header
$header_actions = [
    [
        'title' => 'Agregar Cliente',
        'url' => 'index.php?opc=agregar_cliente',
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

include './model/M_ListarClientes.php';

$clientes = isset($_SESSION['clientes']) ? $_SESSION['clientes'] : [];
print_r($clientes); // Para depuración, eliminar en producción
?>

<div class="content-card">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="mb-1">
                <i class="fas fa-users text-primary"></i>
                Clientes Registrados
            </h3>
            <p class="text-muted mb-0">
                Total de clientes: <strong><?php echo count($clientes); ?></strong>
            </p>
        </div>
        <div class="action-buttons">
            <a href="index.php?opc=agregar_cliente" class="btn-action btn-success-custom">
                <i class="fas fa-plus"></i>
                Nuevo Cliente
            </a>
        </div>
    </div>

    <?php if (!empty($clientes)): ?>
        <div class="table-container">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th width="8%">ID</th>
                        <th width="20%">Nombre</th>
                        <th width="25%">Correo</th>
                        <th width="15%">Cédula</th>
                        <th width="17%">Fecha Registro</th>
                        <th width="15%">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($clientes as $cliente): ?>
                        <tr>
                            <td>
                                <span class="badge bg-primary"><?php echo htmlspecialchars($cliente['id_usuario']); ?></span>
                            </td>
                            <td>
                                <strong><?php echo htmlspecialchars($cliente['nombre']); ?></strong>
                            </td>
                            <td>
                                <i class="fas fa-envelope text-muted me-2"></i>
                                <span><?php echo htmlspecialchars($cliente['correo']); ?></span>
                            </td>
                            <td>
                                <span class="badge bg-info"><?php echo htmlspecialchars($cliente['cedula']); ?></span>
                            </td>
                            <td>
                                <small class="text-muted">
                                    <?php echo date('d/m/Y', strtotime($cliente['fecha_registro'])); ?>
                                </small>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="index.php?opc=editar_cliente&id=<?php echo $cliente['id_usuario']; ?>" 
                                       class="btn btn-sm btn-outline-primary" 
                                       title="Editar cliente">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="./model/M_EliminarCliente.php?id=<?php echo $cliente['id_usuario']; ?>" 
                                       class="btn btn-sm btn-outline-danger" 
                                       title="Eliminar cliente"
                                       onclick="return confirm('¿Está seguro de que desea eliminar este cliente?')">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <div class="text-center py-5">
            <i class="fas fa-users fa-3x text-muted mb-3"></i>
            <h4 class="text-muted">No hay clientes registrados</h4>
            <p class="text-muted">Comienza agregando tu primer cliente.</p>
            <a href="index.php?opc=agregar_cliente" class="btn-action btn-success-custom">
                <i class="fas fa-plus"></i>
                Agregar Primer Cliente
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