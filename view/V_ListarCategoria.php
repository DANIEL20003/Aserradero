<?php
// Configuración de la página
$page_title = "Lista de Categorías";
$page_subtitle = "Gestiona las categorías de productos";
$page_icon = "fas fa-list";

// Breadcrumbs
$breadcrumbs = [
    ['title' => 'Categorías', 'url' => '#'],
    ['title' => 'Lista de Categorías', 'active' => true]
];

// Acciones del header
$header_actions = [
    [
        'title' => 'Agregar Categoría',
        'url' => 'index.php?opc=agregar_categoria',
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

include_once './model/M_ListarCategorias.php';

$categorias = isset($_SESSION['categorias']) ? $_SESSION['categorias'] : [];
?>

<div class="content-card">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="mb-1">
                <i class="fas fa-list text-primary"></i>
                Categorías Registradas
            </h3>
            <p class="text-muted mb-0">
                Total de categorías: <strong><?php echo count($categorias); ?></strong>
            </p>
        </div>
        <div class="action-buttons">
            <a href="index.php?opc=agregar_categoria" class="btn-action btn-success-custom">
                <i class="fas fa-plus"></i>
                Nueva Categoría
            </a>
        </div>
    </div>

    <?php if (!empty($categorias)): ?>
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
                    <?php foreach ($categorias as $categoria): ?>
                        <tr>
                            <td>
                                <span class="badge bg-primary"><?php echo htmlspecialchars($categoria['id_categoria']); ?></span>
                            </td>
                            <td>
                                <strong><?php echo htmlspecialchars($categoria['descripcion']); ?></strong>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="index.php?opc=editar_categoria&id=<?php echo $categoria['id_categoria']; ?>" 
                                       class="btn btn-sm btn-outline-primary" 
                                       title="Editar categoría">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="./model/M_EliminarCategoria.php?id=<?php echo $categoria['id_categoria']; ?>" 
                                       class="btn btn-sm btn-outline-danger" 
                                       title="Eliminar categoría"
                                       onclick="return confirm('¿Está seguro de que desea eliminar esta categoría?')">
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
            <i class="fas fa-list fa-3x text-muted mb-3"></i>
            <h4 class="text-muted">No hay categorías registradas</h4>
            <p class="text-muted">Comienza agregando tu primera categoría.</p>
            <a href="index.php?opc=agregar_categoria" class="btn-action btn-success-custom">
                <i class="fas fa-plus"></i>
                Agregar Primera Categoría
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

