<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once '../config/Cconexion.php'; // AQUI LLAMO A MI ARCHIVO DE CONEXION A LA BASE DE DATOS

session_start();

$email = trim($_POST['email']);
$clave = $_POST['password'];

if(empty($email) || empty($clave)){
    header("Location: ../index.php?opc=login&error=empty_fields");
    exit;
}

// Usar prepared statement para evitar inyección SQL
$sql = "SELECT * FROM Usuarios WHERE correo = ? AND clave = ? AND activo = 1";
$stmt = mysqli_prepare($conexion, $sql);

if (!$stmt) {
    header("Location: ../index.php?opc=login&error=database");
    exit;
}

mysqli_stmt_bind_param($stmt, "ss", $email, $clave);
mysqli_stmt_execute($stmt);
$resultado = mysqli_stmt_get_result($stmt);

if(!$resultado) {
    header("Location: ../index.php?opc=login&error=database");
    exit;
}

if(mysqli_num_rows($resultado) > 0) {
    $fila = mysqli_fetch_assoc($resultado);
    
    // Verificar la contraseña hasheada
    if ($clave == $fila['clave']) {
        $_SESSION['sesion_iniciada'] = "iniciado";
        $_SESSION['id_usuario'] = $fila['id_usuario'];
        $_SESSION['nombre'] = $fila['nombre'];
        $_SESSION['correo'] = $fila['correo'];
        $_SESSION['esAdmin'] = $fila['id_usuario'] == 1 ? true : false;

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
} else {
    header("Location: ../index.php?opc=login&error=invalid");
    exit;
}

?>