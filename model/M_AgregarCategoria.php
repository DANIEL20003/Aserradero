<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include('../config/Cconexion.php');

// Obtener valores del formulario
$nombre = $_POST['nombre'];

// Insertar provincia y obtener su ID
$sql_insert_categoria = "INSERT INTO Categorias (descripcion) VALUES ('$nombre')";
mysqli_query($conexion, $sql_insert_categoria);
$id_categoria = mysqli_insert_id($conexion);

if($id_categoria) {
	
	header("Location: ../index.php?opc=listar_categorias");

} else {
	echo "<script>alert('No se pudo agregar la categoría. Por favor, inténtalo de nuevo.'); 
			window.location.href = '../index.php?opc=agregar_categoria';</script>";
	exit;
}


?>