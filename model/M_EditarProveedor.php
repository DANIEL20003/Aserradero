<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include('../config/Cconexion.php');

// Obtener valores del formulario
$id_proveedor = $_POST['id_proveedor'];
$nombre = $_POST['nombre'];

// Actualizar el proveedor
$sql_update_proveedor = "UPDATE Proveedores SET descripcion = '$nombre' WHERE id_proveedor = $id_proveedor";
$resultado = mysqli_query($conexion, $sql_update_proveedor);

if($resultado && mysqli_affected_rows($conexion) > 0) {
    header("Location: ../index.php?opc=listar_proveedores");
} else {
    echo "<script>alert('No se pudo actualizar el proveedor. Por favor, inténtalo de nuevo.'); 
            window.location.href = '../index.php?opc=editar_proveedor&id=$id_proveedor';</script>";
    exit;
}

?>
