<?php
// Script para crear las tablas necesarias basadas en la estructura de los modelos
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h2>Crear Tablas en Base de Datos - Aserradero</h2>";

try {
    require_once 'config/Cconexion.php';
    
    if (!$conexion) {
        throw new Exception("Error de conexión: " . mysqli_connect_error());
    }
    
    echo "<p>✅ Conexión exitosa</p>";
    
    // Crear tabla Categorias (basada en M_ListarCategorias.php)
    echo "<h3>Creando tabla Categorias...</h3>";
    $sql_categorias = "CREATE TABLE IF NOT EXISTS Categorias (
        id_categoria INT AUTO_INCREMENT PRIMARY KEY,
        descripcion VARCHAR(200) NOT NULL,
        activo TINYINT(1) DEFAULT 1,
        fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";
    
    if (mysqli_query($conexion, $sql_categorias)) {
        echo "<p>✅ Tabla 'Categorias' creada/verificada correctamente</p>";
    } else {
        throw new Exception("Error creando tabla Categorias: " . mysqli_error($conexion));
    }
    
    // Crear tabla Proveedores (requerida por M_ListarProductos.php)
    echo "<h3>Creando tabla Proveedores...</h3>";
    $sql_proveedores = "CREATE TABLE IF NOT EXISTS Proveedores (
        id_proveedor INT AUTO_INCREMENT PRIMARY KEY,
        descripcion VARCHAR(200) NOT NULL,
        telefono VARCHAR(15),
        email VARCHAR(100),
        direccion TEXT,
        activo TINYINT(1) DEFAULT 1,
        fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";
    
    if (mysqli_query($conexion, $sql_proveedores)) {
        echo "<p>✅ Tabla 'Proveedores' creada/verificada correctamente</p>";
    } else {
        throw new Exception("Error creando tabla Proveedores: " . mysqli_error($conexion));
    }
    
    // Crear tabla Productos (basada en M_ListarProductos.php)
    echo "<h3>Creando tabla Productos...</h3>";
    $sql_productos = "CREATE TABLE IF NOT EXISTS Productos (
        id_producto INT AUTO_INCREMENT PRIMARY KEY,
        nombre VARCHAR(200) NOT NULL,
        descripcion TEXT,
        imagen_url VARCHAR(500),
        precio DECIMAL(10,2) NOT NULL DEFAULT 0.00,
        stock INT DEFAULT 0,
        stock_minimo INT DEFAULT 5,
        id_categoria INT,
        id_proveedor INT,
        activo TINYINT(1) DEFAULT 1,
        fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        INDEX idx_categoria (id_categoria),
        INDEX idx_proveedor (id_proveedor),
        INDEX idx_activo (activo),
        FOREIGN KEY (id_categoria) REFERENCES Categorias(id_categoria) ON DELETE SET NULL,
        FOREIGN KEY (id_proveedor) REFERENCES Proveedores(id_proveedor) ON DELETE SET NULL
    )";
    
    if (mysqli_query($conexion, $sql_productos)) {
        echo "<p>✅ Tabla 'Productos' creada/verificada correctamente</p>";
    } else {
        throw new Exception("Error creando tabla Productos: " . mysqli_error($conexion));
    }
    
    // Insertar datos de ejemplo si las tablas están vacías
    $check_cats = mysqli_query($conexion, "SELECT COUNT(*) as total FROM Categorias");
    $cat_count = mysqli_fetch_assoc($check_cats);
    
    if ($cat_count['total'] == 0) {
        echo "<h3>Insertando categorías de ejemplo...</h3>";
        
        $categorias = [
            'Maderas Duras - Roble, Caoba, Nogal',
            'Maderas Blandas - Pino, Abeto, Cedro', 
            'Tableros - Contrachapado, MDF, OSB',
            'Vigas Estructurales - Laminadas y Macizas',
            'Listones y Molduras - Decorativas'
        ];
        
        foreach ($categorias as $cat) {
            $sql = "INSERT INTO Categorias (descripcion) VALUES (?)";
            $stmt = mysqli_prepare($conexion, $sql);
            mysqli_stmt_bind_param($stmt, "s", $cat);
            
            if (mysqli_stmt_execute($stmt)) {
                echo "<p>✅ Categoría insertada: {$cat}</p>";
            }
        }
    }
    
    // Insertar proveedor de ejemplo
    $check_provs = mysqli_query($conexion, "SELECT COUNT(*) as total FROM Proveedores");
    $prov_count = mysqli_fetch_assoc($check_provs);
    
    if ($prov_count['total'] == 0) {
        echo "<h3>Insertando proveedor de ejemplo...</h3>";
        
        $sql = "INSERT INTO Proveedores (descripcion, telefono, email) VALUES (?, ?, ?)";
        $stmt = mysqli_prepare($conexion, $sql);
        $desc = "Aserradero Principal";
        $tel = "123-456-7890";
        $email = "contacto@aserradero.com";
        mysqli_stmt_bind_param($stmt, "sss", $desc, $tel, $email);
        
        if (mysqli_stmt_execute($stmt)) {
            echo "<p>✅ Proveedor insertado: {$desc}</p>";
        }
    }
    
    // Insertar productos de ejemplo si la tabla está vacía
    $check_prods = mysqli_query($conexion, "SELECT COUNT(*) as total FROM Productos");
    $prod_count = mysqli_fetch_assoc($check_prods);
    
    if ($prod_count['total'] == 0) {
        echo "<h3>Insertando productos de ejemplo...</h3>";
        
        $productos = [
            ['Tabla de Pino 2x4"', 'Tabla de pino tratado, ideal para construcción ligera', '', 25.00, 150, 2, 1],
            ['Viga de Roble 4x6"', 'Viga estructural de roble americano de alta resistencia', '', 95.00, 25, 1, 1],
            ['Tablón de Cedro', 'Tablón aromático de cedro rojo, perfecto para exteriores', '', 55.00, 40, 2, 1],
            ['Contrachapado 18mm', 'Tablero contrachapado marino para construcción', '', 68.00, 80, 3, 1],
            ['Listón Decorativo', 'Listones de pino para molduras y acabados', '', 15.00, 200, 5, 1],
            ['Viga Laminada 6x8"', 'Viga laminada encolada de alta resistencia estructural', '', 180.00, 15, 4, 1],
            ['Tabla de Eucalipto', 'Madera de eucalipto seca al horno, gran durabilidad', '', 28.50, 100, 1, 1],
            ['Panel OSB 15mm', 'Panel de virutas orientadas para construcción', '', 48.00, 60, 3, 1]
        ];
        
        foreach ($productos as $prod) {
            $sql = "INSERT INTO Productos (nombre, descripcion, imagen_url, precio, stock, id_categoria, id_proveedor) VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt = mysqli_prepare($conexion, $sql);
            mysqli_stmt_bind_param($stmt, "sssdiis", $prod[0], $prod[1], $prod[2], $prod[3], $prod[4], $prod[5], $prod[6]);
            
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
    echo "<h3>✅ Configuración completada</h3>";
    
    $result = mysqli_query($conexion, "SELECT COUNT(*) as total FROM Categorias WHERE activo = 1");
    $cats = mysqli_fetch_assoc($result);
    echo "<p><strong>Categorías activas:</strong> {$cats['total']}</p>";
    
    $result = mysqli_query($conexion, "SELECT COUNT(*) as total FROM Proveedores WHERE activo = 1");
    $provs = mysqli_fetch_assoc($result);
    echo "<p><strong>Proveedores activos:</strong> {$provs['total']}</p>";
    
    $result = mysqli_query($conexion, "SELECT COUNT(*) as total FROM Productos WHERE activo = 1");
    $prods = mysqli_fetch_assoc($result);
    echo "<p><strong>Productos activos:</strong> {$prods['total']}</p>";
    
    echo "<hr>";
    echo "<h4>Enlaces de prueba:</h4>";
    echo "<p><a href='index.php' target='_blank'>🏠 Ver sitio web</a></p>";
    echo "<p><a href='probar_modelos.php' target='_blank'>🧪 Probar modelos JSON</a></p>";
    echo "<p><a href='debug_db.php' target='_blank'>🔍 Debug de base de datos</a></p>";
    echo "<p><a href='verificar_estructura.php' target='_blank'>📋 Verificar estructura de tablas</a></p>";
    
} catch (Exception $e) {
    echo "<p><strong>❌ Error:</strong> " . $e->getMessage() . "</p>";
}

if (isset($conexion) && $conexion) {
    mysqli_close($conexion);
}
?>
