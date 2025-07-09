<?php
// Habilitar errores para debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Verificar que se recibió el ID del cliente
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "<script>alert('ID de cliente no proporcionado.'); window.location.href = 'index.php?opc=listar_clientes';</script>";
    exit;
}

$id_cliente = intval($_GET['id']);

// Verificar que el ID es válido
if ($id_cliente <= 0) {
    echo "<script>alert('ID de cliente inválido.'); window.location.href = 'index.php?opc=listar_clientes';</script>";
    exit;
}

// Incluir conexión y verificar que funciona
include_once './config/Cconexion.php';

if (!$conexion) {
    echo "<script>alert('Error de conexión a la base de datos: " . mysqli_connect_error() . "'); window.location.href = 'index.php?opc=listar_clientes';</script>";
    exit;
}

// Obtener los datos del cliente
$sql = "SELECT * FROM Usuarios WHERE id_usuario = ? AND activo = 1";
$stmt = mysqli_prepare($conexion, $sql);
mysqli_stmt_bind_param($stmt, "i", $id_cliente);
mysqli_stmt_execute($stmt);
$resultado = mysqli_stmt_get_result($stmt);
$cliente = mysqli_fetch_assoc($resultado);

if (!$cliente) {
    echo "<script>alert('Cliente no encontrado o inactivo. ID: $id_cliente'); window.location.href = 'index.php?opc=listar_clientes';</script>";
    exit;
}

// Configuración de la página
$page_title = "Editar Cliente";
$page_subtitle = "Actualiza la información del cliente";
$page_icon = "fas fa-edit";

// Breadcrumbs
$breadcrumbs = [
    ['title' => 'Clientes', 'url' => 'index.php?opc=listar_clientes'],
    ['title' => 'Editar Cliente', 'active' => true]
];

// Acciones del header
$header_actions = [
    [
        'title' => 'Ver Clientes',
        'url' => 'index.php?opc=listar_clientes',
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
                Editar Cliente
            </h3>
            <p class="text-muted mb-0">
                Cliente ID: <strong><?php echo htmlspecialchars($cliente['id_usuario']); ?></strong>
            </p>
        </div>
        <div class="action-buttons">
            <a href="index.php?opc=listar_clientes" class="btn-action btn-secondary-custom">
                <i class="fas fa-arrow-left"></i>
                Volver
            </a>
        </div>
    </div>

    <form action="./model/M_EditarCliente.php" method="POST" class="form-modern">
        <input type="hidden" name="id_usuario" value="<?php echo $cliente['id_usuario']; ?>">
        
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="form-group">
                    <label for="nombre" class="form-label">
                        <i class="fas fa-user text-primary"></i>
                        Nombre Completo
                    </label>
                    <input type="text" 
                           id="nombre" 
                           name="nombre" 
                           class="form-control" 
                           value="<?php echo htmlspecialchars($cliente['nombre']); ?>" 
                           required
                           placeholder="Ej: Juan Pérez García"
                           maxlength="100">
                </div>

                <div class="form-group">
                    <label for="correo" class="form-label">
                        <i class="fas fa-envelope text-info"></i>
                        Correo Electrónico
                    </label>
                    <input type="email" 
                           id="correo" 
                           name="correo" 
                           class="form-control" 
                           value="<?php echo htmlspecialchars($cliente['correo']); ?>" 
                           required
                           placeholder="ejemplo@correo.com"
                           maxlength="100">
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="cedula" class="form-label">
                                <i class="fas fa-id-card text-warning"></i>
                                Cédula
                            </label>
                            <input type="text" 
                                   id="cedula" 
                                   name="cedula" 
                                   class="form-control" 
                                   value="<?php echo htmlspecialchars($cliente['cedula']); ?>" 
                                   required
                                   placeholder="1234567890"
                                   maxlength="20"
                                   pattern="[0-9]+"
                                   title="Solo números">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="clave" class="form-label">
                                <i class="fas fa-lock text-danger"></i>
                                Nueva Contraseña
                            </label>
                            <input type="password" 
                                   id="clave" 
                                   name="clave" 
                                   class="form-control" 
                                   placeholder="Dejar vacío para mantener actual"
                                   minlength="6">
                            <small class="form-text text-muted">
                                Dejar vacío para mantener la contraseña actual. Mínimo 6 caracteres si desea cambiar.
                            </small>
                        </div>
                    </div>
                </div>

                <div class="form-actions text-center mt-4">
                    <button type="submit" class="btn-action btn-success-custom me-3">
                        <i class="fas fa-save"></i>
                        Actualizar Cliente
                    </button>
                    <a href="index.php?opc=listar_clientes" class="btn-action btn-secondary-custom">
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
    const correo = document.getElementById('correo').value.trim();
    const cedula = document.getElementById('cedula').value.trim();
    const clave = document.getElementById('clave').value;
    
    if (nombre.length < 2) {
        e.preventDefault();
        alert('El nombre debe tener al menos 2 caracteres');
        return false;
    }
    
    if (cedula.length < 8) {
        e.preventDefault();
        alert('La cédula debe tener al menos 8 dígitos');
        return false;
    }
    
    if (clave && clave.length < 6) {
        e.preventDefault();
        alert('La nueva contraseña debe tener al menos 6 caracteres');
        return false;
    }
});

// Validar solo números en cédula
document.getElementById('cedula').addEventListener('input', function(e) {
    this.value = this.value.replace(/[^0-9]/g, '');
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
