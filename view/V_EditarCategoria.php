<?php
// Habilitar errores para debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Verificar que se recibió el ID de la categoría
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "<script>alert('ID de categoría no proporcionado.'); window.location.href = 'index.php?opc=listar_categorias';</script>";
    exit;
}

$id_categoria = intval($_GET['id']);

// Verificar que el ID es válido
if ($id_categoria <= 0) {
    echo "<script>alert('ID de categoría inválido.'); window.location.href = 'index.php?opc=listar_categorias';</script>";
    exit;
}

// Incluir conexión y verificar que funciona
include_once './config/Cconexion.php';

if (!$conexion) {
    echo "<script>alert('Error de conexión a la base de datos: " . mysqli_connect_error() . "'); window.location.href = 'index.php?opc=listar_categorias';</script>";
    exit;
}

// Obtener los datos de la categoría
$sql = "SELECT * FROM Categorias WHERE id_categoria = ? AND activo = 1";
$stmt = mysqli_prepare($conexion, $sql);
mysqli_stmt_bind_param($stmt, "i", $id_categoria);
mysqli_stmt_execute($stmt);
$resultado = mysqli_stmt_get_result($stmt);
$categoria = mysqli_fetch_assoc($resultado);

if (!$categoria) {
    echo "<script>alert('Categoría no encontrada o inactiva. ID: $id_categoria'); window.location.href = 'index.php?opc=listar_categorias';</script>";
    exit;
}

// Configuración de la página
$page_title = "Editar Categoría";
$page_subtitle = "Actualiza la información de la categoría";
$page_icon = "fas fa-edit";

// Breadcrumbs
$breadcrumbs = [
    ['title' => 'Categorías', 'url' => 'index.php?opc=listar_categorias'],
    ['title' => 'Editar Categoría', 'active' => true]
];

// Acciones del header
$header_actions = [
    [
        'title' => 'Ver Categorías',
        'url' => 'index.php?opc=listar_categorias',
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
                Editar Categoría
            </h3>
            <p class="text-muted mb-0">
                Categoría ID: <strong><?php echo htmlspecialchars($categoria['id_categoria']); ?></strong>
            </p>
        </div>
        <div class="action-buttons">
            <a href="index.php?opc=listar_categorias" class="btn-action btn-secondary-custom">
                <i class="fas fa-arrow-left"></i>
                Volver
            </a>
        </div>
    </div>

    <form action="./model/M_EditarCategoria.php" method="POST" class="form-modern">
        <input type="hidden" name="id_categoria" value="<?php echo $categoria['id_categoria']; ?>">
        
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="form-group">
                    <label for="nombre" class="form-label">
                        <i class="fas fa-tag text-primary"></i>
                        Nombre de la Categoría
                    </label>
                    <input type="text" 
                           id="nombre" 
                           name="nombre" 
                           class="form-control" 
                           value="<?php echo htmlspecialchars($categoria['descripcion']); ?>" 
                           required
                           placeholder="Ej: Maderas Duras, Herramientas, etc."
                           maxlength="100">
                    <small class="form-text text-muted">
                        Máximo 100 caracteres
                    </small>
                </div>

                <div class="form-actions text-center mt-4">
                    <button type="submit" class="btn-action btn-success-custom me-3">
                        <i class="fas fa-save"></i>
                        Actualizar Categoría
                    </button>
                    <a href="index.php?opc=listar_categorias" class="btn-action btn-secondary-custom">
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
        alert('El nombre de la categoría debe tener al menos 2 caracteres');
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
