<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');
header('Access-Control-Allow-Headers: Content-Type');

require_once '../config/Cconexion.php';

try {
    // Verificar conexión
    if (!$conexion) {
        throw new Exception("Error de conexión: " . mysqli_connect_error());
    }
    
    // Consulta basada en M_ListarCategorias.php
    $sql = "SELECT * FROM Categorias WHERE activo = 1 ORDER BY descripcion ASC";
    
    $resultado = mysqli_query($conexion, $sql);
    
    if (!$resultado) {
        throw new Exception("Error en la consulta: " . mysqli_error($conexion));
    }
    
    $categorias = [];
    while ($fila = mysqli_fetch_assoc($resultado)) {
        // Convertir encoding si es necesario (como en M_ListarCategorias.php)
        foreach ($fila as $key => &$value) {
            if (is_string($value) && $value !== null) {
                $value = mb_convert_encoding($value, 'UTF-8', 'auto');
            }
        }
        
        $categorias[] = [
            'id_categoria' => (int)$fila['id_categoria'],
            'nombre' => $fila['descripcion'], // El modelo original usa 'descripcion' como nombre
            'descripcion' => $fila['descripcion']
        ];
    }
    
    echo json_encode([
        'success' => true,
        'categories' => $categorias,
        'count' => count($categorias)
    ]);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage(),
        'categories' => []
    ]);
}

if (isset($conexion)) {
    mysqli_close($conexion);
}
?>
