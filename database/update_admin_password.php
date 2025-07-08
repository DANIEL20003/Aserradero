<?php
// Script para actualizar la contraseña del administrador a hash
require_once '../config/Cconexion.php';

// Contraseña en texto plano del administrador
$admin_password = 'admin';

// Generar hash de la contraseña
$admin_password_hash = password_hash($admin_password, PASSWORD_DEFAULT);

// Actualizar la contraseña del administrador (id_usuario = 1)
$sql = "UPDATE Usuarios SET clave = '$admin_password_hash' WHERE id_usuario = 1";

$resultado = mysqli_query($conexion, $sql);

if ($resultado) {
    echo "Contraseña del administrador actualizada correctamente con hash.";
} else {
    echo "Error al actualizar la contraseña: " . mysqli_error($conexion);
}

mysqli_close($conexion);
?>
