<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include('../config/Cconexion.php');

// Obtener valores del formulario
$nombre = $_POST['nombre'];

// Insertar provincia y obtener su ID
$sql_insert_proveedor = "INSERT INTO Proveedores (descripcion) VALUES ('$nombre')";
mysqli_query($conexion, $sql_insert_proveedor);
$id_proveedor = mysqli_insert_id($conexion);

if($id_proveedor) {

	header("Location: ../index.php?opc=listar_proveedores");

} else {
	echo "<script>alert('No se pudo agregar el proveedor. Por favor, inténtalo de nuevo.'); 
			window.location.href = '../index.php?opc=agregar_proveedor';</script>";
	exit;
}


?>