<?php
// Incluir la conexión a la base de datos
include_once './config/Cconexion.php';

// Iniciar sesión si no está iniciada
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

try {
    // Consulta para obtener todas las ventas con información del cliente
    $sql = "SELECT 
                p.id_pedido,
                p.id_usuario,
                p.total,
                p.estado,
                p.creado_en,
                p.activo,
                u.nombre as cliente_nombre,
                u.cedula as cliente_cedula,
                f.id_factura
            FROM Pedidos p
            LEFT JOIN Usuarios u ON p.id_usuario = u.id_usuario
            LEFT JOIN Facturas f ON p.id_pedido = f.id_pedido
            WHERE p.activo = 1
            ORDER BY p.creado_en DESC";

    $resultado = mysqli_query($conexion, $sql);

    if (!$resultado) {
        throw new Exception("Error en la consulta: " . mysqli_error($conexion));
    }

    // Obtener todos los registros
    $ventas = mysqli_fetch_all($resultado, MYSQLI_ASSOC);

    // Guardar en sesión para uso en la vista
    $_SESSION['ventas'] = $ventas;

} catch (Exception $e) {
    // En caso de error, establecer array vacío y mostrar mensaje
    $_SESSION['ventas'] = [];
    $_SESSION['error_message'] = "Error al cargar las ventas: " . $e->getMessage();
}

// Cerrar conexión
mysqli_close($conexion);
?>
