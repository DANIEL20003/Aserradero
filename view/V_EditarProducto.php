<?php
// Habilitar errores para debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Verificar que se recibió el ID del producto
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "<script>alert('ID de producto no proporcionado.'); window.location.href = 'index.php?opc=listar_productos';</script>";
    exit;
}

$id_producto = intval($_GET['id']);

// Verificar que el ID es válido
if ($id_producto <= 0) {
    echo "<script>alert('ID de producto inválido.'); window.location.href = 'index.php?opc=listar_productos';</script>";
    exit;
}

// Incluir conexión y verificar que funciona
include_once './config/Cconexion.php';

if (!$conexion) {
    echo "<script>alert('Error de conexión a la base de datos: " . mysqli_connect_error() . "'); window.location.href = 'index.php?opc=listar_productos';</script>";
    exit;
}

// Obtener los datos del producto
$sql_producto = "SELECT * FROM Productos WHERE id_producto = ? AND activo = 1";
$stmt_producto = mysqli_prepare($conexion, $sql_producto);
mysqli_stmt_bind_param($stmt_producto, "i", $id_producto);
mysqli_stmt_execute($stmt_producto);
$resultado_producto = mysqli_stmt_get_result($stmt_producto);
$producto = mysqli_fetch_assoc($resultado_producto);

if (!$producto) {
    echo "<script>alert('Producto no encontrado o inactivo. ID: $id_producto'); window.location.href = 'index.php?opc=listar_productos';</script>";
    exit;
}

// Obtener categorías activas para el select
$sql_categorias = "SELECT * FROM Categorias WHERE activo = 1";
$resultado_categorias = mysqli_query($conexion, $sql_categorias);
$categorias = mysqli_fetch_all($resultado_categorias, MYSQLI_ASSOC);

// Obtener proveedores activos para el select
$sql_proveedores = "SELECT * FROM Proveedores WHERE activo = 1";
$resultado_proveedores = mysqli_query($conexion, $sql_proveedores);
$proveedores = mysqli_fetch_all($resultado_proveedores, MYSQLI_ASSOC);

// Configuración de la página
$page_title = "Editar Producto";
$page_subtitle = "Actualiza la información del producto";
$page_icon = "fas fa-edit";

// Breadcrumbs
$breadcrumbs = [
    ['title' => 'Productos', 'url' => 'index.php?opc=listar_productos'],
    ['title' => 'Editar Producto', 'active' => true]
];

// Acciones del header
$header_actions = [
    [
        'title' => 'Ver Productos',
        'url' => 'index.php?opc=listar_productos',
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
                <i class="fas fa-edit text-warning"></i>
                Editar Producto
            </h3>
            <p class="text-muted mb-0">
                Producto ID: <strong><?php echo htmlspecialchars($producto['id_producto']); ?></strong>
            </p>
        </div>
        <div class="action-buttons">
            <a href="index.php?opc=listar_productos" class="btn-action btn-secondary-custom">
                <i class="fas fa-arrow-left"></i>
                Volver
            </a>
        </div>
    </div>

    <form action="./model/M_EditarProducto.php" method="POST" enctype="multipart/form-data" class="form-modern">
        <input type="hidden" name="id_producto" value="<?php echo $producto['id_producto']; ?>">
        
        <div class="row">
            <div class="col-md-8">
                <div class="form-group">
                    <label for="nombre" class="form-label">
                        <i class="fas fa-tag text-primary"></i>
                        Nombre del Producto
                    </label>
                    <input type="text" 
                           id="nombre" 
                           name="nombre" 
                           class="form-control" 
                           value="<?php echo htmlspecialchars($producto['nombre']); ?>" 
                           required
                           placeholder="Ej: Madera de Roble">
                </div>

                <div class="form-group">
                    <label for="descripcion" class="form-label">
                        <i class="fas fa-align-left text-info"></i>
                        Descripción
                    </label>
                    <textarea id="descripcion" 
                              name="descripcion" 
                              class="form-control" 
                              rows="3" 
                              required
                              placeholder="Describe las características del producto..."><?php echo htmlspecialchars($producto['descripcion']); ?></textarea>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="precio" class="form-label">
                                <i class="fas fa-dollar-sign text-success"></i>
                                Precio
                            </label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" 
                                       id="precio" 
                                       name="precio" 
                                       class="form-control" 
                                       step="0.01" 
                                       value="<?php echo $producto['precio']; ?>" 
                                       required
                                       placeholder="0.00">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="stock" class="form-label">
                                <i class="fas fa-cubes text-warning"></i>
                                Stock
                            </label>
                            <input type="number" 
                                   id="stock" 
                                   name="stock" 
                                   class="form-control" 
                                   value="<?php echo $producto['stock']; ?>" 
                                   required
                                   placeholder="0">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="categoria" class="form-label">
                                <i class="fas fa-list text-info"></i>
                                Categoría
                            </label>
                            <select id="categoria" name="id_categoria" class="form-select" required>
                                <option value="">Seleccionar categoría</option>
                                <?php foreach ($categorias as $categoria): ?>
                                    <option value="<?php echo $categoria['id_categoria']; ?>" 
                                            <?php echo ($categoria['id_categoria'] == $producto['id_categoria']) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($categoria['descripcion']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="proveedor" class="form-label">
                                <i class="fas fa-truck text-secondary"></i>
                                Proveedor
                            </label>
                            <select id="proveedor" name="id_proveedor" class="form-select" required>
                                <option value="">Seleccionar proveedor</option>
                                <?php foreach ($proveedores as $proveedor): ?>
                                    <option value="<?php echo $proveedor['id_proveedor']; ?>" 
                                            <?php echo ($proveedor['id_proveedor'] == $producto['id_proveedor']) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($proveedor['descripcion']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group">
                    <label class="form-label">
                        <i class="fas fa-image text-primary"></i>
                        Imagen del Producto
                    </label>
                    
                    <div class="image-upload-container">
                        <?php if (!empty($producto['imagen_url'])): ?>
                            <div class="current-image mb-3">
                                <p class="text-muted mb-2">Imagen actual:</p>
                                <img src="<?php echo htmlspecialchars($producto['imagen_url']); ?>" 
                                     alt="Imagen actual" 
                                     class="img-thumbnail"
                                     style="max-width: 100%; max-height: 200px; object-fit: cover;">
                            </div>
                        <?php else: ?>
                            <div class="no-image mb-3">
                                <div class="d-flex align-items-center justify-content-center bg-light border rounded" 
                                     style="height: 200px;">
                                    <div class="text-center text-muted">
                                        <i class="fas fa-image fa-3x mb-2"></i>
                                        <p>Sin imagen actual</p>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>

                        <input type="file" 
                               id="imagen" 
                               name="imagen" 
                               class="form-control" 
                               accept="image/jpeg,image/png,image/gif,image/webp"
                               onchange="previewImage(this)">
                        <small class="form-text text-muted">
                            Formatos: JPG, PNG, GIF, WEBP<br>
                            Tamaño máximo: 10MB<br>
                            Dejar vacío para mantener la imagen actual
                        </small>
                        
                        <div id="preview-container" class="mt-3" style="display: none;">
                            <p class="text-muted mb-2">Vista previa:</p>
                            <img id="preview-image" class="img-thumbnail" style="max-width: 100%; max-height: 200px;">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="form-actions text-center mt-4">
            <button type="submit" class="btn-action btn-success-custom me-3">
                <i class="fas fa-save"></i>
                Actualizar Producto
            </button>
            <a href="index.php?opc=listar_productos" class="btn-action btn-secondary-custom">
                <i class="fas fa-times"></i>
                Cancelar
            </a>
        </div>
    </form>
</div>

<script>
function previewImage(input) {
    const previewContainer = document.getElementById('preview-container');
    const previewImage = document.getElementById('preview-image');
    
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        
        reader.onload = function(e) {
            previewImage.src = e.target.result;
            previewContainer.style.display = 'block';
        };
        
        reader.readAsDataURL(input.files[0]);
    } else {
        previewContainer.style.display = 'none';
    }
}

// Validación del formulario
document.querySelector('.form-modern').addEventListener('submit', function(e) {
    const precio = document.getElementById('precio').value;
    const stock = document.getElementById('stock').value;
    
    if (parseFloat(precio) < 0) {
        e.preventDefault();
        alert('El precio no puede ser negativo');
        return false;
    }
    
    if (parseInt(stock) < 0) {
        e.preventDefault();
        alert('El stock no puede ser negativo');
        return false;
    }
});
</script>

<?php include './view/V_Footer.php'; ?>
</body>
</html>