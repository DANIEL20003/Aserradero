<?php
// Configuración de la página
$page_title = "Agregar Cliente";
$page_subtitle = "Registra un nuevo cliente en el sistema";
$page_icon = "fas fa-plus";

// Breadcrumbs
$breadcrumbs = [
    ['title' => 'Clientes', 'url' => 'index.php?opc=listar_clientes'],
    ['title' => 'Agregar Cliente', 'active' => true]
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
                <i class="fas fa-plus text-success"></i>
                Nuevo Cliente
            </h3>
            <p class="text-muted mb-0">
                Completa la información para registrar un nuevo cliente
            </p>
        </div>
        <div class="action-buttons">
            <a href="index.php?opc=listar_clientes" class="btn-action btn-secondary-custom">
                <i class="fas fa-arrow-left"></i>
                Volver
            </a>
        </div>
    </div>

    <form action="./model/M_AgregarCliente.php" method="POST" class="form-modern">
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
                                Contraseña
                            </label>
                            <input type="password" 
                                   id="clave" 
                                   name="clave" 
                                   class="form-control" 
                                   required
                                   placeholder="Mínimo 6 caracteres"
                                   minlength="6">
                            <small class="form-text text-muted">
                                Mínimo 6 caracteres
                            </small>
                        </div>
                    </div>
                </div>

                <div class="form-actions text-center mt-4">
                    <button type="submit" class="btn-action btn-success-custom me-3">
                        <i class="fas fa-save"></i>
                        Agregar Cliente
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
    
    if (clave.length < 6) {
        e.preventDefault();
        alert('La contraseña debe tener al menos 6 caracteres');
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
});
</script>

<?php include './view/V_Footer.php'; ?>
</body>
</html>