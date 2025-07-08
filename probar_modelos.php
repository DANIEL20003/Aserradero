<?php
// Script para probar los modelos de productos y categorías públicos
header('Content-Type: text/html; charset=UTF-8');
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h2>Prueba de Modelos Públicos</h2>";

echo "<h3>1. Probando M_ListarProductosPublicos.php</h3>";
echo "<iframe src='model/M_ListarProductosPublicos.php' width='100%' height='200' style='border: 1px solid #ccc;'></iframe>";

echo "<h3>2. Probando M_ListarCategoriasPublicas.php</h3>";
echo "<iframe src='model/M_ListarCategoriasPublicas.php' width='100%' height='200' style='border: 1px solid #ccc;'></iframe>";

echo "<h3>3. Respuesta JSON de Productos:</h3>";
echo "<div id='productos-json'></div>";

echo "<h3>4. Respuesta JSON de Categorías:</h3>";
echo "<div id='categorias-json'></div>";

?>

<script>
// Prueba con JavaScript para ver las respuestas
async function probarEndpoints() {
    try {
        // Probar productos
        const respProductos = await fetch('./model/M_ListarProductosPublicos.php');
        const dataProductos = await respProductos.json();
        document.getElementById('productos-json').innerHTML = '<pre>' + JSON.stringify(dataProductos, null, 2) + '</pre>';
        
        // Probar categorías
        const respCategorias = await fetch('./model/M_ListarCategoriasPublicas.php');
        const dataCategorias = await respCategorias.json();
        document.getElementById('categorias-json').innerHTML = '<pre>' + JSON.stringify(dataCategorias, null, 2) + '</pre>';
        
    } catch (error) {
        console.error('Error:', error);
        document.getElementById('productos-json').innerHTML = '<p style="color: red;">Error: ' + error.message + '</p>';
        document.getElementById('categorias-json').innerHTML = '<p style="color: red;">Error: ' + error.message + '</p>';
    }
}

// Ejecutar pruebas cuando cargue la página
window.onload = probarEndpoints;
</script>
