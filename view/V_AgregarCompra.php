<?php
// Incluir conexión para obtener proveedores y productos
include_once './config/Cconexion.php';

// Obtener proveedores activos para el select
$sql_proveedores = "SELECT * FROM Proveedores WHERE activo = 1";
$resultado_proveedores = mysqli_query($conexion, $sql_proveedores);
$proveedores = mysqli_fetch_all($resultado_proveedores, MYSQLI_ASSOC);

// Configuración de la página
$page_title = "Ingresar Compra";
$page_subtitle = "Registra una nueva compra de productos";
$page_icon = "fas fa-shopping-cart";

// Breadcrumbs
$breadcrumbs = [
    ['title' => 'Compras', 'url' => '#'],
    ['title' => 'Ingresar Compra', 'active' => true]
];

// Acciones del header
$header_actions = [
    [
        'title' => 'Ver Dashboard',
        'url' => 'index.php?opc=dashboard',
        'icon' => 'fas fa-home',
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
                Nueva Compra
            </h3>
            <p class="text-muted mb-0">
                Registra una compra de productos de un proveedor
            </p>
        </div>
        <div class="action-buttons">
            <a href="index.php?opc=dashboard" class="btn-action btn-secondary-custom">
                <i class="fas fa-arrow-left"></i>
                Volver
            </a>
        </div>
    </div>

    <form action="./model/M_AgregarCompra.php" method="POST" id="formCompra" class="form-modern">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="proveedor" class="form-label">
                        <i class="fas fa-truck text-primary"></i>
                        Proveedor
                    </label>
                    <select id="proveedor" name="id_proveedor" class="form-select" required onchange="cargarProductos()">
                        <option value="">Seleccionar proveedor</option>
                        <?php foreach ($proveedores as $proveedor): ?>
                            <option value="<?php echo $proveedor['id_proveedor']; ?>">
                                <?php echo htmlspecialchars($proveedor['descripcion']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <small class="form-text text-muted">
                        Selecciona el proveedor para ver sus productos disponibles
                    </small>
                </div>
            </div>
        </div>

        <div id="productosContainer" style="display: none;" class="mt-4">
            <div class="d-flex align-items-center mb-3">
                <h4 class="mb-0">
                    <i class="fas fa-boxes text-info"></i>
                    Productos del Proveedor
                </h4>
            </div>
            
            <div id="listaProductos" class="table-container">
                <!-- Los productos se cargarán aquí dinámicamente -->
            </div>
        </div>

        <div class="form-actions text-center mt-4">
            <button type="submit" id="btnSubmit" class="btn-action btn-success-custom me-3" style="display: none;">
                <i class="fas fa-save"></i>
                Registrar Compra
            </button>
            <a href="index.php?opc=dashboard" class="btn-action btn-secondary-custom">
                <i class="fas fa-times"></i>
                Cancelar
            </a>
        </div>
    </form>
</div>

<script>
function cargarProductos() {
    const proveedorId = document.getElementById('proveedor').value;
    const container = document.getElementById('productosContainer');
    const listaProductos = document.getElementById('listaProductos');
    const btnSubmit = document.getElementById('btnSubmit');
    
    if (proveedorId === '') {
        container.style.display = 'none';
        btnSubmit.style.display = 'none';
        return;
    }
    
    // Mostrar indicador de carga
    listaProductos.innerHTML = `
        <div class="text-center py-4">
            <i class="fas fa-spinner fa-spin fa-2x text-primary mb-2"></i>
            <p class="text-muted">Cargando productos...</p>
        </div>
    `;
    container.style.display = 'block';
    
    // Realizar petición AJAX para obtener productos del proveedor
    fetch(`./model/obtener_productos_proveedor.php?id_proveedor=${proveedorId}`)
        .then(response => response.json())
        .then(result => {
            if (result.error) {
                console.error('Error:', result.message);
                listaProductos.innerHTML = `
                    <div class="alert alert-danger" role="alert">
                        <i class="fas fa-exclamation-triangle"></i>
                        Error: ${result.message}
                    </div>
                `;
                btnSubmit.style.display = 'none';
                return;
            }
            
            const data = result.data || result; // Compatibilidad con ambos formatos
            
            if (data.length > 0) {
                let html = `
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th width="40%">Producto</th>
                                <th width="20%">Stock Actual</th>
                                <th width="30%">Cantidad a Comprar</th>
                                <th width="10%">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                `;
                
                data.forEach(producto => {
                    const stockClass = producto.stock > 10 ? 'bg-success' : (producto.stock > 0 ? 'bg-warning' : 'bg-danger');
                    html += `
                        <tr>
                            <td>
                                <strong>${producto.nombre}</strong>
                                <br><small class="text-muted">${producto.descripcion || ''}</small>
                            </td>
                            <td>
                                <span class="badge ${stockClass}">${producto.stock}</span>
                            </td>
                            <td>
                                <input type="number" 
                                       name="cantidades[${producto.id_producto}]" 
                                       min="0" 
                                       value="0" 
                                       class="form-control cantidad"
                                       placeholder="0"
                                       onchange="calcularTotal(this, ${producto.precio || 0})">
                            </td>
                            <td>
                                <span class="total-producto fw-bold text-success">$0.00</span>
                            </td>
                        </tr>
                    `;
                });
                
                html += `
                        </tbody>
                        <tfoot>
                            <tr class="table-info">
                                <td colspan="3" class="text-end"><strong>Total General:</strong></td>
                                <td><strong id="totalGeneral" class="text-success">$0.00</strong></td>
                            </tr>
                        </tfoot>
                    </table>
                `;
                
                listaProductos.innerHTML = html;
                btnSubmit.style.display = 'inline-block';
                
                // Agregar event listeners para cálculos automáticos
                document.querySelectorAll('.cantidad').forEach(input => {
                    input.addEventListener('input', function() {
                        calcularTotales();
                    });
                });
                
            } else {
                listaProductos.innerHTML = `
                    <div class="text-center py-5">
                        <i class="fas fa-box-open fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">No hay productos disponibles</h5>
                        <p class="text-muted">Este proveedor no tiene productos registrados.</p>
                    </div>
                `;
                btnSubmit.style.display = 'none';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            listaProductos.innerHTML = `
                <div class="alert alert-danger" role="alert">
                    <i class="fas fa-exclamation-triangle"></i>
                    Error al cargar los productos. Por favor, intente nuevamente.
                </div>
            `;
            btnSubmit.style.display = 'none';
        });
}

function calcularTotales() {
    let totalGeneral = 0;
    
    document.querySelectorAll('.cantidad').forEach(input => {
        const cantidad = parseInt(input.value) || 0;
        // Por ahora solo contamos la cantidad, en el futuro se puede agregar precio
        const total = cantidad * 0; // Precio será 0 por ahora
        
        const totalSpan = input.closest('tr').querySelector('.total-producto');
        totalSpan.textContent = `$${total.toFixed(2)}`;
        
        totalGeneral += total;
    });
    
    const totalGeneralSpan = document.getElementById('totalGeneral');
    if (totalGeneralSpan) {
        totalGeneralSpan.textContent = `$${totalGeneral.toFixed(2)}`;
    }
}

// Validar formulario antes de enviar
document.getElementById('formCompra').addEventListener('submit', function(e) {
    const cantidades = document.querySelectorAll('.cantidad');
    let tieneProductos = false;
    
    cantidades.forEach(cantidad => {
        if (parseInt(cantidad.value) > 0) {
            tieneProductos = true;
        }
    });
    
    if (!tieneProductos) {
        e.preventDefault();
        alert('Debe agregar al menos un producto a la compra.');
        return false;
    }
    
    // Confirmar la compra
    if (!confirm('¿Está seguro de que desea registrar esta compra?')) {
        e.preventDefault();
        return false;
    }
});
</script>

<?php include './view/V_Footer.php'; ?>
</body>
</html>