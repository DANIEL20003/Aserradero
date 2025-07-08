<?php
// Configuración de la página
$page_title = "Agregar Producto";
$page_subtitle = "Añade un nuevo producto a tu inventario";
$page_icon = "fas fa-plus-circle";

// Breadcrumbs
$breadcrumbs = [
    ['title' => 'Productos', 'url' => 'index.php?opc=listar_productos'],
    ['title' => 'Agregar Producto', 'active' => true]
];

// Acciones del header
$header_actions = [
    [
        'title' => 'Ver Productos',
        'url' => 'index.php?opc=listar_productos',
        'icon' => 'fas fa-list',
        'class' => 'btn-secondary-custom'
    ]
];

// Incluir header
include './view/V_Header_Admin.php';

// Incluir conexión para obtener categorías y proveedores
include_once './config/Cconexion.php';

// Obtener categorías activas para el select
$sql_categorias = "SELECT * FROM Categorias WHERE activo = 1";
$resultado_categorias = mysqli_query($conexion, $sql_categorias);
$categorias = mysqli_fetch_all($resultado_categorias, MYSQLI_ASSOC);

// Obtener proveedores activos para el select
$sql_proveedores = "SELECT * FROM Proveedores WHERE activo = 1";
$resultado_proveedores = mysqli_query($conexion, $sql_proveedores);
$proveedores = mysqli_fetch_all($resultado_proveedores, MYSQLI_ASSOC);
?>

<div class="form-container">
    <form action="./model/M_AgregarProducto.php" method="POST" enctype="multipart/form-data" id="formAgregarProducto">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="nombre" class="form-label">
                        <i class="fas fa-tag text-primary"></i>
                        Nombre del Producto *
                    </label>
                    <input type="text" 
                           class="form-control" 
                           id="nombre" 
                           name="nombre" 
                           required 
                           placeholder="Ej: Tablón de Cedro"
                           maxlength="100">
                    <div class="form-text">Máximo 100 caracteres</div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label for="precio" class="form-label">
                        <i class="fas fa-dollar-sign text-success"></i>
                        Precio *
                    </label>
                    <div class="input-group">
                        <span class="input-group-text">$</span>
                        <input type="number" 
                               class="form-control" 
                               id="precio" 
                               name="precio" 
                               step="0.01" 
                               min="0"
                               required 
                               placeholder="0.00">
                    </div>
                </div>
            </div>
        </div>

        <div class="form-group">
            <label for="descripcion" class="form-label">
                <i class="fas fa-align-left text-info"></i>
                Descripción *
            </label>
            <textarea class="form-control" 
                      id="descripcion" 
                      name="descripcion" 
                      rows="4" 
                      required 
                      placeholder="Describe las características del producto..."
                      maxlength="500"></textarea>
            <div class="form-text">Máximo 500 caracteres</div>
        </div>

        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label for="stock" class="form-label">
                        <i class="fas fa-boxes text-warning"></i>
                        Stock Inicial *
                    </label>
                    <input type="number" 
                           class="form-control" 
                           id="stock" 
                           name="stock" 
                           min="0"
                           required 
                           placeholder="0"
                           value="0">
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group">
                    <label for="id_categoria" class="form-label">
                        <i class="fas fa-folder text-primary"></i>
                        Categoría *
                    </label>
                    <select class="form-select" id="id_categoria" name="id_categoria" required>
                        <option value="">Selecciona una categoría</option>
                        <?php foreach ($categorias as $categoria): ?>
                            <option value="<?php echo htmlspecialchars($categoria['id_categoria']); ?>">
                                <?php echo htmlspecialchars($categoria['descripcion']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <?php if (empty($categorias)): ?>
                        <div class="form-text text-warning">
                            <i class="fas fa-exclamation-triangle"></i>
                            No hay categorías disponibles. 
                            <a href="index.php?opc=agregar_categoria">Crear una nueva</a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group">
                    <label for="id_proveedor" class="form-label">
                        <i class="fas fa-truck text-secondary"></i>
                        Proveedor *
                    </label>
                    <select class="form-select" id="id_proveedor" name="id_proveedor" required>
                        <option value="">Selecciona un proveedor</option>
                        <?php foreach ($proveedores as $proveedor): ?>
                            <option value="<?php echo htmlspecialchars($proveedor['id_proveedor']); ?>">
                                <?php echo htmlspecialchars($proveedor['descripcion']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <?php if (empty($proveedores)): ?>
                        <div class="form-text text-warning">
                            <i class="fas fa-exclamation-triangle"></i>
                            No hay proveedores disponibles. 
                            <a href="index.php?opc=agregar_proveedor">Crear uno nuevo</a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="form-group">
            <label for="imagen" class="form-label">
                <i class="fas fa-image text-info"></i>
                Imagen del Producto
            </label>
            <input type="file" 
                   class="form-control" 
                   id="imagen" 
                   name="imagen" 
                   accept="image/jpeg,image/png,image/gif,image/webp">
            <div class="form-text">
                <i class="fas fa-info-circle text-primary"></i>
                Formatos permitidos: JPG, PNG, GIF, WEBP. Tamaño máximo: 10MB
            </div>
            
            <!-- Preview de imagen -->
            <div id="imagePreview" class="mt-3" style="display: none;">
                <label class="form-label">Vista previa:</label>
                <div class="border rounded p-2" style="max-width: 200px;">
                    <img id="previewImg" src="" alt="Vista previa" class="img-fluid rounded">
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top">
            <div class="text-muted">
                <i class="fas fa-info-circle"></i>
                Los campos marcados con * son obligatorios
            </div>
            <div class="btn-group">
                <a href="index.php?opc=listar_productos" class="btn-action btn-secondary-custom">
                    <i class="fas fa-times"></i>
                    Cancelar
                </a>
                <button type="submit" class="btn-action btn-success-custom">
                    <i class="fas fa-save"></i>
                    Guardar Producto
                </button>
            </div>
        </div>
    </form>
</div>

        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Preview de imagen
        document.getElementById('imagen').addEventListener('change', function(e) {
            const file = e.target.files[0];
            const preview = document.getElementById('imagePreview');
            const previewImg = document.getElementById('previewImg');
            
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewImg.src = e.target.result;
                    preview.style.display = 'block';
                };
                reader.readAsDataURL(file);
            } else {
                preview.style.display = 'none';
            }
        });

        // Validación del formulario
        document.getElementById('formAgregarProducto').addEventListener('submit', function(e) {
            const nombre = document.getElementById('nombre').value.trim();
            const precio = parseFloat(document.getElementById('precio').value);
            const descripcion = document.getElementById('descripcion').value.trim();
            const categoria = document.getElementById('id_categoria').value;
            const proveedor = document.getElementById('id_proveedor').value;
            
            if (!nombre || !descripcion || !categoria || !proveedor) {
                e.preventDefault();
                alert('Por favor, complete todos los campos obligatorios.');
                return false;
            }
            
            if (precio <= 0) {
                e.preventDefault();
                alert('El precio debe ser mayor a 0.');
                return false;
            }
            
            if (descripcion.length > 500) {
                e.preventDefault();
                alert('La descripción no puede exceder los 500 caracteres.');
                return false;
            }
            
            return true;
        });

        // Contador de caracteres para descripción
        document.getElementById('descripcion').addEventListener('input', function() {
            const maxLength = 500;
            const currentLength = this.value.length;
            const formText = this.nextElementSibling;
            
            formText.textContent = `${currentLength}/${maxLength} caracteres`;
            
            if (currentLength > maxLength * 0.9) {
                formText.classList.add('text-warning');
            } else {
                formText.classList.remove('text-warning');
            }
        });

        // Contador de caracteres para nombre
        document.getElementById('nombre').addEventListener('input', function() {
            const maxLength = 100;
            const currentLength = this.value.length;
            const formText = this.nextElementSibling;
            
            formText.textContent = `${currentLength}/${maxLength} caracteres`;
        });
    </script>

<?php include './view/V_Footer.php'; ?>
</body>
</html>

    <label for="stock">Stock:</label>
    <input type="number" id="stock" name="stock" required>

    <label for="categoria">Categoría:</label>
    <select id="categoria" name="id_categoria" required>
        <option value="">Seleccionar categoría</option>
        <?php foreach ($categorias as $categoria): ?>
            <option value="<?php echo $categoria['id_categoria']; ?>">
                <?php echo htmlspecialchars($categoria['descripcion']); ?>
            </option>
        <?php endforeach; ?>
    </select>

    <label for="proveedor">Proveedor:</label>
    <select id="proveedor" name="id_proveedor" required>
        <option value="">Seleccionar proveedor</option>
        <?php foreach ($proveedores as $proveedor): ?>
            <option value="<?php echo $proveedor['id_proveedor']; ?>">
                <?php echo htmlspecialchars($proveedor['descripcion']); ?>
            </option>
        <?php endforeach; ?>
    </select>

    <input type="submit" value="Agregar Producto">
</form>

<a href="index.php?opc=listar_productos">Volver a la lista de productos</a>