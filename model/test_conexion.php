<?php
header('Content-Type: application/json');

try {
    require_once '../config/Cconexion.php';
    
    if (!$conexion) {
        throw new Exception("Error de conexión: " . mysqli_connect_error());
    }
    
    // Probar una consulta simple
    $result = mysqli_query($conexion, "SELECT 1 as test");
    
    if (!$result) {
        throw new Exception("Error en consulta de prueba: " . mysqli_error($conexion));
    }
    
    echo json_encode([
        'success' => true,
        'message' => 'Conexión exitosa',
        'server_info' => mysqli_get_server_info($conexion)
    ]);
    
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
?>
