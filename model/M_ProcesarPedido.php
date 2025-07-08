<?php
header('Content-Type: application/json');

// Iniciar sesión si no está iniciada
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Verificar si hay sesión activa
if (!isset($_SESSION['sesion_iniciada']) || $_SESSION['sesion_iniciada'] !== "iniciado") {
    echo json_encode(['success' => false, 'error' => 'Sesión no válida']);
    exit;
}

require_once '../config/Cconexion.php';

try {
    // Obtener datos JSON del POST
    $json = file_get_contents('php://input');
    $data = json_decode($json, true);
    
    if (!$data) {
        throw new Exception('Datos inválidos');
    }
    
    // Validar datos requeridos
    $required_fields = ['nombre', 'email', 'telefono', 'direccion', 'ciudad', 'metodo_pago', 'items', 'total'];
    foreach ($required_fields as $field) {
        if (empty($data[$field])) {
            throw new Exception("Campo requerido faltante: $field");
        }
    }
    
    if (empty($data['items']) || !is_array($data['items'])) {
        throw new Exception('El carrito está vacío');
    }
    
    // Comenzar transacción
    mysqli_begin_transaction($conexion);
    
    // Insertar pedido principal
    $sql_pedido = "INSERT INTO Ventas (
        id_cliente, 
        fecha_venta, 
        subtotal, 
        costo_envio, 
        total, 
        metodo_pago, 
        estado,
        direccion_entrega,
        ciudad_entrega,
        telefono_contacto,
        comentarios
    ) VALUES (?, NOW(), ?, ?, ?, ?, 'pendiente', ?, ?, ?, ?)";
    
    $stmt_pedido = mysqli_prepare($conexion, $sql_pedido);
    
    if (!$stmt_pedido) {
        throw new Exception('Error preparando consulta de pedido');
    }
    
    $id_cliente = $_SESSION['id_usuario'];
    $subtotal = $data['subtotal'];
    $costo_envio = $data['costo_envio'];
    $total = $data['total'];
    $metodo_pago = $data['metodo_pago'];
    $direccion = $data['direccion'];
    $ciudad = $data['ciudad'];
    $telefono = $data['telefono'];
    $comentarios = $data['comentarios'] ?? '';
    
    mysqli_stmt_bind_param($stmt_pedido, "idddsssss", 
        $id_cliente, $subtotal, $costo_envio, $total, $metodo_pago, 
        $direccion, $ciudad, $telefono, $comentarios
    );
    
    if (!mysqli_stmt_execute($stmt_pedido)) {
        throw new Exception('Error al insertar pedido');
    }
    
    $id_venta = mysqli_insert_id($conexion);
    
    // Insertar detalles del pedido
    $sql_detalle = "INSERT INTO DetalleVentas (id_venta, id_producto, cantidad, precio_unitario, subtotal) 
                    VALUES (?, ?, ?, ?, ?)";
    $stmt_detalle = mysqli_prepare($conexion, $sql_detalle);
    
    if (!$stmt_detalle) {
        throw new Exception('Error preparando consulta de detalle');
    }
    
    foreach ($data['items'] as $item) {
        $id_producto = $item['id'];
        $cantidad = $item['quantity'];
        $precio_unitario = $item['price'];
        $subtotal_item = $cantidad * $precio_unitario;
        
        mysqli_stmt_bind_param($stmt_detalle, "iiidd", 
            $id_venta, $id_producto, $cantidad, $precio_unitario, $subtotal_item
        );
        
        if (!mysqli_stmt_execute($stmt_detalle)) {
            throw new Exception('Error al insertar detalle del pedido');
        }
        
        // Actualizar stock del producto
        $sql_stock = "UPDATE Productos SET stock = stock - ? WHERE id_producto = ?";
        $stmt_stock = mysqli_prepare($conexion, $sql_stock);
        
        if ($stmt_stock) {
            mysqli_stmt_bind_param($stmt_stock, "ii", $cantidad, $id_producto);
            mysqli_stmt_execute($stmt_stock);
            mysqli_stmt_close($stmt_stock);
        }
    }
    
    // Confirmar transacción
    mysqli_commit($conexion);
    
    // Generar número de pedido formateado
    $order_number = 'PED-' . str_pad($id_venta, 6, '0', STR_PAD_LEFT);
    
    echo json_encode([
        'success' => true,
        'order_id' => $order_number,
        'message' => 'Pedido procesado exitosamente'
    ]);
    
} catch (Exception $e) {
    // Rollback en caso de error
    mysqli_rollback($conexion);
    
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}

mysqli_close($conexion);
?>
