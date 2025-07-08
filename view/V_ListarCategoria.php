<?php

include_once './model/M_ListarCategorias.php';

$categorias = $_SESSION['categorias'];
?>

<table>
	<tr>
		<th>ID</th>
		<th>Descripción</th>
		<th>Acciones</th>
	</tr>
	<?php foreach ($categorias as $categoria): ?>
		<tr>
			<td><?php echo $categoria['id_categoria']; ?></td>
			<td><?php echo $categoria['descripcion']; ?></td>
			<td>
				<a href="./index.php?opc=editar_categoria&id=<?php echo $categoria['id_categoria']; ?>">Editar</a>
				<a href="./model/M_EliminarCategoria.php?id=<?php echo $categoria['id_categoria']; ?>">Eliminar</a>
			</td>
		</tr>
	<?php endforeach; ?>
</table>
<a href="./index.php?opc=agregar_categoria">Agregar Categoría</a>
<a href="./index.php?opc=listar_productos">Volver a Productos</a>
<a href="./index.php?opc=dashboard">Volver al Dashboard</a>

