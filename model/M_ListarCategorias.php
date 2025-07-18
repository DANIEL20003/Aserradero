<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Iniciar sesión si no está iniciada
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include('./config/Cconexion.php');

// Consulta para obtener categorías activas
$sql_consulta = "SELECT * FROM Categorias WHERE activo = 1";
$result = mysqli_query($conexion, $sql_consulta);

if($result) {
    $categorias = mysqli_fetch_all($result, MYSQLI_ASSOC);
    
    // Convertir encoding si es necesario
    if (!empty($categorias)) {
        foreach ($categorias as &$categoria) {
            foreach ($categoria as $key => &$value) {
                if (is_string($value)) {
                    $value = mb_convert_encoding($value, 'UTF-8', 'auto');
                }
            }
        }
    }
    
    // Guardar categorías en sesión
    $_SESSION['categorias'] = $categorias;
    
} else {
    echo "<script>alert('Error al obtener la lista de categorías: " . mysqli_error($conexion) . "'); 
            window.location.href = '../index.php?opc=dashboard';</script>";
    exit;
}

?>