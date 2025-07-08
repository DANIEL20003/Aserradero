<?php
header('Content-Type: application/json');

// Activar reporte de errores para debug
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

try {
    include('../config/Cconexion.php');
    
    if (!isset($_GET['id_proveedor']) || empty($_GET['id_proveedor'])) {
        echo json_encode(['error' => false, 'data' => [], 'message' => 'No proveedor specified']);
        exit;
    }
    
    $id_proveedor = intval($_GET['id_proveedor']);
    
    // Verificar conexión
    if (!$conexion) {
        echo json_encode(['error' => true, 'message' => 'Database connection failed']);
        exit;
    }
    
    // Obtener productos del proveedor
    $sql = "SELECT id_producto, nombre, stock, precio 
            FROM Productos 
            WHERE id_proveedor = $id_proveedor AND activo = 1 
            ORDER BY nombre";
    
    $resultado = mysqli_query($conexion, $sql);
    
    if ($resultado) {
        $productos = mysqli_fetch_all($resultado, MYSQLI_ASSOC);
        echo json_encode(['error' => false, 'data' => $productos, 'message' => 'Success']);
    } else {
        echo json_encode(['error' => true, 'message' => 'Query failed: ' . mysqli_error($conexion)]);
    }
    
} catch (Exception $e) {
    echo json_encode(['error' => true, 'message' => 'Exception: ' . $e->getMessage()]);
}
?>
