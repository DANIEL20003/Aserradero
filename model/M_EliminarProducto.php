<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include("../config/Cconexion.php");
$clave = $_GET['id'];
// update activo to false instead of deleting
$sql = "UPDATE Productos SET activo = false WHERE id_producto = $clave";

$regre = mysqli_query($conexion, $sql);

if ($regre && mysqli_affected_rows($conexion) > 0) {
    header("Location:../index.php?opc=listar_productos");
    exit();
} else {
    echo "Error: El ID no existe.";
}

?>
