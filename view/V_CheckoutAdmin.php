<?php
// Iniciar sesión si no está iniciada
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Verificar si hay sesión activa y si es admin
if (!isset($_SESSION['sesion_iniciada']) || $_SESSION['sesion_iniciada'] !== "iniciado" || !$_SESSION['esAdmin']) {
    header("Location: index.php?opc=login");
    exit;
}

// Incluir conexión
require_once './config/Cconexion.php';
require_once './config/clavebasededatos.php';

try {
    $pdo = new PDO("mysql:host=$hostname;dbname=$database;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Obtener lista de clientes
    $stmt = $pdo->query("SELECT id_usuario as id, nombre, correo as email FROM Usuarios WHERE activo = 1 ORDER BY nombre");
    $clientes = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Obtener productos con stock disponible
    $stmt = $pdo->query("SELECT id_producto as id, nombre, precio, stock, imagen FROM Productos WHERE stock > 0 ORDER BY nombre");
    $productos = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (Exception $e) {
    die('Error al conectar con la base de datos: ' . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Pedido - Administrador</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
            padding: 20px;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            overflow: hidden;
        }

        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }

        .header h1 {
            font-size: 2rem;
            margin-bottom: 10px;
        }

        .content {
            padding: 40px;
        }

        .form-section {
            margin-bottom: 30px;
            border: 2px solid #e0e6ed;
            border-radius: 10px;
            padding: 25px;
        }

        .section-title {
            font-size: 1.3rem;
            color: #2c3e50;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #34495e;
        }

        select, input {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e0e6ed;
            border-radius: 8px;
            font-size: 14px;
            transition: border-color 0.3s;
        }

        select:focus, input:focus {
            outline: none;
            border-color: #667eea;
        }

        .productos-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }

        .producto-card {
            border: 2px solid #e0e6ed;
            border-radius: 10px;
            padding: 20px;
            transition: all 0.3s;
            position: relative;
        }

        .producto-card:hover {
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            transform: translateY(-2px);
        }

        .producto-imagen {
            width: 100%;
            height: 150px;
            object-fit: cover;
            border-radius: 8px;
            margin-bottom: 15px;
            background: #f8f9fa;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #6c757d;
        }

        .producto-info h3 {
            color: #2c3e50;
            margin-bottom: 8px;
            font-size: 1.1rem;
        }

        .producto-precio {
            color: #e74c3c;
            font-weight: bold;
            font-size: 1.2rem;
            margin-bottom: 8px;
        }

        .producto-stock {
            color: #27ae60;
            font-size: 0.9rem;
            margin-bottom: 15px;
        }

        .cantidad-controls {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-top: 15px;
        }

        .cantidad-btn {
            width: 35px;
            height: 35px;
            border: none;
            background: #667eea;
            color: white;
            border-radius: 50%;
            cursor: pointer;
            font-size: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .cantidad-btn:hover {
            background: #5a6fd8;
        }

        .cantidad-input {
            width: 60px;
            text-align: center;
            padding: 8px;
        }

        .carrito-section {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 20px;
            margin-top: 30px;
        }

        .carrito-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 0;
            border-bottom: 1px solid #e0e6ed;
        }

        .carrito-item:last-child {
            border-bottom: none;
        }

        .btn-eliminar {
            background: #e74c3c;
            color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 12px;
        }

        .btn-eliminar:hover {
            background: #c0392b;
        }

        .total-section {
            background: #2c3e50;
            color: white;
            padding: 20px;
            margin-top: 20px;
            border-radius: 10px;
            text-align: center;
        }

        .total-amount {
            font-size: 1.5rem;
            font-weight: bold;
            margin-bottom: 15px;
        }

        .btn-procesar {
            background: #27ae60;
            color: white;
            border: none;
            padding: 15px 30px;
            border-radius: 8px;
            font-size: 1.1rem;
            font-weight: bold;
            cursor: pointer;
            transition: background 0.3s;
            width: 100%;
        }

        .btn-procesar:hover {
            background: #219a52;
        }

        .btn-procesar:disabled {
            background: #bdc3c7;
            cursor: not-allowed;
        }

        .btn-volver {
            background: #6c757d;
            color: white;
            text-decoration: none;
            padding: 12px 25px;
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 20px;
            transition: background 0.3s;
        }

        .btn-volver:hover {
            background: #5a6268;
            color: white;
        }

        @media (max-width: 768px) {
            .content {
                padding: 20px;
            }
            
            .productos-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1><i class="fas fa-shopping-cart"></i> Crear Pedido para Cliente</h1>
            <p>Selecciona un cliente y agrega productos al pedido</p>
        </div>

        <div class="content">
            <a href="?opc=dashboard" class="btn-volver">
                <i class="fas fa-arrow-left"></i>
                Volver al Dashboard
            </a>

            <form id="formPedido">
                <!-- Selección de Cliente -->
                <div class="form-section">
                    <h2 class="section-title">
                        <i class="fas fa-user"></i>
                        Seleccionar Cliente
                    </h2>
                    <div class="form-group">
                        <label for="cliente_id">Cliente:</label>
                        <select id="cliente_id" name="cliente_id" required>
                            <option value="">Seleccionar cliente...</option>
                            <?php foreach ($clientes as $cliente): ?>
                                <option value="<?= $cliente['id'] ?>">
                                    <?= htmlspecialchars($cliente['nombre']) ?> - <?= htmlspecialchars($cliente['email']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <!-- Productos Disponibles -->
                <div class="form-section">
                    <h2 class="section-title">
                        <i class="fas fa-boxes"></i>
                        Productos Disponibles
                    </h2>
                    <div class="productos-grid">
                        <?php foreach ($productos as $producto): ?>
                            <div class="producto-card" data-producto-id="<?= $producto['id'] ?>">
                                <div class="producto-imagen">
                                    <?php if ($producto['imagen']): ?>
                                        <img src="<?= htmlspecialchars($producto['imagen']) ?>" alt="<?= htmlspecialchars($producto['nombre']) ?>">
                                    <?php else: ?>
                                        <i class="fas fa-image fa-3x"></i>
                                    <?php endif; ?>
                                </div>
                                <div class="producto-info">
                                    <h3><?= htmlspecialchars($producto['nombre']) ?></h3>
                                    <div class="producto-precio">$<?= number_format($producto['precio'], 2) ?></div>
                                    <div class="producto-stock">Stock: <?= $producto['stock'] ?> unidades</div>
                                    
                                    <div class="cantidad-controls">
                                        <button type="button" class="cantidad-btn" onclick="cambiarCantidad(<?= $producto['id'] ?>, -1)">-</button>
                                        <input type="number" class="cantidad-input" id="cantidad_<?= $producto['id'] ?>" value="0" min="0" max="<?= $producto['stock'] ?>" readonly>
                                        <button type="button" class="cantidad-btn" onclick="cambiarCantidad(<?= $producto['id'] ?>, 1)">+</button>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Carrito -->
                <div class="carrito-section">
                    <h2 class="section-title">
                        <i class="fas fa-shopping-cart"></i>
                        Carrito de Compras
                    </h2>
                    <div id="carrito-items">
                        <p class="text-muted">No hay productos en el carrito</p>
                    </div>
                    
                    <div class="total-section" id="total-section" style="display: none;">
                        <div class="total-amount">Total: $<span id="total-amount">0.00</span></div>
                        <button type="button" class="btn-procesar" onclick="procesarPedido()" disabled>
                            <i class="fas fa-check"></i>
                            Procesar Pedido
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        const productos = <?= json_encode($productos) ?>;
        let carrito = {};
        let total = 0;

        function cambiarCantidad(productoId, cambio) {
            const input = document.getElementById(`cantidad_${productoId}`);
            const producto = productos.find(p => p.id == productoId);
            
            let nuevaCantidad = parseInt(input.value) + cambio;
            nuevaCantidad = Math.max(0, Math.min(nuevaCantidad, producto.stock));
            
            input.value = nuevaCantidad;
            
            if (nuevaCantidad > 0) {
                carrito[productoId] = {
                    id: productoId,
                    nombre: producto.nombre,
                    precio: parseFloat(producto.precio),
                    cantidad: nuevaCantidad,
                    stock: producto.stock
                };
            } else {
                delete carrito[productoId];
            }
            
            actualizarCarrito();
        }

        function actualizarCarrito() {
            const carritoItems = document.getElementById('carrito-items');
            const totalSection = document.getElementById('total-section');
            const totalAmount = document.getElementById('total-amount');
            
            if (Object.keys(carrito).length === 0) {
                carritoItems.innerHTML = '<p class="text-muted">No hay productos en el carrito</p>';
                totalSection.style.display = 'none';
                return;
            }
            
            let html = '';
            total = 0;
            
            for (const item of Object.values(carrito)) {
                const subtotal = item.cantidad * item.precio;
                total += subtotal;
                
                html += `
                    <div class="carrito-item">
                        <div>
                            <strong>${item.nombre}</strong><br>
                            <small>$${item.precio.toFixed(2)} x ${item.cantidad} = $${subtotal.toFixed(2)}</small>
                        </div>
                        <button type="button" class="btn-eliminar" onclick="eliminarDelCarrito(${item.id})">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                `;
            }
            
            carritoItems.innerHTML = html;
            totalAmount.textContent = total.toFixed(2);
            totalSection.style.display = 'block';
            
            // Habilitar/deshabilitar botón de procesar
            const btnProcesar = document.querySelector('.btn-procesar');
            const clienteId = document.getElementById('cliente_id').value;
            btnProcesar.disabled = !clienteId || Object.keys(carrito).length === 0;
        }

        function eliminarDelCarrito(productoId) {
            delete carrito[productoId];
            document.getElementById(`cantidad_${productoId}`).value = 0;
            actualizarCarrito();
        }

        function procesarPedido() {
            const clienteId = document.getElementById('cliente_id').value;
            
            if (!clienteId) {
                alert('Debe seleccionar un cliente');
                return;
            }
            
            if (Object.keys(carrito).length === 0) {
                alert('Debe agregar al menos un producto al carrito');
                return;
            }
            
            const datos = {
                cliente_id: clienteId,
                items: Object.values(carrito).map(item => ({
                    id: item.id,
                    quantity: item.cantidad,
                    price: item.precio
                })),
                total: total,
                admin_pedido: true,
                nombre: 'Admin',
                email: 'admin@sistema.com',
                telefono: '000-000-0000',
                metodo_pago: 'Efectivo'
            };
            
            fetch('./model/M_ProcesarPedido.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(datos)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Pedido creado exitosamente');
                    window.location.href = '?opc=gestion_pedidos';
                } else {
                    alert('Error al procesar el pedido: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error al procesar el pedido');
            });
        }

        // Event listener para cambio de cliente
        document.getElementById('cliente_id').addEventListener('change', function() {
            actualizarCarrito();
        });
    </script>
</body>
</html>
