<?php
// Iniciar sesión si no está iniciada
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Verificar si hay sesión activa y si es admin
if (!isset($_SESSION['sesion_iniciada']) || $_SESSION['sesion_iniciada'] !== "iniciado" || !$_SESSION['esAdmin']) {
    echo json_encode(['success' => false, 'message' => 'No autorizado']);
    exit;
}

require_once '../config/Cconexion.php';
require_once '../config/clavebasededatos.php';

header('Content-Type: application/json');

try {
    $pdo = new PDO("mysql:host=$hostname;dbname=$database;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Método no permitido');
    }

    $input = json_decode(file_get_contents('php://input'), true);
    
    if (!isset($input['pedido_id']) || !isset($input['nuevo_estado'])) {
        throw new Exception('Datos incompletos');
    }

    $pedido_id = (int)$input['pedido_id'];
    $nuevo_estado = $input['nuevo_estado'];

    // Validar estados permitidos
    $estados_permitidos = ['pendiente', 'aceptado', 'cancelado'];
    if (!in_array($nuevo_estado, $estados_permitidos)) {
        throw new Exception('Estado no válido');
    }

    $pdo->beginTransaction();

    // Obtener el estado actual del pedido
    $stmt = $pdo->prepare("SELECT estado FROM Pedidos WHERE id_pedido = ?");
    $stmt->execute([$pedido_id]);
    $pedido = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$pedido) {
        throw new Exception('Pedido no encontrado');
    }

    $estado_anterior = $pedido['estado'];

    // Si se está aceptando el pedido, verificar stock y descontarlo
    if ($nuevo_estado === 'aceptado' && $estado_anterior !== 'aceptado') {
        // Obtener detalles del pedido
        $stmt = $pdo->prepare("
            SELECT pd.id_producto, pd.cantidad, p.stock, p.nombre
            FROM Pedido_detalles pd
            JOIN Productos p ON pd.id_producto = p.id_producto
            WHERE pd.id_pedido = ?
        ");
        $stmt->execute([$pedido_id]);
        $detalles = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Verificar que hay suficiente stock
        foreach ($detalles as $detalle) {
            if ($detalle['stock'] < $detalle['cantidad']) {
                throw new Exception("Stock insuficiente para el producto: {$detalle['nombre']}. Stock disponible: {$detalle['stock']}, solicitado: {$detalle['cantidad']}");
            }
        }

        // Descontar stock
        foreach ($detalles as $detalle) {
            $stmt = $pdo->prepare("UPDATE Productos SET stock = stock - ? WHERE id_producto = ?");
            $stmt->execute([$detalle['cantidad'], $detalle['id_producto']]);
        }
    }

    // Si se está cancelando o rechazando un pedido que estaba aceptado, restaurar stock
    if (($nuevo_estado === 'cancelado' || $nuevo_estado === 'pendiente') && $estado_anterior === 'aceptado') {
        // Obtener detalles del pedido
        $stmt = $pdo->prepare("
            SELECT pd.id_producto, pd.cantidad
            FROM Pedido_detalles pd
            WHERE pd.id_pedido = ?
        ");
        $stmt->execute([$pedido_id]);
        $detalles = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Restaurar stock
        foreach ($detalles as $detalle) {
            $stmt = $pdo->prepare("UPDATE Productos SET stock = stock + ? WHERE id_producto = ?");
            $stmt->execute([$detalle['cantidad'], $detalle['id_producto']]);
        }
    }

    // Actualizar estado del pedido
    $stmt = $pdo->prepare("UPDATE Pedidos SET estado = ? WHERE id_pedido = ?");
    $stmt->execute([$nuevo_estado, $pedido_id]);

    $pdo->commit();

    echo json_encode([
        'success' => true, 
        'message' => 'Estado del pedido actualizado correctamente',
        'nuevo_estado' => $nuevo_estado
    ]);

} catch (Exception $e) {
    if (isset($pdo)) {
        $pdo->rollBack();
    }
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>
