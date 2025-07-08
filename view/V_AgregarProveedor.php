<?php
// Configuración de la página
$page_title = "Agregar Proveedor";
$page_subtitle = "Registra un nuevo proveedor de la empresa";
$page_icon = "fas fa-plus";

// Breadcrumbs
$breadcrumbs = [
    ['title' => 'Proveedores', 'url' => 'index.php?opc=listar_proveedores'],
    ['title' => 'Agregar Proveedor', 'active' => true]
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
                <i class="fas fa-plus text-success"></i>
                Nuevo Proveedor
            </h3>
            <p class="text-muted mb-0">
                Completa la información para registrar un nuevo proveedor
            </p>
        </div>
        <div class="action-buttons">
            <a href="index.php?opc=listar_proveedores" class="btn-action btn-secondary-custom">
                <i class="fas fa-arrow-left"></i>
                Volver
            </a>
        </div>
    </div>

    <form action="./model/M_AgregarProveedor.php" method="POST" class="form-modern">
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
                        Agregar Proveedor
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
});
</script>

<?php include './view/V_Footer.php'; ?>
</body>
</html>
