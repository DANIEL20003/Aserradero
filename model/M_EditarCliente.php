<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include('../config/Cconexion.php');

// Verificar que se recibieron todos los datos necesarios
if (!isset($_POST['id_usuario']) || !isset($_POST['nombre']) || !isset($_POST['correo']) || !isset($_POST['cedula'])) {
    echo "<script>alert('Faltan datos requeridos.'); 
            window.location.href = '../index.php?opc=listar_clientes';</script>";
    exit;
}

// Obtener valores del formulario con validación
$id_usuario = intval($_POST['id_usuario']);
$nombre = mysqli_real_escape_string($conexion, $_POST['nombre']);
$correo = mysqli_real_escape_string($conexion, $_POST['correo']);
$cedula = mysqli_real_escape_string($conexion, $_POST['cedula']);
$clave = isset($_POST['clave']) && !empty($_POST['clave']) ? mysqli_real_escape_string($conexion, $_POST['clave']) : null;

// Verificar si el correo ya existe en otro usuario
$sql_verificar = "SELECT id_usuario FROM Usuarios WHERE correo = '$correo' AND id_usuario != $id_usuario";
$resultado_verificar = mysqli_query($conexion, $sql_verificar);

if (mysqli_num_rows($resultado_verificar) > 0) {
    echo "<script>alert('Ya existe otro usuario con ese correo electrónico.'); 
            window.location.href = '../index.php?opc=editar_cliente&id=$id_usuario';</script>";
    exit;
}

// Verificar si la cédula ya existe en otro usuario
$sql_verificar_cedula = "SELECT id_usuario FROM Usuarios WHERE cedula = '$cedula' AND id_usuario != $id_usuario";
$resultado_verificar_cedula = mysqli_query($conexion, $sql_verificar_cedula);

if (mysqli_num_rows($resultado_verificar_cedula) > 0) {
    echo "<script>alert('Ya existe otro usuario con esa cédula.'); 
            window.location.href = '../index.php?opc=editar_cliente&id=$id_usuario';</script>";
    exit;
}

// Construir consulta UPDATE
if ($clave !== null) {
    // Si se proporcionó nueva contraseña, encriptarla y actualizarla
    $clave_encriptada = password_hash($clave, PASSWORD_DEFAULT);
    $sql_update_cliente = "UPDATE Usuarios SET 
                          nombre = '$nombre', 
                          correo = '$correo', 
                          cedula = '$cedula', 
                          clave = '$clave_encriptada' 
                          WHERE id_usuario = $id_usuario";
} else {
    // Si no se proporcionó contraseña, no actualizarla
    $sql_update_cliente = "UPDATE Usuarios SET 
                          nombre = '$nombre', 
                          correo = '$correo', 
                          cedula = '$cedula' 
                          WHERE id_usuario = $id_usuario";
}

$resultado = mysqli_query($conexion, $sql_update_cliente);

if($resultado && mysqli_affected_rows($conexion) > 0) {
    header("Location: ../index.php?opc=listar_clientes");
} else {
    echo "<script>alert('No se pudo actualizar el cliente. Error: " . mysqli_error($conexion) . "'); 
            window.location.href = '../index.php?opc=editar_cliente&id=$id_usuario';</script>";
    exit;
}

?>
