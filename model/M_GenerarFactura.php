<?php
// Incluir la conexión y FPDF
include_once './config/Cconexion.php';
require_once('./fpdf182/fpdf.php');

// Obtener el ID del pedido
$id_pedido = $_GET['id'] ?? 0;

if (!$id_pedido) {
    echo "<script>alert('ID de pedido no válido.'); window.location.href = '../index.php?opc=listar_ventas';</script>";
    exit;
}

try {
    // Obtener datos de la venta
    $sql_venta = "SELECT 
                    p.id_pedido,
                    p.total,
                    p.estado,
                    p.creado_en,
                    u.nombre as cliente_nombre,
                    u.correo as cliente_correo,
                    u.cedula as cliente_cedula
                  FROM Pedidos p
                  LEFT JOIN Usuarios u ON p.id_usuario = u.id_usuario
                  WHERE p.id_pedido = $id_pedido AND p.activo = 1";

    $resultado_venta = mysqli_query($conexion, $sql_venta);
    $venta = mysqli_fetch_assoc($resultado_venta);

    if (!$venta) {
        throw new Exception("Venta no encontrada.");
    }

    // Obtener detalles de la venta
    $sql_detalles = "SELECT 
                       pd.cantidad,
                       pd.precio,
                       pd.subtotal,
                       pr.nombre as producto_nombre,
                       pr.descripcion as producto_descripcion
                     FROM Pedido_detalles pd
                     LEFT JOIN Productos pr ON pd.id_producto = pr.id_producto
                     WHERE pd.id_pedido = $id_pedido";

    $resultado_detalles = mysqli_query($conexion, $sql_detalles);
    $detalles = mysqli_fetch_all($resultado_detalles, MYSQLI_ASSOC);

    // Verificar si ya existe una factura para este pedido
    $sql_factura_existe = "SELECT id_factura FROM Facturas WHERE id_pedido = $id_pedido";
    $resultado_factura = mysqli_query($conexion, $sql_factura_existe);
    
    if (mysqli_num_rows($resultado_factura) == 0) {
        // Crear registro de factura
        $secuencial = date('Ymd') . str_pad($id_pedido, 6, '0', STR_PAD_LEFT);
        $sql_crear_factura = "INSERT INTO Facturas (id_pedido, secuencial, fecha_emision, activo) 
                             VALUES ($id_pedido, '$secuencial', NOW(), 1)";
        
        if (!mysqli_query($conexion, $sql_crear_factura)) {
            throw new Exception("Error al crear la factura: " . mysqli_error($conexion));
        }
        
        $id_factura = mysqli_insert_id($conexion);
    } else {
        $factura_existente = mysqli_fetch_assoc($resultado_factura);
        $id_factura = $factura_existente['id_factura'];
        $secuencial = date('Ymd') . str_pad($id_pedido, 6, '0', STR_PAD_LEFT);
    }

    // Crear PDF
    $pdf = new FPDF();
    $pdf->AddPage();
    
    // Encabezado
    $pdf->SetFont('Arial', 'B', 16);
    $pdf->Cell(0, 10, 'ASERRADERO - FACTURA', 0, 1, 'C');
    $pdf->Ln(5);
    
    // Información de la empresa
    $pdf->SetFont('Arial', '', 10);
    $pdf->Cell(0, 6, 'Direccion: Calle Principal #123', 0, 1);
    $pdf->Cell(0, 6, 'Telefono: (02) 123-4567', 0, 1);
    $pdf->Cell(0, 6, 'Email: info@aserradero.com', 0, 1);
    $pdf->Ln(10);
    
    // Información de la factura
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(100, 8, 'DATOS DE LA FACTURA', 1, 0);
    $pdf->Cell(90, 8, 'DATOS DEL CLIENTE', 1, 1);
    
    $pdf->SetFont('Arial', '', 10);
    $pdf->Cell(100, 6, 'Numero: ' . $secuencial, 1, 0);
    $pdf->Cell(90, 6, 'Nombre: ' . $venta['cliente_nombre'], 1, 1);
    
    $pdf->Cell(100, 6, 'Fecha: ' . date('d/m/Y H:i', strtotime($venta['creado_en'])), 1, 0);
    $pdf->Cell(90, 6, 'Cedula: ' . $venta['cliente_cedula'], 1, 1);
    
    $pdf->Cell(100, 6, 'Estado: ' . ucfirst($venta['estado']), 1, 0);
    $pdf->Cell(90, 6, 'Email: ' . $venta['cliente_correo'], 1, 1);
    
    $pdf->Ln(10);
    
    // Encabezado de la tabla de productos
    $pdf->SetFont('Arial', 'B', 10);
    $pdf->Cell(80, 8, 'PRODUCTO', 1, 0, 'C');
    $pdf->Cell(25, 8, 'PRECIO', 1, 0, 'C');
    $pdf->Cell(25, 8, 'CANTIDAD', 1, 0, 'C');
    $pdf->Cell(30, 8, 'SUBTOTAL', 1, 1, 'C');
    
    // Contenido de la tabla
    $pdf->SetFont('Arial', '', 9);
    foreach ($detalles as $detalle) {
        $pdf->Cell(80, 6, substr($detalle['producto_nombre'], 0, 35), 1, 0);
        $pdf->Cell(25, 6, '$' . number_format($detalle['precio'], 2), 1, 0, 'R');
        $pdf->Cell(25, 6, $detalle['cantidad'], 1, 0, 'C');
        $pdf->Cell(30, 6, '$' . number_format($detalle['subtotal'], 2), 1, 1, 'R');
    }
    
    // Total
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(130, 10, 'TOTAL:', 1, 0, 'R');
    $pdf->Cell(30, 10, '$' . number_format($venta['total'], 2), 1, 1, 'R');
    
    // Pie de página
    $pdf->Ln(10);
    $pdf->SetFont('Arial', 'I', 8);
    $pdf->Cell(0, 6, 'Gracias por su compra', 0, 1, 'C');
    $pdf->Cell(0, 6, 'Esta es una factura generada electronicamente', 0, 1, 'C');
    
    // Salida del PDF
    $filename = 'Factura_' . $secuencial . '.pdf';
    $pdf->Output('D', $filename);

} catch (Exception $e) {
    echo "<script>
            alert('Error al generar la factura: " . addslashes($e->getMessage()) . "');
            window.location.href = '../index.php?opc=listar_ventas';
          </script>";
}

// Cerrar conexión
mysqli_close($conexion);
?>
