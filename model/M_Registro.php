<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once '../config/Cconexion.php';

// Verificar que se recibieron todos los datos necesarios
if (!isset($_POST['nombre']) || !isset($_POST['email']) || !isset($_POST['cedula']) || !isset($_POST['password']) || !isset($_POST['confirm_password'])) {
    header("Location: ../index.php?opc=registro&error=missing_data");
    exit;
}

// Obtener y limpiar los datos del formulario
$nombre = mysqli_real_escape_string($conexion, trim($_POST['nombre']));
$correo = mysqli_real_escape_string($conexion, trim($_POST['email']));
$cedula = mysqli_real_escape_string($conexion, trim($_POST['cedula']));
$clave = $_POST['password'];
$confirmar_clave = $_POST['confirm_password'];

// Validaciones del servidor
if (empty($nombre) || empty($correo) || empty($cedula) || empty($clave) || empty($confirmar_clave)) {
    header("Location: ../index.php?opc=registro&error=empty_fields");
    exit;
}

// Validar formato de email
if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
    header("Location: ../index.php?opc=registro&error=invalid_email");
    exit;
}

// Validar que las contraseñas coincidan
if ($clave !== $confirmar_clave) {
    header("Location: ../index.php?opc=registro&error=password_mismatch");
    exit;
}

// Validar longitud de contraseña
if (strlen($clave) < 6) {
    header("Location: ../index.php?opc=registro&error=password_short");
    exit;
}

// Validar formato de cédula (solo números)
if (!preg_match('/^[0-9]{8,12}$/', $cedula)) {
    header("Location: ../index.php?opc=registro&error=invalid_cedula");
    exit;
}

// Verificar que no exista un usuario con el mismo correo usando prepared statement
$sql_check_email = "SELECT id_usuario FROM Usuarios WHERE correo = ?";
$stmt_email = mysqli_prepare($conexion, $sql_check_email);

if (!$stmt_email) {
    header("Location: ../index.php?opc=registro&error=database");
    exit;
}

mysqli_stmt_bind_param($stmt_email, "s", $correo);
mysqli_stmt_execute($stmt_email);
$result_email = mysqli_stmt_get_result($stmt_email);

if (mysqli_num_rows($result_email) > 0) {
    header("Location: ../index.php?opc=registro&error=email_exists");
    exit;
}

// Verificar que no exista un usuario con la misma cédula usando prepared statement
$sql_check_cedula = "SELECT id_usuario FROM Usuarios WHERE cedula = ?";
$stmt_cedula = mysqli_prepare($conexion, $sql_check_cedula);

if (!$stmt_cedula) {
    header("Location: ../index.php?opc=registro&error=database");
    exit;
}

mysqli_stmt_bind_param($stmt_cedula, "s", $cedula);
mysqli_stmt_execute($stmt_cedula);
$result_cedula = mysqli_stmt_get_result($stmt_cedula);

if (mysqli_num_rows($result_cedula) > 0) {
    header("Location: ../index.php?opc=registro&error=cedula_exists");
    exit;
}

// Hash de la contraseña
$clave_hash = password_hash($clave, PASSWORD_DEFAULT);

// Insertar el nuevo usuario usando prepared statement
$sql_insert = "INSERT INTO Usuarios (nombre, correo, cedula, clave, fecha_registro, activo) 
               VALUES (?, ?, ?, ?, NOW(), 1)";

$stmt_insert = mysqli_prepare($conexion, $sql_insert);

if (!$stmt_insert) {
    header("Location: ../index.php?opc=registro&error=database");
    exit;
}

mysqli_stmt_bind_param($stmt_insert, "ssss", $nombre, $correo, $cedula, $clave_hash);
$resultado = mysqli_stmt_execute($stmt_insert);

if ($resultado) {
    $id_usuario = mysqli_insert_id($conexion);
    
    // Iniciar sesión automáticamente después del registro
    session_start();
    $_SESSION['sesion_iniciada'] = "iniciado";
    $_SESSION['id_usuario'] = $id_usuario;
    $_SESSION['nombre'] = $nombre;
    $_SESSION['correo'] = $correo;
    $_SESSION['esAdmin'] = false; // Los usuarios registrados no son admin por defecto
    
    header("Location: ../index.php?opc=dashboard&message=registered");
} else {
    header("Location: ../index.php?opc=registro&error=database&details=" . urlencode(mysqli_error($conexion)));
}

mysqli_close($conexion);
?>
