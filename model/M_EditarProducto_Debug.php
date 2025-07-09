<?php
// Script de debug para ver qué datos llegan al modelo
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

echo "<h2>Debug - Datos recibidos por M_EditarProducto.php</h2>";

echo "<h3>Datos POST:</h3>";
echo "<pre>";
print_r($_POST);
echo "</pre>";

echo "<h3>Archivos FILES:</h3>";
echo "<pre>";
print_r($_FILES);
echo "</pre>";

echo "<h3>Verificar conexión a BD:</h3>";
include('../config/Cconexion.php');

if ($conexion) {
    echo "<p>✅ Conexión exitosa</p>";
    
    if (isset($_POST['id_producto'])) {
        $id = intval($_POST['id_producto']);
        echo "<h3>Datos actuales del producto ID: $id</h3>";
        
        $sql = "SELECT * FROM Productos WHERE id_producto = $id";
        $result = mysqli_query($conexion, $sql);
        
        if ($result && mysqli_num_rows($result) > 0) {
            $producto = mysqli_fetch_assoc($result);
            echo "<pre>";
            print_r($producto);
            echo "</pre>";
        } else {
            echo "<p>❌ No se encontró el producto</p>";
        }
    }
} else {
    echo "<p>❌ Error de conexión: " . mysqli_connect_error() . "</p>";
}
?>
