<?php
// Test simple para verificar que PHP funciona
echo "PHP está funcionando correctamente<br>";
echo "Fecha y hora: " . date('Y-m-d H:i:s') . "<br>";

// Verificar si las sesiones funcionan
session_start();
echo "Sesiones funcionan<br>";

// Verificar si los archivos de configuración existen
if (file_exists('./config/Cconexion.php')) {
    echo "Archivo de conexión existe<br>";
} else {
    echo "ERROR: Archivo de conexión NO existe<br>";
}

if (file_exists('./config/clavebasededatos.php')) {
    echo "Archivo de claves existe<br>";
} else {
    echo "ERROR: Archivo de claves NO existe<br>";
}

if (file_exists('./fpdf182/fpdf.php')) {
    echo "FPDF existe<br>";
} else {
    echo "ERROR: FPDF NO existe<br>";
}

// Verificar parámetros GET
if (isset($_GET['pedido_id'])) {
    echo "Pedido ID recibido: " . $_GET['pedido_id'] . "<br>";
} else {
    echo "ERROR: No se recibió pedido_id<br>";
}

echo "Test completado";
?>
