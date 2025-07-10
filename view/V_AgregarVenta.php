<?php
// Incluir conexión para obtener clientes y productos
include_once './config/Cconexion.php';

// Obtener clientes activos para el select
$sql_clientes = "SELECT id_usuario, nombre, cedula FROM Usuarios WHERE activo = 1";
$resultado_clientes = mysqli_query($conexion, $sql_clientes);
$clientes = mysqli_fetch_all($resultado_clientes, MYSQLI_ASSOC);

// Obtener productos activos para la venta
$sql_productos = "SELECT p.*, c.descripcion as categoria_nombre 
                  FROM Productos p 
                  LEFT JOIN Categorias c ON p.id_categoria = c.id_categoria 
                  WHERE p.activo = 1 AND p.stock > 0";
$resultado_productos = mysqli_query($conexion, $sql_productos);
$productos = mysqli_fetch_all($resultado_productos, MYSQLI_ASSOC);

// Configuración de la página
$page_title = "Nueva Venta";
$page_subtitle = "Registra una nueva venta de productos";
$page_icon = "fas fa-shopping-cart";

// Breadcrumbs
$breadcrumbs = [
    ['title' => 'Ventas', 'url' => 'index.php?opc=listar_ventas'],
    ['title' => 'Nueva Venta', 'active' => true]
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
                <i class="fas fa-shopping-cart text-success"></i>
                Nueva Venta
            </h3>
            <p class="text-muted mb-0">
                Registra una venta de productos a un cliente
            </p>
        </div>
        <div class="action-buttons">
            <a href="index.php?opc=listar_ventas" class="btn-action btn-secondary-custom">
                <i class="fas fa-arrow-left"></i>
                Volver
            </a>
        </div>
    </div>

    <form action="./model/M_AgregarVenta.php" method="POST" id="formVenta" class="form-modern">
        <!-- Información del cliente -->
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="cliente" class="form-label">
                        <i class="fas fa-user text-primary"></i>
                        Cliente
                    </label>
                    <select id="cliente" name="id_cliente" class="form-select" required>
                        <option value="">Seleccionar cliente</option>
                        <?php foreach ($clientes as $cliente): ?>
                            <option value="<?php echo $cliente['id_usuario']; ?>">
                                <?php echo htmlspecialchars($cliente['nombre']); ?> - <?php echo htmlspecialchars($cliente['cedula']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <small class="form-text text-muted">
                        Si el cliente no está registrado, debe agregarlo primero
                    </small>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="estado" class="form-label">
                        <i class="fas fa-info-circle text-info"></i>
                        Estado de la Venta
                    </label>
                    <select id="estado" name="estado" class="form-select" required>
                        <option value="pendiente">Pendiente</option>
                        <option value="completado">Completado</option>
                        <option value="cancelado">Cancelado</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Productos -->
        <div class="mt-4">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <h4 class="mb-0">
                    <i class="fas fa-boxes text-info"></i>
                    Productos a Vender
                </h4>
                <button type="button" class="btn btn-sm btn-outline-success" onclick="agregarProducto()">
                    <i class="fas fa-plus"></i>
                    Agregar Producto
                </button>
            </div>
            
            <div id="productosContainer" class="table-container">
                <table class="table table-bordered mb-0">
                    <thead>
                        <tr>
                            <th width="35%">Producto</th>
                            <th width="15%">Stock</th>
                            <th width="15%">Precio</th>
                            <th width="15%">Cantidad</th>
                            <th width="15%">Subtotal</th>
                            <th width="5%">Acción</th>
                        </tr>
                    </thead>
                    <tbody id="productosBody">
                        <!-- Los productos se agregarán aquí dinámicamente -->
                    </tbody>
                    <tfoot>
                        <tr class="table-info">
                            <td colspan="4" class="text-end"><strong>Total General:</strong></td>
                            <td><strong id="totalGeneral" class="text-success">$0.00</strong></td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        <div class="form-actions text-center mt-4">
            <button type="submit" id="btnSubmit" class="btn-action btn-success-custom me-3">
                <i class="fas fa-save"></i>
                Registrar Venta
            </button>
            <a href="index.php?opc=listar_ventas" class="btn-action btn-secondary-custom">
                <i class="fas fa-times"></i>
                Cancelar
            </a>
        </div>
    </form>
</div>

<!-- Template para fila de producto -->
<template id="productoRowTemplate">
    <tr class="producto-row">
        <td>
            <select name="productos[]" class="form-select producto-select" required onchange="actualizarProducto(this)">
                <option value="">Seleccionar producto</option>
                <?php foreach ($productos as $producto): ?>
                    <option value="<?php echo $producto['id_producto']; ?>" 
                            data-stock="<?php echo $producto['stock']; ?>" 
                            data-precio="<?php echo $producto['precio']; ?>">
                        <?php echo htmlspecialchars($producto['nombre']); ?> - 
                        <?php echo htmlspecialchars($producto['categoria_nombre'] ?? 'Sin categoría'); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </td>
        <td>
            <span class="stock-display badge bg-info">0</span>
        </td>
        <td>
            <span class="precio-display fw-bold text-success">$0.00</span>
        </td>
        <td>
            <input type="number" name="cantidades[]" min="1" value="1" class="form-control cantidad-input" 
                   onchange="calcularSubtotal(this)" required>
        </td>
        <td>
            <span class="subtotal-display fw-bold text-primary">$0.00</span>
        </td>
        <td>
            <button type="button" class="btn btn-sm btn-outline-danger" onclick="eliminarProducto(this)">
                <i class="fas fa-trash"></i>
            </button>
        </td>
    </tr>
</template>

<script>
let contadorProductos = 0;

function agregarProducto() {
    const template = document.getElementById('productoRowTemplate');
    const clone = template.content.cloneNode(true);
    const tbody = document.getElementById('productosBody');
    
    // Agregar ID único a la fila
    const row = clone.querySelector('.producto-row');
    row.setAttribute('data-id', contadorProductos++);
    
    tbody.appendChild(clone);
}

function eliminarProducto(button) {
    const row = button.closest('.producto-row');
    row.remove();
    calcularTotal();
}

function actualizarProducto(select) {
    const row = select.closest('.producto-row');
    const option = select.selectedOptions[0];
    
    if (option.value) {
        const stock = option.getAttribute('data-stock');
        const precio = parseFloat(option.getAttribute('data-precio'));
        
        row.querySelector('.stock-display').textContent = stock;
        row.querySelector('.precio-display').textContent = `$${precio.toFixed(2)}`;
        row.querySelector('.cantidad-input').max = stock;
        
        calcularSubtotal(row.querySelector('.cantidad-input'));
    } else {
        row.querySelector('.stock-display').textContent = '0';
        row.querySelector('.precio-display').textContent = '$0.00';
        row.querySelector('.subtotal-display').textContent = '$0.00';
        calcularTotal();
    }
}

function calcularSubtotal(input) {
    const row = input.closest('.producto-row');
    const select = row.querySelector('.producto-select');
    const option = select.selectedOptions[0];
    
    if (option && option.value) {
        const precio = parseFloat(option.getAttribute('data-precio'));
        const cantidad = parseInt(input.value) || 0;
        const stock = parseInt(option.getAttribute('data-stock'));
        
        if (cantidad > stock) {
            alert(`La cantidad no puede ser mayor al stock disponible (${stock})`);
            input.value = stock;
            return;
        }
        
        const subtotal = precio * cantidad;
        row.querySelector('.subtotal-display').textContent = `$${subtotal.toFixed(2)}`;
    }
    
    calcularTotal();
}

function calcularTotal() {
    let total = 0;
    const subtotales = document.querySelectorAll('.subtotal-display');
    
    subtotales.forEach(subtotal => {
        const valor = parseFloat(subtotal.textContent.replace('$', '')) || 0;
        total += valor;
    });
    
    document.getElementById('totalGeneral').textContent = `$${total.toFixed(2)}`;
}

// Validar formulario antes de enviar
document.getElementById('formVenta').addEventListener('submit', function(e) {
    const productos = document.querySelectorAll('.producto-select');
    let tieneProductos = false;
    
    productos.forEach(select => {
        if (select.value) {
            tieneProductos = true;
        }
    });
    
    if (!tieneProductos) {
        e.preventDefault();
        alert('Debe agregar al menos un producto a la venta.');
        return false;
    }
    
    // Confirmar la venta
    if (!confirm('¿Está seguro de que desea registrar esta venta?')) {
        e.preventDefault();
        return false;
    }
});

// Agregar el primer producto al cargar la página
document.addEventListener('DOMContentLoaded', function() {
    agregarProducto();
});
</script>

<?php include './view/V_Footer.php'; ?>
</body>
</html>
