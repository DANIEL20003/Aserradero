<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include('../config/Cconexion.php');

// Verificar conexión
if (!$conexion) {
    echo "<script>alert('Error de conexión a la base de datos.'); 
            window.location.href = '../index.php?opc=listar_productos';</script>";
    exit;
}

// Verificar que se recibieron todos los datos necesarios
if (!isset($_POST['id_producto']) || !isset($_POST['nombre'])) {
    echo "<script>alert('Faltan datos requeridos.'); 
            window.location.href = '../index.php?opc=listar_productos';</script>";
    exit;
}

// Obtener valores del formulario con validación
$id_producto = intval($_POST['id_producto']);
$nombre = mysqli_real_escape_string($conexion, trim($_POST['nombre']));
$descripcion = isset($_POST['descripcion']) ? mysqli_real_escape_string($conexion, trim($_POST['descripcion'])) : '';
$precio = isset($_POST['precio']) ? floatval($_POST['precio']) : 0;
$stock = isset($_POST['stock']) ? intval($_POST['stock']) : 0;
$id_categoria = isset($_POST['id_categoria']) && $_POST['id_categoria'] != '' ? intval($_POST['id_categoria']) : null;
$id_proveedor = isset($_POST['id_proveedor']) && $_POST['id_proveedor'] != '' ? intval($_POST['id_proveedor']) : null;

// Validaciones básicas
if (empty($nombre)) {
    echo "<script>alert('El nombre del producto es requerido.'); 
            window.location.href = '../index.php?opc=editar_producto&id=$id_producto';</script>";
    exit;
}

if ($precio < 0) {
    echo "<script>alert('El precio no puede ser negativo.'); 
            window.location.href = '../index.php?opc=editar_producto&id=$id_producto';</script>";
    exit;
}

if ($stock < 0) {
    echo "<script>alert('El stock no puede ser negativo.'); 
            window.location.href = '../index.php?opc=editar_producto&id=$id_producto';</script>";
    exit;
}

// Verificar que el producto existe
$sql_verificar = "SELECT id_producto, imagen_url FROM Productos WHERE id_producto = ? AND activo = 1";
$stmt_verificar = mysqli_prepare($conexion, $sql_verificar);
mysqli_stmt_bind_param($stmt_verificar, "i", $id_producto);
mysqli_stmt_execute($stmt_verificar);
$resultado_verificar = mysqli_stmt_get_result($stmt_verificar);

if (mysqli_num_rows($resultado_verificar) == 0) {
    echo "<script>alert('El producto no existe o ha sido eliminado.'); 
            window.location.href = '../index.php?opc=listar_productos';</script>";
    exit;
}

$producto_actual = mysqli_fetch_assoc($resultado_verificar);
$imagen_url = $producto_actual['imagen_url']; // Mantener la imagen actual por defecto

// Manejar subida de nueva imagen (opcional)
if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
    // Verificar que es una imagen válida
    $tipos_permitidos = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    $tipo_archivo = $_FILES['imagen']['type'];
    
    if (!in_array($tipo_archivo, $tipos_permitidos)) {
        echo "<script>alert('Tipo de archivo no permitido. Use JPG, PNG, GIF o WEBP.'); 
                window.location.href = '../index.php?opc=editar_producto&id=$id_producto';</script>";
        exit;
    }
    
    // Verificar tamaño (máximo 10MB)
    if ($_FILES['imagen']['size'] > 10 * 1024 * 1024) {
        echo "<script>alert('El archivo es demasiado grande. Máximo 10MB.'); 
                window.location.href = '../index.php?opc=editar_producto&id=$id_producto';</script>";
        exit;
    }
    
    // Si tiene helper de ImgBB, usarlo
    if (file_exists('../config/imgbb_helper.php')) {
        include('../config/imgbb_helper.php');
        $resultado_subida = subirImagenImgBB($_FILES['imagen']['tmp_name'], $_FILES['imagen']['name']);
        
        if ($resultado_subida['success']) {
            $imagen_url = $resultado_subida['url'];
        } else {
            echo "<script>alert('Error al subir imagen: " . addslashes($resultado_subida['error']) . "'); 
                    window.location.href = '../index.php?opc=editar_producto&id=$id_producto';</script>";
            exit;
        }
    } else {
        // Fallback: mantener la imagen actual si no hay helper
        echo "<script>alert('Sistema de imágenes no configurado. Manteniendo imagen actual.'); 
                window.location.href = '../index.php?opc=editar_producto&id=$id_producto';</script>";
        exit;
    }
} elseif (isset($_FILES['imagen']) && $_FILES['imagen']['error'] !== UPLOAD_ERR_NO_FILE) {
    // Hubo un error en la subida
    $errores_upload = [
        UPLOAD_ERR_INI_SIZE => 'El archivo es demasiado grande',
        UPLOAD_ERR_FORM_SIZE => 'El archivo excede el tamaño permitido',
        UPLOAD_ERR_PARTIAL => 'El archivo se subió parcialmente',
        UPLOAD_ERR_NO_TMP_DIR => 'Falta directorio temporal',
        UPLOAD_ERR_CANT_WRITE => 'Error de escritura',
        UPLOAD_ERR_EXTENSION => 'Extensión no permitida'
    ];
    
    $mensaje_error = isset($errores_upload[$_FILES['imagen']['error']]) 
                    ? $errores_upload[$_FILES['imagen']['error']] 
                    : 'Error desconocido';
    
    echo "<script>alert('Error al subir archivo: $mensaje_error'); 
            window.location.href = '../index.php?opc=editar_producto&id=$id_producto';</script>";
    exit;
}

// Actualizar el producto usando prepared statements
$sql_update = "UPDATE Productos SET 
               nombre = ?, 
               descripcion = ?, 
               imagen_url = ?, 
               precio = ?, 
               stock = ?, 
               id_categoria = ?, 
               id_proveedor = ? 
               WHERE id_producto = ? AND activo = 1";

$stmt_update = mysqli_prepare($conexion, $sql_update);

if (!$stmt_update) {
    echo "<script>alert('Error en la preparación de la consulta: " . mysqli_error($conexion) . "'); 
            window.location.href = '../index.php?opc=editar_producto&id=$id_producto';</script>";
    exit;
}

mysqli_stmt_bind_param($stmt_update, "sssdiiis", 
    $nombre, 
    $descripcion, 
    $imagen_url, 
    $precio, 
    $stock, 
    $id_categoria, 
    $id_proveedor, 
    $id_producto
);

$resultado = mysqli_stmt_execute($stmt_update);

if ($resultado && mysqli_stmt_affected_rows($stmt_update) > 0) {
    // Éxito - redirigir
    echo "<script>
            alert('Producto actualizado correctamente.');
            window.location.href = '../index.php?opc=listar_productos';
          </script>";
} else if ($resultado && mysqli_stmt_affected_rows($stmt_update) == 0) {
    // No hubo cambios
    echo "<script>
            alert('No se realizaron cambios en el producto.');
            window.location.href = '../index.php?opc=listar_productos';
          </script>";
} else {
    $error_msg = mysqli_error($conexion);
    echo "<script>
            alert('No se pudo actualizar el producto. Error: " . addslashes($error_msg) . "'); 
            window.location.href = '../index.php?opc=editar_producto&id=$id_producto';
          </script>";
}

mysqli_stmt_close($stmt_update);
mysqli_close($conexion);
?>
