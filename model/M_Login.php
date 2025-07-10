<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once '../config/Cconexion.php'; // AQUI LLAMO A MI ARCHIVO DE CONEXION A LA BASE DE DATOS

session_start();

$email = trim($_POST['email']);
$clave = $_POST['password'];

if(empty($email) || empty($clave)){
	echo "<script>alert('Por favor, complete todos los campos.'); 
			window.location.href = '../index.php?opc=login';</script>";
	exit;
}

// Usar prepared statement para evitar inyección SQL
$sql = "SELECT * FROM Usuarios WHERE correo = ? AND clave = ? AND activo = 1";
$stmt = mysqli_prepare($conexion, $sql);

if (!$stmt) {
	echo "<script>alert('Error en la preparación de la consulta: " . mysqli_error($conexion) . "'); 
			window.location.href = '../index.php?opc=login';</script>";
	exit;
}

mysqli_stmt_bind_param($stmt, "ss", $email, $clave);
mysqli_stmt_execute($stmt);
$resultado = mysqli_stmt_get_result($stmt);

if(!$resultado) {
	echo "<script>alert('Error en la consulta: " . mysqli_error($conexion) . "'); 
			window.location.href = '../index.php?opc=login';</script>";
    exit;
}

if(mysqli_num_rows($resultado) > 0) {
    $fila = mysqli_fetch_assoc($resultado);

	if($clave != $fila['clave']) {
		echo "<script>alert('Correo o contraseña incorrecta.'); 
				window.location.href = '../index.php?opc=login';</script>";
		exit;
	}

	$_SESSION['sesion_iniciada'] = "iniciado";
	$_SESSION['id_usuario'] = $fila['id_usuario'];
	$_SESSION['nombre'] = $fila['nombre'];
	$_SESSION['correo'] = $fila['correo'];
	$_SESSION['telefono'] = $fila['telefono'];
	$_SESSION['cedula'] = $fila['cedula'];
	$_SESSION['esAdmin'] = $fila['id_usuario'] == 1 ? true : false;

	if($_SESSION['esAdmin']) {
		header("Location: ../index.php?opc=dashboard");
		exit();
	}

	// Verificar si hay redirección desde el formulario
	if (isset($_POST['redirect_after_login']) && !empty($_POST['redirect_after_login'])) {
		$redirect = $_POST['redirect_after_login'];
		header("Location: ../index.php?opc=$redirect");
		exit();
	}

	if(isset($_SESSION['vienecarrito']) && $_SESSION['vienecarrito'] == true){
		$_SESSION['vienecarrito'] = false;
		header("Location: ../index.php?opc=pedido");
	} else {
		// Verificar si hay redirección pendiente
		if (isset($_SESSION['redirect_after_login'])) {
			$redirect = $_SESSION['redirect_after_login'];
			unset($_SESSION['redirect_after_login']);
			header("Location: ../index.php?opc=$redirect");
		} else {
			header("Location: ../index.php?opc=dashboard");
		}
	}
	exit();
} else {
    header("Location: ../index.php?opc=login&error=invalid");
    exit;
}

?>