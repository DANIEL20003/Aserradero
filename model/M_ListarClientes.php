<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Iniciar sesión si no está iniciada
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include('./config/Cconexion.php');

// Consulta para obtener clientes/usuarios activos
$sql_consulta = "SELECT * FROM Usuarios WHERE activo = 1";
$result = mysqli_query($conexion, $sql_consulta);

if($result) {
    $clientes = mysqli_fetch_all($result, MYSQLI_ASSOC);
    
    // Convertir encoding si es necesario
    if (!empty($clientes)) {
        foreach ($clientes as &$cliente) {
            foreach ($cliente as $key => &$value) {
                if (is_string($value)) {
                    $value = mb_convert_encoding($value, 'UTF-8', 'auto');
                }
            }
        }
    }
    
    // Guardar clientes en sesión
    $_SESSION['clientes'] = $clientes;
    
} else {
    echo "<script>alert('Error al obtener la lista de clientes: " . mysqli_error($conexion) . "'); 
            window.location.href = '../index.php?opc=dashboard';</script>";
    exit;
}

?>
