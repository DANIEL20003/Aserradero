<?php
// Configuración de la página
$page_title = "Lista de Productos";
$page_subtitle = "Gestiona todos los productos de tu inventario";
$page_icon = "fas fa-boxes";

// Breadcrumbs
$breadcrumbs = [
    ['title' => 'Productos', 'url' => '#'],
    ['title' => 'Lista de Productos', 'active' => true]
];

// Acciones del header
$header_actions = [
    [
        'title' => 'Agregar Producto',
        'url' => 'index.php?opc=agregar_producto',
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

include './model/M_ListarProductos.php';

$productos = isset($_SESSION['productos']) ? $_SESSION['productos'] : [];
?>

<div class="content-card">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="mb-1">
                <i class="fas fa-list text-primary"></i>
                Productos Registrados
            </h3>
            <p class="text-muted mb-0">
                Total de productos: <strong><?php echo count($productos); ?></strong>
            </p>
        </div>
		<div class="category-filter">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <h5 class="mb-3 mb-md-0">Filtrar por categoría:</h5>
                    </div>
                    <div class="col-md-6">
                        <select class="form-select" id="categoryFilter" onchange="filterProducts()">
                            <option value="">Todas las categorías</option>
                            <!-- Las categorías se cargarán dinámicamente -->
                        </select>
                    </div>
                </div>
            </div>
        <div class="action-buttons">
            <a href="index.php?opc=agregar_producto" class="btn-action btn-success-custom">
                <i class="fas fa-plus"></i>
                Nuevo Producto
            </a>
        </div>
    </div>

    <?php if (!empty($productos)): ?>
        <div class="table-container">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th width="8%">ID</th>
                        <th width="12%">Imagen</th>
                        <th width="20%">Nombre</th>
                        <th width="20%">Descripción</th>
                        <th width="10%">Precio</th>
                        <th width="8%">Stock</th>
                        <th width="12%">Categoría</th>
						<?php if($_SESSION['esAdmin']): ?>
                        <th width="12%">Proveedor</th>
                        <th width="15%">Acciones</th>
						<?php endif; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($productos as $producto): ?>
                        <tr>
                            <td>
                                <span class="badge bg-primary"><?php echo htmlspecialchars($producto['id_producto']); ?></span>
                            </td>
                            <td class="text-center">
                                <?php if (!empty($producto['imagen_url'])): ?>
                                    <img src="<?php echo htmlspecialchars($producto['imagen_url']); ?>" 
                                         alt="Imagen del producto" 
                                         class="rounded"
                                         style="max-width: 60px; max-height: 60px; object-fit: cover; border: 2px solid #dee2e6;">
                                <?php else: ?>
                                    <div class="d-flex align-items-center justify-content-center" 
                                         style="width: 60px; height: 60px; background-color: #f8f9fa; border: 2px dashed #dee2e6; border-radius: 4px;">
                                        <i class="fas fa-image text-muted"></i>
                                    </div>
                                <?php endif; ?>
                            </td>
                            <td>
                                <strong><?php echo htmlspecialchars($producto['nombre'] ?? ''); ?></strong>
                            </td>
                            <td>
                                <span class="text-muted"><?php echo htmlspecialchars($producto['descripcion'] ?? ''); ?></span>
                            </td>
                            <td>
                                <span class="fw-bold text-success">
                                    $<?php echo number_format(floatval($producto['precio'] ?? 0), 2); ?>
                                </span>
                            </td>
                            <td>
                                <?php 
                                $stock = intval($producto['stock'] ?? 0);
                                $badge_class = $stock > 10 ? 'bg-success' : ($stock > 0 ? 'bg-warning' : 'bg-danger');
                                ?>
                                <span class="badge <?php echo $badge_class; ?>">
                                    <?php echo $stock; ?>
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-info">
                                    <?php echo htmlspecialchars($producto['categoria_nombre'] ?? 'Sin categoría'); ?>
                                </span>
                            </td>
							<?php if($_SESSION['esAdmin']): ?>
                            <td>
                                <span class="badge bg-secondary">
                                    <?php echo htmlspecialchars($producto['proveedor_nombre'] ?? 'Sin proveedor'); ?>
                                </span>
                            </td>
							
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="index.php?opc=editar_producto&id=<?php echo $producto['id_producto']; ?>" 
                                       class="btn btn-sm btn-outline-primary" 
                                       title="Editar producto">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="./model/M_EliminarProducto.php?id=<?php echo $producto['id_producto']; ?>" 
                                       class="btn btn-sm btn-outline-danger" 
                                       title="Eliminar producto"
                                       onclick="return confirm('¿Está seguro de que desea eliminar este producto?')">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </div>
                            </td>
							<?php endif; ?>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <div class="text-center py-5">
            <i class="fas fa-box-open fa-3x text-muted mb-3"></i>
            <h4 class="text-muted">No hay productos registrados</h4>
            <p class="text-muted">Comienza agregando tu primer producto al inventario.</p>
            <a href="index.php?opc=agregar_producto" class="btn-action btn-success-custom">
                <i class="fas fa-plus"></i>
                Agregar Primer Producto
            </a>
        </div>
    <?php endif; ?>
</div>

        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
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