<?php
// Archivo de prueba para verificar la funcionalidad de facturación
// Ejecutar desde la raíz del proyecto: php test_facturacion.php

require_once './config/Cconexion.php';
require_once './config/clavebasededatos.php';

echo "=== PRUEBA DE FUNCIONALIDAD DE FACTURACIÓN ===\n";

try {
    $pdo = new PDO("mysql:host=$hostname;dbname=$database;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Verificar que existe la tabla Facturas
    $stmt = $pdo->query("SHOW TABLES LIKE 'Facturas'");
    if ($stmt->rowCount() > 0) {
        echo "✓ Tabla Facturas existe\n";
        
        // Mostrar estructura de la tabla
        $stmt = $pdo->query("DESCRIBE Facturas");
        echo "\n--- Estructura de la tabla Facturas ---\n";
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "{$row['Field']}: {$row['Type']} {$row['Null']} {$row['Key']} {$row['Default']}\n";
        }
    } else {
        echo "✗ Tabla Facturas NO existe. Ejecutar migración:\n";
        echo "mysql -u$username -p$password $database < database/migration_facturas.sql\n";
    }

    // Verificar que existe la tabla Pedidos
    $stmt = $pdo->query("SHOW TABLES LIKE 'Pedidos'");
    if ($stmt->rowCount() > 0) {
        echo "\n✓ Tabla Pedidos existe\n";
        
        // Contar pedidos por estado
        $stmt = $pdo->query("SELECT estado, COUNT(*) as cantidad FROM Pedidos GROUP BY estado");
        echo "\n--- Pedidos por estado ---\n";
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "{$row['estado']}: {$row['cantidad']}\n";
        }
    } else {
        echo "\n✗ Tabla Pedidos NO existe\n";
    }

    // Verificar que existe la tabla Pedido_detalles
    $stmt = $pdo->query("SHOW TABLES LIKE 'Pedido_detalles'");
    if ($stmt->rowCount() > 0) {
        echo "\n✓ Tabla Pedido_detalles existe\n";
    } else {
        echo "\n✗ Tabla Pedido_detalles NO existe\n";
    }

    // Si hay facturas, mostrar algunas
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM Facturas WHERE activo = 1");
    $total_facturas = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    echo "\n--- Total de facturas activas: $total_facturas ---\n";

    if ($total_facturas > 0) {
        $stmt = $pdo->query("
            SELECT f.*, p.estado as estado_pedido, u.nombre as cliente_nombre
            FROM Facturas f
            JOIN Pedidos p ON f.id_pedido = p.id_pedido
            JOIN Usuarios u ON p.id_usuario = u.id_usuario
            WHERE f.activo = 1
            ORDER BY f.fecha_emision DESC
            LIMIT 5
        ");
        
        echo "\n--- Últimas 5 facturas ---\n";
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "Factura {$row['secuencial']} - Pedido {$row['id_pedido']} - Cliente: {$row['cliente_nombre']} - Estado: {$row['estado_pedido']} - Fecha: {$row['fecha_emision']}\n";
        }
    }

    // Verificar archivos críticos
    echo "\n--- Verificación de archivos ---\n";
    $archivos_criticos = [
        './model/M_GenerarFactura.php',
        './controller/Copciones.php',
        './view/V_GestionPedidos.php',
        './fpdf182/fpdf.php'
    ];

    foreach ($archivos_criticos as $archivo) {
        if (file_exists($archivo)) {
            echo "✓ $archivo existe\n";
        } else {
            echo "✗ $archivo NO existe\n";
        }
    }

    echo "\n=== FIN DE LA PRUEBA ===\n";

} catch (Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
}
?>
