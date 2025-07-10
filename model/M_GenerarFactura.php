<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
// Iniciar sesión si no está iniciada
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Verificar si hay sesión activa
if (!isset($_SESSION['sesion_iniciada']) || $_SESSION['sesion_iniciada'] !== "iniciado") {
    // Determinar la ruta de redirección según desde dónde se accede
    $redirect_path = (dirname($_SERVER['REQUEST_URI']) === '/model') ? '../index.php?opc=login' : 'index.php?opc=login';
    header("Location: $redirect_path");
    exit;
}

// Determinar las rutas base según el directorio actual
$base_dir = dirname(__DIR__);
$config_path = $base_dir . '/config/Cconexion.php';
$claves_path = $base_dir . '/config/clavebasededatos.php';
$fpdf_path = $base_dir . '/fpdf182/fpdf.php';

// Si estamos en la raíz del proyecto, usar rutas relativas
if (file_exists('./config/Cconexion.php')) {
    require_once './config/Cconexion.php';
    require_once './config/clavebasededatos.php';
    require_once './fpdf182/fpdf.php';
} elseif (file_exists($config_path)) {
    // Si estamos en un subdirectorio, usar rutas absolutas
    require_once $config_path;
    require_once $claves_path;
    require_once $fpdf_path;
} else {
    die('Error: No se pueden encontrar los archivos de configuración');
}

if (!isset($_GET['pedido_id'])) {
    die('ID de pedido no especificado');
}

$pedido_id = (int)$_GET['pedido_id'];

try {
    $pdo = new PDO("mysql:host=$hostname;dbname=$database;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Comenzar transacción
    $pdo->beginTransaction();

    // Verificar que el pedido existe
    $stmt = $pdo->prepare("
        SELECT p.*, u.nombre as cliente_nombre, u.correo as cliente_email, u.telefono as cliente_telefono
        FROM Pedidos p
        JOIN Usuarios u ON p.id_usuario = u.id_usuario
        WHERE p.id_pedido = ?
    ");
    $stmt->execute([$pedido_id]);
    $pedido = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$pedido) {
        die('Pedido no encontrado');
    }

    // Verificar si ya existe una factura para este pedido
    $stmt = $pdo->prepare("SELECT id_factura, secuencial FROM Facturas WHERE id_pedido = ? AND activo = 1");
    $stmt->execute([$pedido_id]);
    $factura_existente = $stmt->fetch(PDO::FETCH_ASSOC);

    $numero_factura = '';
    $ya_facturado = false;

    if ($factura_existente) {
        // Ya existe una factura
        $numero_factura = $factura_existente['secuencial'];
        $ya_facturado = true;
    } else {
        // Crear nueva factura
        $secuencial = date('Ymd') . str_pad($pedido_id, 6, '0', STR_PAD_LEFT);
        
        $stmt = $pdo->prepare("
            INSERT INTO Facturas (id_pedido, secuencial, fecha_emision, activo) 
            VALUES (?, ?, NOW(), 1)
        ");
        $stmt->execute([$pedido_id, $secuencial]);
        $numero_factura = $secuencial;
    }

    // Obtener detalles del pedido
    $stmt = $pdo->prepare("
        SELECT pd.*, pr.nombre as producto_nombre, pr.stock, pd.precio
        FROM Pedido_detalles pd
        JOIN Productos pr ON pd.id_producto = pr.id_producto
        WHERE pd.id_pedido = ?
    ");
    $stmt->execute([$pedido_id]);
    $detalles = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (empty($detalles)) {
        die('No se encontraron detalles del pedido');
    }

    // Si es una nueva factura, verificar stock y descontarlo
    if (!$ya_facturado) {
        foreach ($detalles as $detalle) {
            // Verificar stock disponible
            if ($detalle['stock'] < $detalle['cantidad']) {
                throw new Exception("Stock insuficiente para el producto: {$detalle['producto_nombre']}. Stock disponible: {$detalle['stock']}, requerido: {$detalle['cantidad']}");
            }
        }

        // Descontar stock
        foreach ($detalles as $detalle) {
            $stmt = $pdo->prepare("UPDATE Productos SET stock = stock - ? WHERE id_producto = ?");
            $stmt->execute([$detalle['cantidad'], $detalle['id_producto']]);
        }

        // Actualizar estado del pedido a 'facturado'
        $stmt = $pdo->prepare("UPDATE Pedidos SET estado = 'facturado' WHERE id_pedido = ?");
        $stmt->execute([$pedido_id]);
    }

    // Confirmar transacción
    $pdo->commit();

    // Crear PDF
    $pdf = new FPDF();
    $pdf->AddPage();
    $pdf->SetFont('Arial', 'B', 16);

    // Encabezado
    $pdf->Cell(0, 10, 'ASERRADERO - FACTURA', 0, 1, 'C');
    $pdf->Ln(5);

    // Información de la empresa
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(0, 6, 'Aserradero Industrial', 0, 1);
    $pdf->SetFont('Arial', '', 10);
    $pdf->Cell(0, 5, 'Direccion: Calle Principal 123', 0, 1);
    $pdf->Cell(0, 5, 'Telefono: +593 123 456 789', 0, 1);
    $pdf->Cell(0, 5, 'Email: info@aserradero.com', 0, 1);
    $pdf->Ln(10);

    // Información del cliente y pedido
    $pdf->SetFont('Arial', 'B', 10);
    $pdf->Cell(95, 6, 'DATOS DEL CLIENTE:', 0, 0);
    $pdf->Cell(95, 6, 'DATOS DE LA FACTURA:', 0, 1);
    
    $pdf->SetFont('Arial', '', 9);
    $pdf->Cell(95, 5, 'Nombre: ' . $pedido['cliente_nombre'], 0, 0);
    $pdf->Cell(95, 5, 'Factura No: ' . $numero_factura, 0, 1);
    
    $pdf->Cell(95, 5, 'Email: ' . $pedido['cliente_email'], 0, 0);
    $pdf->Cell(95, 5, 'Fecha: ' . date('d/m/Y'), 0, 1);
    
    $pdf->Cell(95, 5, 'Telefono: ' . ($pedido['cliente_telefono'] ?? 'N/A'), 0, 0);
    $pdf->Cell(95, 5, 'Pedido No: PED-' . str_pad($pedido_id, 6, '0', STR_PAD_LEFT), 0, 1);
    
    $pdf->Cell(95, 5, 'Estado: ' . ($ya_facturado ? 'Previamente Facturado' : 'Facturado'), 0, 0);
    $pdf->Cell(95, 5, 'Stock: ' . ($ya_facturado ? 'Ya descontado' : 'Descontado'), 0, 1);
    
    $pdf->Ln(10);

    // Tabla de productos
    $pdf->SetFont('Arial', 'B', 9);
    $pdf->Cell(20, 8, 'Cant.', 1, 0, 'C');
    $pdf->Cell(80, 8, 'Producto', 1, 0, 'C');
    $pdf->Cell(30, 8, 'Precio Unit.', 1, 0, 'C');
    $pdf->Cell(30, 8, 'Subtotal', 1, 1, 'C');

    $pdf->SetFont('Arial', '', 9);
    $total = 0;

    foreach ($detalles as $detalle) {
        $subtotal = $detalle['cantidad'] * $detalle['precio'];
        $total += $subtotal;

        $pdf->Cell(20, 6, $detalle['cantidad'], 1, 0, 'C');
        $pdf->Cell(80, 6, substr($detalle['producto_nombre'], 0, 35), 1, 0, 'L');
        $pdf->Cell(30, 6, '$' . number_format($detalle['precio'], 2), 1, 0, 'R');
        $pdf->Cell(30, 6, '$' . number_format($subtotal, 2), 1, 1, 'R');
    }

    // Total
    $pdf->Ln(5);
    $pdf->SetFont('Arial', 'B', 11);
    $pdf->Cell(130, 8, 'TOTAL:', 1, 0, 'R');
    $pdf->Cell(30, 8, '$' . number_format($total, 2), 1, 1, 'R');

    // Información adicional
    $pdf->Ln(10);
    $pdf->SetFont('Arial', '', 9);
    $pdf->Cell(0, 5, 'Gracias por su compra!', 0, 1, 'C');
    $pdf->Cell(0, 5, 'Esta factura fue generada electronicamente.', 0, 1, 'C');

    // Generar nombre del archivo
    $fecha = date('Y-m-d');
    $nombre_archivo = "Factura_{$numero_factura}_{$fecha}.pdf";

    // Salida del PDF
    $pdf->Output('D', $nombre_archivo);

} catch (Exception $e) {
    // Rollback en caso de error
    if ($pdo->inTransaction()) {
        $pdo->rollback();
    }
    die('Error al generar la factura: ' . $e->getMessage());
}
?>