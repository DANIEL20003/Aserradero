<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include('../config/Cconexion.php');

// Verificar que se recibieron todos los datos necesarios
if (!isset($_POST['id_proveedor']) || !isset($_POST['cantidades'])) {
    echo "<script>alert('Faltan datos requeridos.'); 
            window.location.href = '../index.php?opc=agregar_compra';</script>";
    exit;
}

$id_proveedor = intval($_POST['id_proveedor']);
$cantidades = $_POST['cantidades'];

// Validar que hay al menos un producto con cantidad > 0
$tiene_productos = false;
foreach ($cantidades as $id_producto => $cantidad) {
    if (intval($cantidad) > 0) {
        $tiene_productos = true;
        break;
    }
}

if (!$tiene_productos) {
    echo "<script>alert('Debe agregar al menos un producto a la compra.'); 
            window.location.href = '../index.php?opc=agregar_compra';</script>";
    exit;
}

// Iniciar transacción
mysqli_autocommit($conexion, FALSE);

try {
    // Actualizar stock de cada producto
    foreach ($cantidades as $id_producto => $cantidad) {
        $cantidad = intval($cantidad);
        if ($cantidad > 0) {
            $id_producto = intval($id_producto);
            
            // Actualizar stock del producto
            $sql_update_stock = "UPDATE Productos 
                                SET stock = stock + $cantidad 
                                WHERE id_producto = $id_producto";
            
            $resultado = mysqli_query($conexion, $sql_update_stock);
            
            if (!$resultado) {
                throw new Exception("Error al actualizar stock del producto ID: $id_producto");
            }
        }
    }
    
    // Si todo salió bien, confirmar transacción
    mysqli_commit($conexion);
    
    echo "<script>
            alert('Compra registrada exitosamente. Stock actualizado.'); 
            window.location.href = '../index.php?opc=listar_productos';
          </script>";

} catch (Exception $e) {
    // Si hay error, revertir transacción
    mysqli_rollback($conexion);
    
    echo "<script>
            alert('Error al procesar la compra: " . $e->getMessage() . "'); 
            window.location.href = '../index.php?opc=agregar_compra';
          </script>";
}

// Restaurar autocommit
mysqli_autocommit($conexion, TRUE);

?>
