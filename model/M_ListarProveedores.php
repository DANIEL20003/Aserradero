<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Iniciar sesión si no está iniciada
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include './config/Cconexion.php';

// Consulta para obtener proveedores activos
$sql_consulta = "SELECT * FROM Proveedores WHERE activo = 1";
$result = mysqli_query($conexion, $sql_consulta);

if($result) {
	$proveedores = mysqli_fetch_all($result, MYSQLI_ASSOC);
	// Convertir todos los valores de los proveedores a UTF-8
	foreach ($proveedores as &$proveedor) {
		foreach ($proveedor as $key => $value) {
			if (is_string($value)) {
				$proveedor[$key] = mb_convert_encoding($value, 'UTF-8', 'auto');
			}
		}
	}
	unset($proveedor);
	
	// Guardar proveedores en sesión
	$_SESSION['proveedores'] = $proveedores;

} else {
	echo "<script>alert('Error al obtener la lista de proveedores: " . mysqli_error($conexion) . "'); 
			window.location.href = '../index.php?opc=dashboard';</script>";
	exit;
}

?>