<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include('./config/Cconexion.php');

// Obtener valores del formulario

// Insertar provincia y obtener su ID
$sql_consulta = "SELECT * FROM Productos WHERE activo = true"; // Asegúrate de que la consulta sea correcta según tu base de dato
$result = mysqli_query($conexion, $sql_consulta);

if($result) {
	$productos_raw = mysqli_fetch_all($result, MYSQLI_ASSOC);
    $productos = [];
    foreach ($productos_raw as $producto) {
        $producto_encoded = [];
        foreach ($producto as $key => $value) {
            if (is_string($value)) {
                $producto_encoded[$key] = mb_convert_encoding($value, 'UTF-8', 'ISO-8859-1');
            } else {
                $producto_encoded[$key] = $value;
            }
        }
        $productos[] = $producto_encoded;
    }
	// Aquí podrías procesar los productos obtenidos si es necesario
	// Por ejemplo, podrías guardarlas en una variable de sesión o pasarlas a una vista

	// Redirigir a la página de listar productos
	$_SESSION['productos'] = $productos; // Guardar en sesión si es necesario
	// header("Location: ../index.php?pagina=listar_productos");

} else {
	echo "<script>alert('No se pudo agregar el producto. Por favor, inténtalo de nuevo.'); 
			window.location.href = '../index.php?opc=agregar_producto';</script>";
	exit;
}

?>