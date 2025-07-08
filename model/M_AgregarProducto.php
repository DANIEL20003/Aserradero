<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include('../config/Cconexion.php');
include('../config/imgbb_helper.php');

// Verificar que se recibieron todos los datos necesarios
if (!isset($_POST['nombre']) || !isset($_POST['precio'])) {
    echo "<script>alert('Faltan datos requeridos.'); 
            window.location.href = '../index.php?opc=agregar_producto';</script>";
    exit;
}

// Obtener valores del formulario con validación
$nombre = mysqli_real_escape_string($conexion, $_POST['nombre']);
$descripcion = isset($_POST['descripcion']) ? mysqli_real_escape_string($conexion, $_POST['descripcion']) : '';
$precio = isset($_POST['precio']) ? floatval($_POST['precio']) : 0;
$stock = isset($_POST['stock']) ? intval($_POST['stock']) : 0;
$id_categoria = isset($_POST['id_categoria']) && $_POST['id_categoria'] != '' ? intval($_POST['id_categoria']) : 'NULL';
$id_proveedor = isset($_POST['id_proveedor']) && $_POST['id_proveedor'] != '' ? intval($_POST['id_proveedor']) : 'NULL';

// Manejar subida de imagen
$imagen_url = '';
if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
    $resultado_subida = subirImagenImgBB($_FILES['imagen']['tmp_name'], $_FILES['imagen']['name']);
    
    if ($resultado_subida['success']) {
        $imagen_url = $resultado_subida['url'];
    } else {
        echo "<script>alert('Error al subir imagen: " . $resultado_subida['error'] . "'); 
                window.location.href = '../index.php?opc=agregar_producto';</script>";
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
            window.location.href = '../index.php?opc=agregar_producto';</script>";
    exit;
}

// Escapar la URL de la imagen
$imagen_url = mysqli_real_escape_string($conexion, $imagen_url);

// Insertar producto
$sql_insert_producto = "INSERT INTO Productos (nombre, descripcion, imagen_url, precio, stock, id_categoria, id_proveedor) 
                        VALUES ('$nombre', '$descripcion', '$imagen_url', $precio, $stock, $id_categoria, $id_proveedor)";

$resultado = mysqli_query($conexion, $sql_insert_producto);

if($resultado) {
    $id_producto = mysqli_insert_id($conexion);
    if($id_producto) {
        header("Location: ../index.php?opc=listar_productos");
    } else {
        echo "<script>alert('No se pudo agregar el producto. Error en la inserción.'); 
                window.location.href = '../index.php?opc=agregar_producto';</script>";
    }
} else {
    echo "<script>alert('Error en la consulta SQL: " . mysqli_error($conexion) . "'); 
            window.location.href = '../index.php?opc=agregar_producto';</script>";
}

?>
