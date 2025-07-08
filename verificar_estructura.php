<?php
// Script para verificar estructura de tablas
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h2>Verificar Estructura de Tablas</h2>";

try {
    require_once 'config/Cconexion.php';
    
    if (!$conexion) {
        throw new Exception("Error de conexión: " . mysqli_connect_error());
    }
    
    // Verificar estructura de tabla Productos
    echo "<h3>Estructura de tabla Productos:</h3>";
    $result = mysqli_query($conexion, "DESCRIBE Productos");
    if ($result) {
        echo "<table border='1' style='border-collapse: collapse;'>";
        echo "<tr><th>Campo</th><th>Tipo</th><th>Null</th><th>Key</th><th>Default</th></tr>";
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";
            echo "<td>{$row['Field']}</td>";
            echo "<td>{$row['Type']}</td>";
            echo "<td>{$row['Null']}</td>";
            echo "<td>{$row['Key']}</td>";
            echo "<td>{$row['Default']}</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<p>❌ Error o tabla no existe: " . mysqli_error($conexion) . "</p>";
    }
    
    // Verificar estructura de tabla Categorias
    echo "<h3>Estructura de tabla Categorias:</h3>";
    $result = mysqli_query($conexion, "DESCRIBE Categorias");
    if ($result) {
        echo "<table border='1' style='border-collapse: collapse;'>";
        echo "<tr><th>Campo</th><th>Tipo</th><th>Null</th><th>Key</th><th>Default</th></tr>";
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";
            echo "<td>{$row['Field']}</td>";
            echo "<td>{$row['Type']}</td>";
            echo "<td>{$row['Null']}</td>";
            echo "<td>{$row['Key']}</td>";
            echo "<td>{$row['Default']}</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<p>❌ Error o tabla no existe: " . mysqli_error($conexion) . "</p>";
    }
    
    // Verificar estructura de tabla Proveedores
    echo "<h3>Estructura de tabla Proveedores:</h3>";
    $result = mysqli_query($conexion, "DESCRIBE Proveedores");
    if ($result) {
        echo "<table border='1' style='border-collapse: collapse;'>";
        echo "<tr><th>Campo</th><th>Tipo</th><th>Null</th><th>Key</th><th>Default</th></tr>";
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";
            echo "<td>{$row['Field']}</td>";
            echo "<td>{$row['Type']}</td>";
            echo "<td>{$row['Null']}</td>";
            echo "<td>{$row['Key']}</td>";
            echo "<td>{$row['Default']}</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<p>❌ Error o tabla no existe: " . mysqli_error($conexion) . "</p>";
    }
    
} catch (Exception $e) {
    echo "<p><strong>❌ Error:</strong> " . $e->getMessage() . "</p>";
}

if (isset($conexion) && $conexion) {
    mysqli_close($conexion);
}
?>
