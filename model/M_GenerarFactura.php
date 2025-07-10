<?php
// Versión mejorada de M_GenerarFactura.php con mejor manejo de buffers
// Limpiar y configurar buffers desde el inicio
while (ob_get_level()) {
    ob_end_clean();
}
ob_start();

// Configurar para evitar salida de errores
error_reporting(0);
ini_set('display_errors', 0);

// Iniciar sesión si no está iniciada
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Verificar si hay sesión activa
if (!isset($_SESSION['sesion_iniciada']) || $_SESSION['sesion_iniciada'] !== "iniciado") {
    ob_end_clean();
    header("Location: index.php?opc=login");
    exit;
}

// Verificar que se recibió el ID del pedido
if (!isset($_GET['pedido_id']) || empty($_GET['pedido_id'])) {
    ob_end_clean();
    header("Location: index.php?opc=gestion_pedidos&error=" . urlencode("ID de pedido no especificado"));
    exit;
}

$pedido_id = (int)$_GET['pedido_id'];

// Validar que el ID sea válido
if ($pedido_id <= 0) {
    ob_end_clean();
    header("Location: index.php?opc=gestion_pedidos&error=" . urlencode("ID de pedido no válido"));
    exit;
}

// Configuración de IVA
define('IVA_PORCENTAJE', 15);

// Función auxiliar para calcular IVA
function calcularIVA($subtotal, $porcentaje = IVA_PORCENTAJE) {
    return $subtotal * ($porcentaje / 100);
}

try {
    // Incluir archivos necesarios
    require_once __DIR__ . '/../config/clavebasededatos.php';
    require_once __DIR__ . '/../fpdf182/fpdf.php';

    // Crear conexión PDO
    $pdo = new PDO("mysql:host=$hostname;dbname=$database;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Comenzar transacción
    $pdo->beginTransaction();

    // Verificar que el pedido existe
    $stmt = $pdo->prepare("
        SELECT p.*, u.nombre as cliente_nombre, u.correo as cliente_email, u.telefono as cliente_telefono, u.cedula as cliente_cedula,
               COALESCE(p.identificacion, u.cedula) as identificacion_cliente,
               COALESCE(p.receptor, u.nombre) as nombre_cliente,
               COALESCE(p.correo, u.correo) as email_cliente,
               COALESCE(p.telefono, u.telefono) as telefono_cliente
        FROM Pedidos p
        JOIN Usuarios u ON p.id_usuario = u.id_usuario
        WHERE p.id_pedido = ?
    ");
    $stmt->execute([$pedido_id]);
    $pedido = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$pedido) {
        throw new Exception('Pedido no encontrado');
    }

    // Verificar si ya existe una factura para este pedido
    $stmt = $pdo->prepare("SELECT id_factura, secuencial FROM Facturas WHERE id_pedido = ? AND activo = 1");
    $stmt->execute([$pedido_id]);
    $factura_existente = $stmt->fetch(PDO::FETCH_ASSOC);

    $numero_factura = '';
    $ya_facturado = false;

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
        throw new Exception('No se encontraron detalles del pedido');
    }

    // Calcular totales
    $subtotal_sin_iva = 0;
    foreach ($detalles as $detalle) {
        $subtotal_sin_iva += $detalle['cantidad'] * $detalle['precio'];
    }
    
    $iva_monto_calculado = calcularIVA($subtotal_sin_iva);
    $total_con_iva = $subtotal_sin_iva + $iva_monto_calculado;

    if ($factura_existente) {
        // Ya existe una factura
        $numero_factura = $factura_existente['secuencial'];
        $ya_facturado = true;
    } else {
        // Crear nueva factura
        $secuencial = date('Ymd') . str_pad($pedido_id, 6, '0', STR_PAD_LEFT);
        
        // Verificar si la tabla tiene las columnas de IVA
        $stmt = $pdo->query("SHOW COLUMNS FROM Facturas LIKE 'subtotal'");
        $tiene_columnas_iva = $stmt->rowCount() > 0;
        
        if ($tiene_columnas_iva) {
            $stmt = $pdo->prepare("
                INSERT INTO Facturas (id_pedido, secuencial, fecha_emision, subtotal, iva_porcentaje, iva_monto, total, activo) 
                VALUES (?, ?, NOW(), ?, ?, ?, ?, 1)
            ");
            $stmt->execute([$pedido_id, $secuencial, $subtotal_sin_iva, IVA_PORCENTAJE, $iva_monto_calculado, $total_con_iva]);
        } else {
            $stmt = $pdo->prepare("
                INSERT INTO Facturas (id_pedido, secuencial, fecha_emision, activo) 
                VALUES (?, ?, NOW(), 1)
            ");
            $stmt->execute([$pedido_id, $secuencial]);
        }
        
        $numero_factura = $secuencial;

        // Verificar stock y descontarlo si es nueva factura
        foreach ($detalles as $detalle) {
            if ($detalle['stock'] < $detalle['cantidad']) {
                throw new Exception("Stock insuficiente para el producto: {$detalle['producto_nombre']}");
            }
        }

        // Descontar stock
        foreach ($detalles as $detalle) {
            $stmt = $pdo->prepare("UPDATE Productos SET stock = stock - ? WHERE id_producto = ?");
            $stmt->execute([$detalle['cantidad'], $detalle['id_producto']]);
        }

        // Actualizar estado del pedido
        $stmt = $pdo->prepare("UPDATE Pedidos SET estado = 'facturado' WHERE id_pedido = ?");
        $stmt->execute([$pedido_id]);
    }

    // Confirmar transacción
    $pdo->commit();

    // Limpiar completamente el buffer antes de crear el PDF
    while (ob_get_level()) {
        ob_end_clean();
    }

    // Crear PDF
    $pdf = new FPDF();
    $pdf->AddPage();
    
    // Encabezado principal
    $pdf->SetFont('Arial', 'B', 18);
    $pdf->Cell(0, 10, 'ASERRADERO INDUSTRIAL', 0, 1, 'C');
    
    $pdf->SetFont('Arial', 'B', 14);
    $pdf->Cell(0, 8, 'FACTURA', 0, 1, 'C');
    $pdf->Ln(5);

    // Información de la empresa en recuadro izquierdo
    $pdf->Rect(10, 35, 90, 40);
    $pdf->SetXY(12, 37);
    $pdf->SetFont('Arial', 'B', 10);
    $pdf->Cell(0, 5, 'ASERRADERO INDUSTRIAL', 0, 1);
    $pdf->SetX(12);
    $pdf->SetFont('Arial', '', 9);
    $pdf->Cell(0, 5, 'RUC: 1234567890001', 0, 1);
    $pdf->SetX(12);
    $pdf->Cell(0, 5, 'Direccion: Av. Principal 123', 0, 1);
    $pdf->SetX(12);
    $pdf->Cell(0, 5, 'Telefono: +593 123 456 789', 0, 1);
    $pdf->SetX(12);
    $pdf->Cell(0, 5, 'Email: info@aserradero.com', 0, 1);

    // Información de la factura en recuadro derecho
    $pdf->Rect(110, 35, 90, 40);
    $pdf->SetXY(112, 37);
    $pdf->SetFont('Arial', 'B', 10);
    $pdf->Cell(0, 5, 'FACTURA', 0, 1);
    $pdf->SetX(112);
    $pdf->SetFont('Arial', '', 9);
    $pdf->Cell(0, 5, 'No. ' . $numero_factura, 0, 1);
    $pdf->SetX(112);
    $pdf->Cell(0, 5, 'FECHA: ' . date('d/m/Y'), 0, 1);
    $pdf->SetX(112);
    $pdf->Cell(0, 5, 'AMBIENTE: PRODUCCION', 0, 1);
    $pdf->SetX(112);
    $pdf->Cell(0, 5, 'EMISION: NORMAL', 0, 1);

    $pdf->Ln(15);

    // Información del cliente en formato tabla
    $pdf->SetFont('Arial', 'B', 9);
    $pdf->Cell(40, 6, 'Razon Social / Nombres:', 1, 0, 'L');
    $pdf->SetFont('Arial', '', 9);
    $pdf->Cell(100, 6, strtoupper($pedido['nombre_cliente']), 1, 0, 'L');
    $pdf->SetFont('Arial', 'B', 9);
    $pdf->Cell(25, 6, 'Identificacion:', 1, 0, 'L');
    $pdf->SetFont('Arial', '', 9);
    $pdf->Cell(25, 6, ($pedido['identificacion_cliente'] ?? 'N/A'), 1, 1, 'L');

    $pdf->SetFont('Arial', 'B', 9);
    $pdf->Cell(40, 6, 'Fecha:', 1, 0, 'L');
    $pdf->SetFont('Arial', '', 9);
    $pdf->Cell(30, 6, date('d/m/Y'), 1, 0, 'L');
    $pdf->SetFont('Arial', 'B', 9);
    $pdf->Cell(30, 6, 'Direccion:', 1, 0, 'L');
    $pdf->SetFont('Arial', '', 9);
    $pdf->Cell(40, 6, 'N/A', 1, 0, 'L');
    $pdf->SetFont('Arial', 'B', 9);
    $pdf->Cell(25, 6, 'Telefono:', 1, 0, 'L');
    $pdf->SetFont('Arial', '', 9);
    $pdf->Cell(25, 6, ($pedido['telefono_cliente'] ?? 'N/A'), 1, 1, 'L');

    $pdf->Ln(5);

    // Tabla de productos con encabezados similares a la factura real
    $pdf->SetFont('Arial', 'B', 8);
    $pdf->Cell(15, 7, 'Cod.', 1, 0, 'C');
    $pdf->Cell(15, 7, 'Cod. Auxiliar', 1, 0, 'C');
    $pdf->Cell(15, 7, 'Cantidad', 1, 0, 'C');
    $pdf->Cell(60, 7, 'Descripcion', 1, 0, 'C');
    $pdf->Cell(25, 7, 'Detalle Adicional', 1, 0, 'C');
    $pdf->Cell(20, 7, 'Precio Unitario', 1, 0, 'C');
    $pdf->Cell(15, 7, 'Subsidio', 1, 0, 'C');
    $pdf->Cell(20, 7, 'Precio Total', 1, 1, 'C');

    $pdf->SetFont('Arial', '', 8);
    $subtotal_general = 0;
    $contador = 1;

    foreach ($detalles as $detalle) {
        $subtotal = $detalle['cantidad'] * $detalle['precio'];
        $subtotal_general += $subtotal;

        // Generar código simple
        $codigo = 'PROD' . str_pad($detalle['id_producto'], 3, '0', STR_PAD_LEFT);
        
        $pdf->Cell(15, 6, $codigo, 1, 0, 'C');
        $pdf->Cell(15, 6, $codigo, 1, 0, 'C');
        $pdf->Cell(15, 6, number_format($detalle['cantidad'], 2), 1, 0, 'C');
        $pdf->Cell(60, 6, substr(strtoupper($detalle['producto_nombre']), 0, 30), 1, 0, 'L');
        $pdf->Cell(25, 6, '', 1, 0, 'C'); // Detalle adicional vacío
        $pdf->Cell(20, 6, number_format($detalle['precio'], 2), 1, 0, 'R');
        $pdf->Cell(15, 6, '0.00', 1, 0, 'R'); // Subsidio
        $pdf->Cell(20, 6, number_format($subtotal, 2), 1, 1, 'R');
    }

    // Cálculos de totales
    $iva_porcentaje = IVA_PORCENTAJE;
    $iva_monto = calcularIVA($subtotal_general);
    $total_final = $subtotal_general + $iva_monto;

    $pdf->Ln(10);

    // Información adicional en lado izquierdo
    $pdf->SetFont('Arial', 'B', 9);
    $pdf->Cell(100, 6, 'Informacion Adicional', 1, 1, 'C');
    
    $pdf->SetFont('Arial', '', 8);
    $pdf->Cell(30, 5, 'Email Cliente:', 1, 0, 'L');
    $pdf->Cell(70, 5, $pedido['email_cliente'], 1, 1, 'L');
    
    $pdf->Cell(30, 5, 'Pedido No:', 1, 0, 'L');
    $pdf->Cell(70, 5, 'PED-' . str_pad($pedido_id, 6, '0', STR_PAD_LEFT), 1, 1, 'L');
    
    $pdf->Cell(30, 5, 'Estado:', 1, 0, 'L');
    $pdf->Cell(70, 5, ($ya_facturado ? 'RE-IMPRESION' : 'ORIGINAL'), 1, 1, 'L');

    // Posicionar totales en el lado derecho
    $pdf->SetXY(110, $pdf->GetY() - 18);
    
    // Tabla de totales como en la factura real
    $pdf->SetFont('Arial', 'B', 9);
    
    // Subtotal 0%
    $pdf->Cell(50, 5, 'SUBTOTAL 0%', 1, 0, 'L');
    $pdf->Cell(30, 5, number_format($subtotal_general, 2), 1, 1, 'R');
    $pdf->SetX(110);
    
    // Subtotal sin impuestos
    $pdf->Cell(50, 5, 'SUBTOTAL SIN IMPUESTOS', 1, 0, 'L');
    $pdf->Cell(30, 5, number_format($subtotal_general, 2), 1, 1, 'R');
    $pdf->SetX(110);
    
    // Total descuento
    $pdf->Cell(50, 5, 'TOTAL DESCUENTO', 1, 0, 'L');
    $pdf->Cell(30, 5, '0.00', 1, 1, 'R');
    $pdf->SetX(110);
    
    // IVA
    $pdf->Cell(50, 5, "IVA {$iva_porcentaje}%", 1, 0, 'L');
    $pdf->Cell(30, 5, number_format($iva_monto, 2), 1, 1, 'R');
    $pdf->SetX(110);
    
    // Valor total
    $pdf->SetFont('Arial', 'B', 10);
    $pdf->Cell(50, 6, 'VALOR TOTAL', 1, 0, 'L');
    $pdf->Cell(30, 6, number_format($total_final, 2), 1, 1, 'R');

    // Información final
    $pdf->Ln(10);
    $pdf->SetFont('Arial', '', 8);
    $pdf->Cell(0, 4, 'Esta factura fue generada electronicamente por el Sistema de Aserradero Industrial', 0, 1, 'C');
    $pdf->Cell(0, 4, 'Fecha y hora de generacion: ' . date('d/m/Y H:i:s'), 0, 1, 'C');
    if ($ya_facturado) {
        $pdf->Cell(0, 4, 'NOTA: Esta es una re-impresion de la factura original. El stock ya fue descontado previamente.', 0, 1, 'C');
    }

    // Generar nombre del archivo
    $fecha = date('Y-m-d');
    $nombre_archivo = "Factura_{$numero_factura}_{$fecha}.pdf";

    // Salida del PDF
    $pdf->Output('D', $nombre_archivo);

} catch (Exception $e) {
    // Rollback en caso de error
    if (isset($pdo) && $pdo->inTransaction()) {
        $pdo->rollback();
    }
    
    // Limpiar buffer
    while (ob_get_level()) {
        ob_end_clean();
    }
    
    // Redirigir con mensaje de error
    $error_message = urlencode($e->getMessage());
    header("Location: index.php?opc=gestion_pedidos&error=$error_message");
    exit;
}
?>
