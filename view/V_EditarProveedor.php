<?php
// Habilitar errores para debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Verificar que se recibió el ID del proveedor
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "<script>alert('ID de proveedor no proporcionado.'); window.location.href = 'index.php?opc=listar_proveedores';</script>";
    exit;
}

$id_proveedor = intval($_GET['id']);

// Verificar que el ID es válido
if ($id_proveedor <= 0) {
    echo "<script>alert('ID de proveedor inválido.'); window.location.href = 'index.php?opc=listar_proveedores';</script>";
    exit;
}

// Incluir conexión y verificar que funciona
include_once './config/Cconexion.php';

if (!$conexion) {
    echo "<script>alert('Error de conexión a la base de datos: " . mysqli_connect_error() . "'); window.location.href = 'index.php?opc=listar_proveedores';</script>";
    exit;
}

// Obtener los datos del proveedor
$sql = "SELECT * FROM Proveedores WHERE id_proveedor = ? AND activo = 1";
$stmt = mysqli_prepare($conexion, $sql);
mysqli_stmt_bind_param($stmt, "i", $id_proveedor);
mysqli_stmt_execute($stmt);
$resultado = mysqli_stmt_get_result($stmt);
$proveedor = mysqli_fetch_assoc($resultado);

if (!$proveedor) {
    echo "<script>alert('Proveedor no encontrado o inactivo. ID: $id_proveedor'); window.location.href = 'index.php?opc=listar_proveedores';</script>";
    exit;
}

// Configuración de la página
$page_title = "Editar Proveedor";
$page_subtitle = "Actualiza la información del proveedor";
$page_icon = "fas fa-edit";

// Breadcrumbs
$breadcrumbs = [
    ['title' => 'Proveedores', 'url' => 'index.php?opc=listar_proveedores'],
    ['title' => 'Editar Proveedor', 'active' => true]
];

// Acciones del header
$header_actions = [
    [
        'title' => 'Ver Proveedores',
        'url' => 'index.php?opc=listar_proveedores',
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
                Editar Proveedor
            </h3>
            <p class="text-muted mb-0">
                Proveedor ID: <strong><?php echo htmlspecialchars($proveedor['id_proveedor']); ?></strong>
            </p>
        </div>
        <div class="action-buttons">
            <a href="index.php?opc=listar_proveedores" class="btn-action btn-secondary-custom">
                <i class="fas fa-arrow-left"></i>
                Volver
            </a>
        </div>
    </div>

    <form action="./model/M_EditarProveedor.php" method="POST" class="form-modern">
        <input type="hidden" name="id_proveedor" value="<?php echo $proveedor['id_proveedor']; ?>">
        
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="form-group">
                    <label for="nombre" class="form-label">
                        <i class="fas fa-truck text-primary"></i>
                        Nombre del Proveedor
                    </label>
                    <input type="text" 
                           id="nombre" 
                           name="nombre" 
                           class="form-control" 
                           value="<?php echo htmlspecialchars($proveedor['descripcion']); ?>" 
                           required
                           placeholder="Ej: Distribuidora XYZ, Maderas del Norte, etc."
                           maxlength="100">
                    <small class="form-text text-muted">
                        Máximo 100 caracteres
                    </small>
                </div>

                <div class="form-actions text-center mt-4">
                    <button type="submit" class="btn-action btn-success-custom me-3">
                        <i class="fas fa-save"></i>
                        Actualizar Proveedor
                    </button>
                    <a href="index.php?opc=listar_proveedores" class="btn-action btn-secondary-custom">
                        <i class="fas fa-times"></i>
                        Cancelar
                    </a>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
// Validación del formulario
document.querySelector('.form-modern').addEventListener('submit', function(e) {
    const nombre = document.getElementById('nombre').value.trim();
    
    if (nombre.length < 2) {
        e.preventDefault();
        alert('El nombre del proveedor debe tener al menos 2 caracteres');
        return false;
    }
});

// Auto-focus en el campo de nombre
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('nombre').focus();
    document.getElementById('nombre').select();
});
</script>

<?php include './view/V_Footer.php'; ?>
</body>
</html>