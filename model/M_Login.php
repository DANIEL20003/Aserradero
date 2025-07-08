<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once '../config/Cconexion.php'; // AQUI LLAMO A MI ARCHIVO DE CONEXION A LA BASE DE DATOS

session_start();

$email = $_POST['email'];
$clave = $_POST['password'];

if($email == "" || $clave == ""){
    echo "Por favor, ingrese su correo y contraseña.";
    return;
}

$sql = "SELECT * FROM Usuarios WHERE correo = '$email' AND clave = '$clave'";
$resultado = mysqli_query($conexion, $sql);

if(!$resultado) {
    echo "Error en la consulta: " . mysqli_error($conexion);
    exit;
}

if(mysqli_num_rows($resultado) > 0) {

    $_SESSION['sesion_iniciada'] = "iniciado";
    // nombre
    $fila = mysqli_fetch_assoc($resultado);
	
    $_SESSION['id_usuario'] = $fila['id_usuario'];
    $_SESSION['nombre'] = $fila['nombre'];
    $_SESSION['correo'] = $fila['correo'];
    $_SESSION['esAdmin'] = $fila['id_usuario'] == 1 ? true : false;

    if(isset($_SESSION['vienecarrito']) && $_SESSION['vienecarrito'] == true){
        $_SESSION['vienecarrito'] = false;
        header("Location: ../index.php?opc=pedido");
    } else {
        header("Location: ../index.php?opc=productos");
    }
    exit(); // Importante: detener la ejecución del script después de la redirección
} else {
	echo "<script>alert('Credenciales incorrectas. Por favor, inténtalo de nuevo.'); 
			window.location.href = '../index.php?opc=login';</script>";
	exit;
}

?>