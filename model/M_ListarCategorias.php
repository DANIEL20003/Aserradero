<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include('./config/Cconexion.php');

// Obtener valores del formulario

// Insertar provincia y obtener su ID
$sql_consulta = "SELECT * FROM Categorias";
$result = mysqli_query($conexion, $sql_consulta);

if($result) {
	$categorias = mysqli_fetch_all($result, MYSQLI_ASSOC);
	
	// Aquí podrías procesar las categorías obtenidas si es necesario
	// Por ejemplo, podrías guardarlas en una variable de sesión o pasarlas a una vista

	// Redirigir a la página de listar categorías
	$_SESSION['categorias'] = $categorias; // Guardar en sesión si es necesario
	// header("Location: ../index.php?pagina=listar_categorias");

} else {
	echo "<script>alert('No se pudo agregar la categoría. Por favor, inténtalo de nuevo.'); 
			window.location.href = '../index.php?opc=agregar_categoria';</script>";
	exit;
}

?>