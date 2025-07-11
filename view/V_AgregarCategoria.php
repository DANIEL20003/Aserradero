<?php
// Configuración de la página
$page_title = "Agregar Categoría";
$page_subtitle = "Crea una nueva categoría para productos";
$page_icon = "fas fa-plus";

// Breadcrumbs
$breadcrumbs = [
    ['title' => 'Categorías', 'url' => 'index.php?opc=listar_categorias'],
    ['title' => 'Agregar Categoría', 'active' => true]
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
                <i class="fas fa-plus text-success"></i>
                Nueva Categoría
            </h3>
            <p class="text-muted mb-0">
                Completa la información para crear una nueva categoría
            </p>
        </div>
        <div class="action-buttons">
            <a href="index.php?opc=listar_categorias" class="btn-action btn-secondary-custom">
                <i class="fas fa-arrow-left"></i>
                Volver
            </a>
        </div>
    </div>

    <form action="./model/M_AgregarCategoria.php" method="POST" class="form-modern">
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
                        Agregar Categoría
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
});
</script>

<?php include './view/V_Footer.php'; ?>
</body>
</html>
