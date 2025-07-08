<?php
// Script para insertar productos de ejemplo en la base de datos
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'config/Cconexion.php';

echo "<h2>Insertar Productos de Ejemplo</h2>";

try {
    if (!$conexion) {
        throw new Exception("Error de conexión: " . mysqli_connect_error());
    }
    
    // Primero, verificar si las tablas existen
    $check_productos = mysqli_query($conexion, "SHOW TABLES LIKE 'Productos'");
    if (mysqli_num_rows($check_productos) == 0) {
        echo "<p><strong>❌ La tabla 'Productos' no existe. Creando tablas...</strong></p>";
        
        // Crear tabla Categorias
        $sql_categorias = "CREATE TABLE IF NOT EXISTS Categorias (
            id_categoria INT AUTO_INCREMENT PRIMARY KEY,
            nombre VARCHAR(100) NOT NULL,
            descripcion TEXT,
            activo TINYINT(1) DEFAULT 1,
            fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )";
        
        if (mysqli_query($conexion, $sql_categorias)) {
            echo "<p>✅ Tabla 'Categorias' creada</p>";
        } else {
            throw new Exception("Error creando tabla Categorias: " . mysqli_error($conexion));
        }
        
        // Crear tabla Productos
        $sql_productos = "CREATE TABLE IF NOT EXISTS Productos (
            id_producto INT AUTO_INCREMENT PRIMARY KEY,
            nombre VARCHAR(150) NOT NULL,
            descripcion TEXT,
            precio_compra DECIMAL(10,2),
            precio_venta DECIMAL(10,2) NOT NULL,
            stock INT DEFAULT 0,
            stock_minimo INT DEFAULT 5,
            imagen VARCHAR(500),
            id_categoria INT,
            activo TINYINT(1) DEFAULT 1,
            fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (id_categoria) REFERENCES Categorias(id_categoria)
        )";
        
        if (mysqli_query($conexion, $sql_productos)) {
            echo "<p>✅ Tabla 'Productos' creada</p>";
        } else {
            throw new Exception("Error creando tabla Productos: " . mysqli_error($conexion));
        }
    }
    
    // Verificar si ya hay categorías, si no, insertar algunas
    $check_cats = mysqli_query($conexion, "SELECT COUNT(*) as total FROM Categorias");
    $cat_count = mysqli_fetch_assoc($check_cats);
    
    if ($cat_count['total'] == 0) {
        echo "<h3>Insertando categorías de ejemplo...</h3>";
        
        $categorias = [
            ['Maderas Duras', 'Maderas de alta resistencia y durabilidad'],
            ['Maderas Blandas', 'Maderas para construcción general'],
            ['Tableros', 'Tableros y paneles de madera'],
            ['Vigas y Estructurales', 'Elementos estructurales de madera'],
            ['Decorativas', 'Maderas para decoración y acabados']
        ];
        
        foreach ($categorias as $cat) {
            $sql = "INSERT INTO Categorias (nombre, descripcion) VALUES (?, ?)";
            $stmt = mysqli_prepare($conexion, $sql);
            mysqli_stmt_bind_param($stmt, "ss", $cat[0], $cat[1]);
            
            if (mysqli_stmt_execute($stmt)) {
                echo "<p>✅ Categoría insertada: {$cat[0]}</p>";
            } else {
                echo "<p>❌ Error insertando categoría {$cat[0]}: " . mysqli_error($conexion) . "</p>";
            }
        }
    }
    
    // Verificar si ya hay productos, si no, insertar algunos
    $check_prods = mysqli_query($conexion, "SELECT COUNT(*) as total FROM Productos");
    $prod_count = mysqli_fetch_assoc($check_prods);
    
    if ($prod_count['total'] == 0) {
        echo "<h3>Insertando productos de ejemplo...</h3>";
        
        $productos = [
            ['Tabla de Pino 2x4"', 'Tabla de pino tratado, ideal para construcción', 15.50, 25.00, 150, 1],
            ['Viga de Roble 4x6"', 'Viga estructural de roble americano', 65.00, 95.00, 25, 4],
            ['Tablón de Cedro', 'Tablón aromático de cedro rojo', 35.75, 55.00, 40, 5],
            ['Contrachapado 18mm', 'Tablero contrachapado para construcción', 45.00, 68.00, 80, 3],
            ['Listón de Pino', 'Listones decorativos de pino', 8.50, 15.00, 200, 2],
            ['Viga Laminada 6x8"', 'Viga laminada encolada de alta resistencia', 120.00, 180.00, 15, 4],
            ['Tabla de Eucalipto', 'Madera de eucalipto seca al horno', 18.00, 28.50, 100, 1],
            ['Panel OSB 15mm', 'Panel de virutas orientadas', 32.00, 48.00, 60, 3]
        ];
        
        foreach ($productos as $prod) {
            $sql = "INSERT INTO Productos (nombre, descripcion, precio_compra, precio_venta, stock, id_categoria) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = mysqli_prepare($conexion, $sql);
            mysqli_stmt_bind_param($stmt, "ssddii", $prod[0], $prod[1], $prod[2], $prod[3], $prod[4], $prod[5]);
            
            if (mysqli_stmt_execute($stmt)) {
                echo "<p>✅ Producto insertado: {$prod[0]} - $" . number_format($prod[3], 2) . "</p>";
            } else {
                echo "<p>❌ Error insertando producto {$prod[0]}: " . mysqli_error($conexion) . "</p>";
            }
        }
    } else {
        echo "<p>ℹ️ Ya existen {$prod_count['total']} productos en la base de datos</p>";
    }
    
    // Mostrar resumen final
    echo "<h3>Resumen de la base de datos:</h3>";
    $result = mysqli_query($conexion, "SELECT COUNT(*) as total FROM Categorias WHERE activo = 1");
    $cats = mysqli_fetch_assoc($result);
    echo "<p>Categorías activas: {$cats['total']}</p>";
    
    $result = mysqli_query($conexion, "SELECT COUNT(*) as total FROM Productos WHERE activo = 1");
    $prods = mysqli_fetch_assoc($result);
    echo "<p>Productos activos: {$prods['total']}</p>";
    
    echo "<h3>✅ Proceso completado. Ahora puedes probar tu sitio web.</h3>";
    echo "<p><a href='index.php' target='_blank'>Ver sitio web</a></p>";
    echo "<p><a href='debug_db.php' target='_blank'>Debug de base de datos</a></p>";
    
} catch (Exception $e) {
    echo "<p><strong>❌ Error:</strong> " . $e->getMessage() . "</p>";
}

if (isset($conexion) && $conexion) {
    mysqli_close($conexion);
}
?>
