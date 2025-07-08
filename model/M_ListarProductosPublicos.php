<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

// Habilitar reportes de error para debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

try {
    // Verificar si el archivo de configuración existe
    $config_path = '../config/Cconexion.php';
    if (!file_exists($config_path)) {
        throw new Exception("No se encontró el archivo de configuración: $config_path");
    }
    
    require_once $config_path;
    
    // Verificar conexión
    if (!isset($conexion) || !$conexion) {
        throw new Exception("Error de conexión a la base de datos");
    }
    
    // Verificar si las tablas existen
    $check_productos = mysqli_query($conexion, "SHOW TABLES LIKE 'Productos'");
    if (mysqli_num_rows($check_productos) == 0) {
        throw new Exception("La tabla 'Productos' no existe en la base de datos");
    }
    
    // Consulta basada en M_ListarProductos.php
    $sql = "SELECT p.id_producto, p.nombre, p.descripcion, p.imagen_url, p.precio, p.stock, 
                   p.id_categoria, p.id_proveedor,
                   COALESCE(c.descripcion, 'Sin categoría') as categoria_nombre,
                   COALESCE(pr.descripcion, 'Sin proveedor') as proveedor_nombre
            FROM Productos p 
            LEFT JOIN Categorias c ON p.id_categoria = c.id_categoria 
            LEFT JOIN Proveedores pr ON p.id_proveedor = pr.id_proveedor 
            WHERE p.activo = 1
            ORDER BY p.nombre ASC 
            LIMIT 50";
    
    $resultado = mysqli_query($conexion, $sql);
    
    if (!$resultado) {
        throw new Exception("Error en la consulta: " . mysqli_error($conexion));
    }
    
    $productos = [];
    while ($fila = mysqli_fetch_assoc($resultado)) {
        // Convertir encoding si es necesario (como en M_ListarProductos.php)
        foreach ($fila as $key => &$value) {
            if (is_string($value) && $value !== null) {
                $value = mb_convert_encoding($value, 'UTF-8', 'auto');
            }
        }
        
        $productos[] = [
            'id_producto' => (int)$fila['id_producto'],
            'nombre' => $fila['nombre'],
            'descripcion' => $fila['descripcion'] ?? '',
            'precio_venta' => (float)$fila['precio'], // Mapear precio a precio_venta
            'stock' => (int)$fila['stock'],
            'imagen' => $fila['imagen_url'], // Mapear imagen_url a imagen
            'categoria' => $fila['categoria_nombre'],
            'id_categoria' => (int)$fila['id_categoria'],
            'proveedor' => $fila['proveedor_nombre'],
            'id_proveedor' => (int)$fila['id_proveedor']
        ];
    }
    
    $response = [
        'success' => true,
        'products' => $productos,
        'count' => count($productos)
    ];
    
    echo json_encode($response);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage(),
        'products' => [],
        'file' => __FILE__,
        'line' => __LINE__
    ]);
}

if (isset($conexion) && $conexion) {
    mysqli_close($conexion);
}
?>
