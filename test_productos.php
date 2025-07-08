<?php
// Archivo de prueba para verificar productos en la base de datos
require_once '../config/Cconexion.php';

echo "<h2>Prueba de conexión y productos</h2>";

// Verificar conexión
if (!$conexion) {
    echo "<p style='color: red;'>Error de conexión: " . mysqli_connect_error() . "</p>";
    exit;
} else {
    echo "<p style='color: green;'>✓ Conexión a la base de datos exitosa</p>";
}

// Verificar si existe la tabla Productos
$check_table = "SHOW TABLES LIKE 'Productos'";
$result = mysqli_query($conexion, $check_table);

if (mysqli_num_rows($result) == 0) {
    echo "<p style='color: red;'>✗ La tabla 'Productos' no existe</p>";
    exit;
} else {
    echo "<p style='color: green;'>✓ La tabla 'Productos' existe</p>";
}

// Contar productos
$count_sql = "SELECT COUNT(*) as total FROM Productos";
$count_result = mysqli_query($conexion, $count_sql);
$count_row = mysqli_fetch_assoc($count_result);
echo "<p>Total de productos en la base de datos: <strong>" . $count_row['total'] . "</strong></p>";

// Contar productos activos
$count_active_sql = "SELECT COUNT(*) as total FROM Productos WHERE activo = 1";
$count_active_result = mysqli_query($conexion, $count_active_sql);
$count_active_row = mysqli_fetch_assoc($count_active_result);
echo "<p>Productos activos: <strong>" . $count_active_row['total'] . "</strong></p>";

// Mostrar algunos productos de ejemplo
$sample_sql = "SELECT id_producto, nombre, precio_venta, stock, activo FROM Productos LIMIT 5";
$sample_result = mysqli_query($conexion, $sample_sql);

echo "<h3>Productos de ejemplo:</h3>";
echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
echo "<tr><th>ID</th><th>Nombre</th><th>Precio</th><th>Stock</th><th>Activo</th></tr>";

while ($row = mysqli_fetch_assoc($sample_result)) {
    echo "<tr>";
    echo "<td>" . $row['id_producto'] . "</td>";
    echo "<td>" . $row['nombre'] . "</td>";
    echo "<td>$" . $row['precio_venta'] . "</td>";
    echo "<td>" . $row['stock'] . "</td>";
    echo "<td>" . ($row['activo'] ? 'Sí' : 'No') . "</td>";
    echo "</tr>";
}
echo "</table>";

// Verificar si existe la tabla Categorias
$check_categories = "SHOW TABLES LIKE 'Categorias'";
$cat_result = mysqli_query($conexion, $check_categories);

if (mysqli_num_rows($cat_result) == 0) {
    echo "<p style='color: red;'>✗ La tabla 'Categorias' no existe</p>";
} else {
    echo "<p style='color: green;'>✓ La tabla 'Categorias' existe</p>";
    
    // Contar categorías activas
    $count_cat_sql = "SELECT COUNT(*) as total FROM Categorias WHERE activo = 1";
    $count_cat_result = mysqli_query($conexion, $count_cat_sql);
    $count_cat_row = mysqli_fetch_assoc($count_cat_result);
    echo "<p>Categorías activas: <strong>" . $count_cat_row['total'] . "</strong></p>";
}

mysqli_close($conexion);
?>
