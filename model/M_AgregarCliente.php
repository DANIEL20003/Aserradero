<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include('../config/Cconexion.php');

// Verificar que se recibieron todos los datos necesarios
if (!isset($_POST['nombre']) || !isset($_POST['correo']) || !isset($_POST['cedula']) || !isset($_POST['clave'])) {
    echo "<script>alert('Faltan datos requeridos.'); 
            window.location.href = '../index.php?opc=agregar_cliente';</script>";
    exit;
}

// Obtener valores del formulario con validación
$nombre = mysqli_real_escape_string($conexion, $_POST['nombre']);
$correo = mysqli_real_escape_string($conexion, $_POST['correo']);
$cedula = mysqli_real_escape_string($conexion, $_POST['cedula']);
$clave = mysqli_real_escape_string($conexion, $_POST['clave']);

// Verificar si el correo ya existe
$sql_verificar = "SELECT id_usuario FROM Usuarios WHERE correo = '$correo'";
$resultado_verificar = mysqli_query($conexion, $sql_verificar);

if (mysqli_num_rows($resultado_verificar) > 0) {
    echo "<script>alert('Ya existe un usuario con ese correo electrónico.'); 
            window.location.href = '../index.php?opc=agregar_cliente';</script>";
    exit;
}

// Verificar si la cédula ya existe
$sql_verificar_cedula = "SELECT id_usuario FROM Usuarios WHERE cedula = '$cedula'";
$resultado_verificar_cedula = mysqli_query($conexion, $sql_verificar_cedula);

if (mysqli_num_rows($resultado_verificar_cedula) > 0) {
    echo "<script>alert('Ya existe un usuario con esa cédula.'); 
            window.location.href = '../index.php?opc=agregar_cliente';</script>";
    exit;
}

// Encriptar la contraseña
// $clave_encriptada = password_hash($clave, PASSWORD_DEFAULT);

// Insertar cliente
$sql_insert_cliente = "INSERT INTO Usuarios (nombre, correo, cedula, clave, fecha_registro) 
                       VALUES ('$nombre', '$correo', '$cedula', '$clave', NOW())";

$resultado = mysqli_query($conexion, $sql_insert_cliente);

if($resultado) {
    $id_cliente = mysqli_insert_id($conexion);
    if($id_cliente) {
        header("Location: ../index.php?opc=listar_clientes");
    } else {
        echo "<script>alert('No se pudo agregar el cliente. Error en la inserción.'); 
                window.location.href = '../index.php?opc=agregar_cliente';</script>";
    }
} else {
    echo "<script>alert('Error en la consulta SQL: " . mysqli_error($conexion) . "'); 
            window.location.href = '../index.php?opc=agregar_cliente';</script>";
}

?>
