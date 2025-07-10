<?php
// Habilitar reporte de errores para debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Iniciar sesión si no está iniciada
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Verificar si hay sesión activa
if (!isset($_SESSION['sesion_iniciada']) || $_SESSION['sesion_iniciada'] !== "iniciado") {
    die('Acceso denegado: Sesión no válida');
}

// Verificar que se incluyan correctamente los archivos
if (!file_exists('./config/Cconexion.php')) {
    die('Error: No se puede encontrar el archivo de configuración');
}

require_once './config/Cconexion.php';
require_once './config/clavebasededatos.php';

if (!file_exists('./fpdf182/fpdf.php')) {
    die('Error: No se puede encontrar la librería FPDF');
}
require_once './fpdf182/fpdf.php';

if (!isset($_GET['pedido_id'])) {
    die('Error: ID de pedido no especificado');
}

$pedido_id = (int)$_GET['pedido_id'];

try {
    if (!isset($hostname) || !isset($username) || !isset($password) || !isset($database)) {
        die('Error: Variables de conexión no definidas');
    }
    
    $pdo = new PDO("mysql:host=$hostname;dbname=$database;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "<!-- Debug: Conexión establecida -->\n";

    // Verificar que el pedido existe y está aceptado
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

    // Obtener detalles del pedido
    $stmt = $pdo->prepare("
        SELECT pd.*, pr.nombre as producto_nombre, pd.precio
        FROM Pedido_detalles pd
        JOIN Productos pr ON pd.id_producto = pr.id_producto
        WHERE pd.id_pedido = ?
    ");
    $stmt->execute([$pedido_id]);
    $detalles = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (empty($detalles)) {
        die('No se encontraron detalles del pedido');
    }

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
    $pdf->Cell(95, 5, 'Nombre: ' . ($pedido['receptor'] ?? $pedido['cliente_nombre']), 0, 0);
    $pdf->Cell(95, 5, 'Factura No: FAC-' . str_pad($pedido_id, 6, '0', STR_PAD_LEFT), 0, 1);
    
    $pdf->Cell(95, 5, 'Email: ' . ($pedido['correo'] ?? $pedido['cliente_email']), 0, 0);
    $pdf->Cell(95, 5, 'Fecha: ' . date('d/m/Y', strtotime($pedido['creado_en'])), 0, 1);
    
    $pdf->Cell(95, 5, 'Telefono: ' . ($pedido['telefono'] ?? $pedido['cliente_telefono'] ?? 'N/A'), 0, 0);
    $pdf->Cell(95, 5, 'Estado: ' . ucfirst($pedido['estado']), 0, 1);
    
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
    $nombre_archivo = "Factura_Pedido_{$pedido_id}_{$fecha}.pdf";

    // Salida del PDF
    $pdf->Output('D', $nombre_archivo);

} catch (Exception $e) {
    die('Error al generar la factura: ' . $e->getMessage());
}
?>
