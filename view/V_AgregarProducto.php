<form action="./model/M_AgregarProducto.php" method="POST">
    <label for="nombre">Nombre del Producto:</label>
    <input type="text" id="nombre" name="nombre" required>

    <label for="categoria">Categoría:</label>
    <select id="categoria" name="categoria" required>
        <option value="categoria1">Categoría 1</option>
        <option value="categoria2">Categoría 2</option>
    </select>

    <label for="precio">Precio:</label>
    <input type="number" id="precio" name="precio" step="0.01" required>

    <input type="submit" value="Agregar Producto">
</form>