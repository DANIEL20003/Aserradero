<?php
// Debug para M_GenerarFactura.php
echo "=== DEBUG GENERADOR DE FACTURAS ===<br><br>";

// 1. Verificar PHP
echo "1. PHP funcionando: ✓<br>";

// 2. Verificar sesiones
try {
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    echo "2. Sesiones iniciadas: ✓<br>";
} catch (Exception $e) {
    echo "2. ERROR en sesiones: " . $e->getMessage() . "<br>";
}

// 3. Verificar archivos de configuración
$base_dir = dirname(__DIR__);
echo "3. Directorio base: " . $base_dir . "<br>";

$files_to_check = [
    'config/Cconexion.php',
    'config/clavebasededatos.php', 
    'fpdf182/fpdf.php'
];

foreach ($files_to_check as $file) {
    $full_path = $base_dir . '/' . $file;
    if (file_exists($full_path)) {
        echo "   ✓ $file existe<br>";
    } else {
        echo "   ✗ $file NO existe en $full_path<br>";
    }
}

// 4. Verificar parámetros
if (isset($_GET['pedido_id'])) {
    echo "4. Pedido ID recibido: " . $_GET['pedido_id'] . "<br>";
} else {
    echo "4. ✗ NO se recibió pedido_id<br>";
}

// 5. Verificar sesión de usuario
if (isset($_SESSION['sesion_iniciada'])) {
    echo "5. Estado de sesión: " . $_SESSION['sesion_iniciada'] . "<br>";
    if (isset($_SESSION['nombre'])) {
        echo "   Usuario: " . $_SESSION['nombre'] . "<br>";
    }
} else {
    echo "5. ✗ No hay sesión iniciada<br>";
}

// 6. Intentar incluir archivos
try {
    echo "<br>6. Intentando incluir archivos...<br>";
    
    if (file_exists('./config/Cconexion.php')) {
        require_once './config/Cconexion.php';
        echo "   ✓ Cconexion.php (desde raíz)<br>";
    } elseif (file_exists('../config/Cconexion.php')) {
        require_once '../config/Cconexion.php';
        echo "   ✓ Cconexion.php (desde subdirectorio)<br>";
    } else {
        echo "   ✗ No se encuentra Cconexion.php<br>";
    }
    
    if (file_exists('./config/clavebasededatos.php')) {
        require_once './config/clavebasededatos.php';
        echo "   ✓ clavebasededatos.php (desde raíz)<br>";
    } elseif (file_exists('../config/clavebasededatos.php')) {
        require_once '../config/clavebasededatos.php';
        echo "   ✓ clavebasededatos.php (desde subdirectorio)<br>";
    } else {
        echo "   ✗ No se encuentra clavebasededatos.php<br>";
    }
    
} catch (Exception $e) {
    echo "   ✗ ERROR al incluir archivos: " . $e->getMessage() . "<br>";
}

// 7. Verificar variables de conexión
echo "<br>7. Variables de conexión:<br>";
if (isset($hostname)) echo "   hostname: $hostname<br>";
if (isset($username)) echo "   username: $username<br>";
if (isset($database)) echo "   database: $database<br>";
if (isset($password)) echo "   password: [definida]<br>";

// 8. Intentar conexión a BD
if (isset($hostname) && isset($username) && isset($password) && isset($database)) {
    try {
        $pdo = new PDO("mysql:host=$hostname;dbname=$database;charset=utf8", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        echo "8. ✓ Conexión a base de datos exitosa<br>";
        
        // Verificar si existe el pedido
        if (isset($_GET['pedido_id'])) {
            $pedido_id = (int)$_GET['pedido_id'];
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM Pedidos WHERE id_pedido = ?");
            $stmt->execute([$pedido_id]);
            $count = $stmt->fetchColumn();
            echo "   Pedido ID $pedido_id " . ($count > 0 ? "✓ existe" : "✗ no existe") . "<br>";
        }
        
    } catch (Exception $e) {
        echo "8. ✗ ERROR en conexión BD: " . $e->getMessage() . "<br>";
    }
} else {
    echo "8. ✗ Variables de conexión no definidas<br>";
}

echo "<br>=== FIN DEBUG ===";
?>
