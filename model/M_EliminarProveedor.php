<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include("../config/Cconexion.php");
$clave = $_GET['id'];
// update activo to false instead of deleting
$sql = "UPDATE Proveedores SET activo = false WHERE id_proveedor = $clave";

$regre = mysqli_query($conexion, $sql);

if ($regre && mysqli_affected_rows($conexion) > 0) {
    header("Location:../index.php?opc=listar_proveedores");
    exit();
} else {
    echo "Error: El ID no existe.";
}

// if($regre){
//     header("location:../login.html");
// }else {
//     echo"no se  elimino";
// }

?>