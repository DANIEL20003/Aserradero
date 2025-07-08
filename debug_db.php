<?php
// Script para debuggear la base de datos
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h2>Debug de Base de Datos - Aserradero</h2>";

try {
    require_once 'config/Cconexion.php';
    
    echo "<p><strong>Conexión:</strong> ";
    if ($conexion) {
        echo "✅ Exitosa</p>";
        
        // Verificar qué tablas existen
        echo "<h3>Tablas en la base de datos:</h3>";
        $result = mysqli_query($conexion, "SHOW TABLES");
        $tables = [];
        while ($row = mysqli_fetch_array($result)) {
            $tables[] = $row[0];
            echo "- " . $row[0] . "<br>";
        }
        
        // Verificar si existe la tabla Productos
        if (in_array('Productos', $tables)) {
            echo "<h3>Estructura de la tabla Productos:</h3>";
            $result = mysqli_query($conexion, "DESCRIBE Productos");
            while ($row = mysqli_fetch_assoc($result)) {
                echo "- {$row['Field']} ({$row['Type']}) - {$row['Null']} - {$row['Key']}<br>";
            }
            
            echo "<h3>Cantidad de productos:</h3>";
            $result = mysqli_query($conexion, "SELECT COUNT(*) as total FROM Productos");
            $count = mysqli_fetch_assoc($result);
            echo "Total de productos: " . $count['total'] . "<br>";
            
            $result = mysqli_query($conexion, "SELECT COUNT(*) as activos FROM Productos WHERE activo = 1");
            $count_activos = mysqli_fetch_assoc($result);
            echo "Productos activos: " . $count_activos['activos'] . "<br>";
            
            // Mostrar algunos productos de ejemplo
            echo "<h3>Primeros 5 productos:</h3>";
            $result = mysqli_query($conexion, "SELECT * FROM Productos LIMIT 5");
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<div style='border: 1px solid #ccc; margin: 5px; padding: 10px;'>";
                echo "<strong>ID:</strong> {$row['id_producto']}<br>";
                echo "<strong>Nombre:</strong> {$row['nombre']}<br>";
                echo "<strong>Precio:</strong> {$row['precio_venta']}<br>";
                echo "<strong>Stock:</strong> {$row['stock']}<br>";
                echo "<strong>Activo:</strong> " . ($row['activo'] ? 'Sí' : 'No') . "<br>";
                echo "</div>";
            }
        } else {
            echo "<p><strong>⚠️ La tabla 'Productos' NO existe</strong></p>";
        }
        
        // Verificar si existe la tabla Categorias
        if (in_array('Categorias', $tables)) {
            echo "<h3>Categorías disponibles:</h3>";
            $result = mysqli_query($conexion, "SELECT * FROM Categorias");
            while ($row = mysqli_fetch_assoc($result)) {
                echo "- ID: {$row['id_categoria']}, Nombre: {$row['nombre']}<br>";
            }
        } else {
            echo "<p><strong>⚠️ La tabla 'Categorias' NO existe</strong></p>";
        }
        
    } else {
        echo "❌ Error: " . mysqli_connect_error() . "</p>";
    }
    
} catch (Exception $e) {
    echo "<p><strong>Error:</strong> " . $e->getMessage() . "</p>";
}
?>
