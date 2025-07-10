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
require_once '../config/clavebasededatos.php';

try {
    $pdo = new PDO("mysql:host=$hostname;dbname=$database;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Obtener datos JSON del POST
    $json = file_get_contents('php://input');
    $data = json_decode($json, true);
    
    if (!$data) {
        throw new Exception('Datos inválidos');
    }
    
    // Determinar si es un pedido creado por admin
    $es_admin_pedido = isset($data['admin_pedido']) && $data['admin_pedido'] === true;
    
    if ($es_admin_pedido) {
        // Verificar que el usuario sea admin
        if (!$_SESSION['esAdmin']) {
            throw new Exception('No tienes permisos para crear pedidos para otros usuarios');
        }
        
        // Validar datos para pedido de admin
        $required_fields = ['cliente_id', 'productos', 'total'];
        foreach ($required_fields as $field) {
            if (!isset($data[$field])) {
                throw new Exception("Campo requerido faltante: $field");
            }
        }
        
        if (empty($data['productos']) || !is_array($data['productos'])) {
            throw new Exception('El carrito está vacío');
        }
        
        $cliente_id = (int)$data['cliente_id'];
        $productos = $data['productos'];
        $total = (float)$data['total'];
        
    } else {
        // Validar datos para pedido normal del usuario
        $required_fields = ['nombre', 'email', 'telefono', 'metodo_pago', 'items', 'total'];
        foreach ($required_fields as $field) {
            if (empty($data[$field])) {
                throw new Exception("Campo requerido faltante: $field");
            }
        }
        
        if (empty($data['items']) || !is_array($data['items'])) {
            throw new Exception('El carrito está vacío');
        }
    }
    
    // Comenzar transacción
    $pdo->beginTransaction();
    
    if ($es_admin_pedido) {
        // Insertar pedido creado por admin
        $stmt = $pdo->prepare("
            INSERT INTO Pedidos (id_usuario, total, estado, creado_en, activo) 
            VALUES (?, ?, 'pendiente', NOW(), 1)
        ");
        $stmt->execute([$cliente_id, $total]);
        $pedido_id = $pdo->lastInsertId();
        
        // Insertar detalles del pedido
        $stmt_detalle = $pdo->prepare("
            INSERT INTO Pedido_detalles (id_pedido, id_producto, cantidad, precio) 
            VALUES (?, ?, ?, ?)
        ");
        
        foreach ($productos as $producto) {
            // Verificar stock disponible
            $stmt_stock = $pdo->prepare("SELECT stock FROM Productos WHERE id_producto = ?");
            $stmt_stock->execute([$producto['id']]);
            $stock_actual = $stmt_stock->fetchColumn();
            
            if ($stock_actual < $producto['cantidad']) {
                throw new Exception("Stock insuficiente para el producto: {$producto['nombre']}");
            }
            
            $stmt_detalle->execute([
                $pedido_id,
                $producto['id'],
                $producto['cantidad'],
                $producto['precio']
            ]);
        }
        
    } else {
        // Insertar pedido normal del usuario
        $stmt = $pdo->prepare("
            INSERT INTO Pedidos (
                id_usuario, 
                total, 
                estado,
                creado_en,
                activo,
                receptor,
                correo,
                telefono,
                identificacion,
                metodo_pago
            ) VALUES (?, ?, 'pendiente', NOW(), 1, ?, ?, ?, ?, ?)
        ");
        
        $stmt->execute([
            $_SESSION['id_usuario'],
            $data['total'],
            $data['nombre'],
            $data['email'],
            $data['telefono'],
            $data['documento'] ?? '',
            $data['metodo_pago']
        ]);
        
        $pedido_id = $pdo->lastInsertId();
        
        // Insertar detalles del pedido
        $stmt_detalle = $pdo->prepare("
            INSERT INTO Pedido_detalles (id_pedido, id_producto, cantidad, precio) 
            VALUES (?, ?, ?, ?)
        ");
        
        foreach ($data['items'] as $item) {
            // Verificar stock disponible
            $stmt_stock = $pdo->prepare("SELECT stock FROM Productos WHERE id_producto = ?");
            $stmt_stock->execute([$item['id']]);
            $stock_actual = $stmt_stock->fetchColumn();
            
            if ($stock_actual < $item['quantity']) {
                $stmt_producto = $pdo->prepare("SELECT nombre FROM Productos WHERE id_producto = ?");
                $stmt_producto->execute([$item['id']]);
                $nombre_producto = $stmt_producto->fetchColumn();
                throw new Exception("Stock insuficiente para el producto: {$nombre_producto}");
            }
            
            $stmt_detalle->execute([
                $pedido_id,
                $item['id'],
                $item['quantity'],
                $item['price']
            ]);
        }
    }
    
    // Confirmar transacción
    $pdo->commit();
    
    echo json_encode([
        'success' => true, 
        'message' => 'Pedido procesado exitosamente',
        'pedido_id' => $pedido_id
    ]);

} catch (Exception $e) {
    // Revertir transacción en caso de error
    if (isset($pdo)) {
        $pdo->rollBack();
    }
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>
