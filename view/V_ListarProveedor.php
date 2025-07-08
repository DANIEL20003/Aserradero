<?php
// Configuración de la página
$page_title = "Lista de Proveedores";
$page_subtitle = "Gestiona los proveedores de la empresa";
$page_icon = "fas fa-truck";

// Breadcrumbs
$breadcrumbs = [
    ['title' => 'Proveedores', 'url' => '#'],
    ['title' => 'Lista de Proveedores', 'active' => true]
];

// Acciones del header
$header_actions = [
    [
        'title' => 'Agregar Proveedor',
        'url' => 'index.php?opc=agregar_proveedor',
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

include_once './model/M_ListarProveedores.php';

$proveedores = isset($_SESSION['proveedores']) ? $_SESSION['proveedores'] : [];
?>

<div class="content-card">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="mb-1">
                <i class="fas fa-truck text-primary"></i>
                Proveedores Registrados
            </h3>
            <p class="text-muted mb-0">
                Total de proveedores: <strong><?php echo count($proveedores); ?></strong>
            </p>
        </div>
        <div class="action-buttons">
            <a href="index.php?opc=agregar_proveedor" class="btn-action btn-success-custom">
                <i class="fas fa-plus"></i>
                Nuevo Proveedor
            </a>
        </div>
    </div>

    <?php if (!empty($proveedores)): ?>
        <div class="table-container">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th width="15%">ID</th>
                        <th width="60%">Descripción</th>
                        <th width="25%">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($proveedores as $proveedor): ?>
                        <tr>
                            <td>
                                <span class="badge bg-primary"><?php echo htmlspecialchars($proveedor['id_proveedor']); ?></span>
                            </td>
                            <td>
                                <strong><?php echo htmlspecialchars($proveedor['descripcion']); ?></strong>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="index.php?opc=editar_proveedor&id=<?php echo $proveedor['id_proveedor']; ?>" 
                                       class="btn btn-sm btn-outline-primary" 
                                       title="Editar proveedor">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="./model/M_EliminarProveedor.php?id=<?php echo $proveedor['id_proveedor']; ?>" 
                                       class="btn btn-sm btn-outline-danger" 
                                       title="Eliminar proveedor"
                                       onclick="return confirm('¿Está seguro de que desea eliminar este proveedor?')">
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
            <i class="fas fa-truck fa-3x text-muted mb-3"></i>
            <h4 class="text-muted">No hay proveedores registrados</h4>
            <p class="text-muted">Comienza agregando tu primer proveedor.</p>
            <a href="index.php?opc=agregar_proveedor" class="btn-action btn-success-custom">
                <i class="fas fa-plus"></i>
                Agregar Primer Proveedor
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