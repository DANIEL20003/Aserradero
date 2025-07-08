<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Iniciar sesión si no está iniciada
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include('./config/Cconexion.php');

// Consulta para obtener productos con información de categorías y proveedores
$sql_consulta = "SELECT p.id_producto, p.nombre, p.descripcion, p.imagen_url, p.precio, p.stock, 
                         p.id_categoria, p.id_proveedor,
                         COALESCE(c.descripcion, 'Sin categoría') as categoria_nombre,
                         COALESCE(pr.descripcion, 'Sin proveedor') as proveedor_nombre
                  FROM Productos p 
                  LEFT JOIN Categorias c ON p.id_categoria = c.id_categoria 
                  LEFT JOIN Proveedores pr ON p.id_proveedor = pr.id_proveedor 
                  WHERE p.activo = 1
                  ORDER BY p.nombre";

$result = mysqli_query($conexion, $sql_consulta);

if($result) {
    $productos = mysqli_fetch_all($result, MYSQLI_ASSOC);
    
    // Convertir encoding si es necesario (alternativa moderna a utf8_encode)
    if (!empty($productos)) {
        foreach ($productos as &$producto) {
            foreach ($producto as $key => &$value) {
                if (is_string($value) && $value !== null) {
                    // Asegurar UTF-8 sin usar función deprecada
                    $value = mb_convert_encoding($value, 'UTF-8', 'auto');
                }
            }
        }
    }
    
    // Guardar productos en sesión
    $_SESSION['productos'] = $productos;
    
} else {
    echo "<script>alert('Error al obtener la lista de productos: " . mysqli_error($conexion) . "'); 
            window.location.href = '../index.php?opc=dashboard';</script>";
    exit;
}

?>