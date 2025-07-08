<?php
// Incluir la conexión a la base de datos
include_once './config/Cconexion.php';

// Verificar que se recibieron los datos por POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        // Obtener datos del formulario
        $id_cliente = mysqli_real_escape_string($conexion, $_POST['id_cliente']);
        $estado = mysqli_real_escape_string($conexion, $_POST['estado']);
        $productos = $_POST['productos'] ?? [];
        $cantidades = $_POST['cantidades'] ?? [];

        // Validar datos básicos
        if (empty($id_cliente) || empty($productos) || empty($cantidades)) {
            throw new Exception("Faltan datos obligatorios para registrar la venta.");
        }

        // Verificar que hay productos válidos
        $productos_validos = array_filter($productos);
        if (empty($productos_validos)) {
            throw new Exception("Debe seleccionar al menos un producto para la venta.");
        }

        // Iniciar transacción
        mysqli_autocommit($conexion, false);

        // Calcular total y validar stock
        $total = 0;
        $detalles_venta = [];

        for ($i = 0; $i < count($productos); $i++) {
            if (!empty($productos[$i]) && !empty($cantidades[$i]) && $cantidades[$i] > 0) {
                $id_producto = (int)$productos[$i];
                $cantidad = (int)$cantidades[$i];

                // Obtener información del producto
                $sql_producto = "SELECT nombre, precio, stock FROM Productos WHERE id_producto = $id_producto AND activo = 1";
                $resultado_producto = mysqli_query($conexion, $sql_producto);
                $producto = mysqli_fetch_assoc($resultado_producto);

                if (!$producto) {
                    throw new Exception("Producto con ID $id_producto no encontrado o inactivo.");
                }

                // Verificar stock disponible
                if ($cantidad > $producto['stock']) {
                    throw new Exception("Stock insuficiente para el producto '{$producto['nombre']}'. Stock disponible: {$producto['stock']}, solicitado: $cantidad");
                }

                $precio = (float)$producto['precio'];
                $subtotal = $precio * $cantidad;
                $total += $subtotal;

                $detalles_venta[] = [
                    'id_producto' => $id_producto,
                    'cantidad' => $cantidad,
                    'precio' => $precio,
                    'subtotal' => $subtotal
                ];
            }
        }

        if (empty($detalles_venta)) {
            throw new Exception("No se encontraron productos válidos para la venta.");
        }

        // Insertar el pedido
        $sql_pedido = "INSERT INTO Pedidos (id_usuario, total, estado, creado_en) 
                       VALUES ($id_cliente, $total, '$estado', NOW())";

        if (!mysqli_query($conexion, $sql_pedido)) {
            throw new Exception("Error al insertar el pedido: " . mysqli_error($conexion));
        }

        $id_pedido = mysqli_insert_id($conexion);

        // Insertar detalles del pedido y actualizar stock
        foreach ($detalles_venta as $detalle) {
            // Insertar detalle
            $sql_detalle = "INSERT INTO Pedido_detalles (id_pedido, id_producto, cantidad, precio, subtotal) 
                           VALUES ($id_pedido, {$detalle['id_producto']}, {$detalle['cantidad']}, {$detalle['precio']}, {$detalle['subtotal']})";

            if (!mysqli_query($conexion, $sql_detalle)) {
                throw new Exception("Error al insertar detalle del pedido: " . mysqli_error($conexion));
            }

            // Actualizar stock del producto (restar la cantidad vendida)
            $sql_actualizar_stock = "UPDATE Productos 
                                   SET stock = stock - {$detalle['cantidad']} 
                                   WHERE id_producto = {$detalle['id_producto']}";

            if (!mysqli_query($conexion, $sql_actualizar_stock)) {
                throw new Exception("Error al actualizar stock del producto: " . mysqli_error($conexion));
            }
        }

        // Si todo está bien, confirmar la transacción
        mysqli_commit($conexion);

        // Redirigir con mensaje de éxito
        echo "<script>
                alert('Venta registrada exitosamente. ID de pedido: $id_pedido');
                window.location.href = '../index.php?opc=listar_ventas';
              </script>";

    } catch (Exception $e) {
        // En caso de error, deshacer cambios
        mysqli_rollback($conexion);
        
        echo "<script>
                alert('Error al registrar la venta: " . addslashes($e->getMessage()) . "');
                window.history.back();
              </script>";
    }

    // Restaurar autocommit
    mysqli_autocommit($conexion, true);
    
    // Cerrar conexión
    mysqli_close($conexion);
} else {
    // Si no es POST, redirigir
    header("Location: ../index.php?opc=agregar_venta");
    exit;
}
?>
