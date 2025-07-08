<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include('../config/Cconexion.php');

// Obtener valores del formulario
$id_categoria = $_POST['id_categoria'];
$nombre = $_POST['nombre'];

// Actualizar la categoría
$sql_update_categoria = "UPDATE Categorias SET descripcion = '$nombre' WHERE id_categoria = $id_categoria";
$resultado = mysqli_query($conexion, $sql_update_categoria);

if($resultado && mysqli_affected_rows($conexion) > 0) {
    header("Location: ../index.php?opc=listar_categorias");
} else {
    echo "<script>alert('No se pudo actualizar la categoría. Por favor, inténtalo de nuevo.'); 
            window.location.href = '../index.php?opc=editar_categoria&id=$id_categoria';</script>";
    exit;
}

?>
