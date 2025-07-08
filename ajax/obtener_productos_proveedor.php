<?php
header('Content-Type: application/json');

include('../config/Cconexion.php');

if (!isset($_GET['id_proveedor']) || empty($_GET['id_proveedor'])) {
    echo json_encode([]);
    exit;
}

$id_proveedor = intval($_GET['id_proveedor']);

// Obtener productos del proveedor
$sql = "SELECT id_producto, nombre, stock, precio 
        FROM Productos 
        WHERE id_proveedor = $id_proveedor AND activo = 1 
        ORDER BY nombre";

$resultado = mysqli_query($conexion, $sql);

if ($resultado) {
    $productos = mysqli_fetch_all($resultado, MYSQLI_ASSOC);
    echo json_encode($productos);
} else {
    echo json_encode([]);
}
?>
