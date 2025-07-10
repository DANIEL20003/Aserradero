<?php
// Iniciar sesión si no está iniciada
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Verificar si hay sesión activa
if (!isset($_SESSION['sesion_iniciada']) || $_SESSION['sesion_iniciada'] !== "iniciado") {
    header("Location: index.php?opc=login");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Aserradero</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
            min-height: 100vh;
            color: #333;
        }

        /* Header */
        .header {
            background: #fff;
            padding: 15px 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 24px;
            font-weight: bold;
            color: #2c3e50;
        }

        .logo i {
            color: #27ae60;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .welcome-msg {
            font-weight: 500;
            color: #2c3e50;
        }

        .admin-badge {
            background: #e74c3c;
            color: white;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
        }

        .logout-btn {
            background: #e74c3c;
            color: white;
            padding: 8px 15px;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            font-size: 14px;
            transition: background 0.3s;
        }

        .logout-btn:hover {
            background: #c0392b;
        }

        /* Main Content */
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 30px 20px;
        }

        .dashboard-title {
            text-align: center;
            color: white;
            margin-bottom: 30px;
        }

        .dashboard-title h1 {
            font-size: 36px;
            margin-bottom: 10px;
        }

        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin-top: 30px;
        }

        .card {
            background: white;
            border-radius: 10px;
            padding: 25px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.15);
        }

        .card-header {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid #ecf0f1;
        }

        .card-icon {
            font-size: 24px;
            color: #3498db;
        }

        .card-title {
            font-size: 20px;
            font-weight: 600;
            color: #2c3e50;
        }

        .card-actions {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .btn {
            padding: 12px 16px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: 500;
            text-align: center;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .btn-primary {
            background: #3498db;
            color: white;
        }

        .btn-primary:hover {
            background: #2980b9;
        }

        .btn-secondary {
            background: #ecf0f1;
            color: #2c3e50;
            border: 1px solid #bdc3c7;
        }

        .btn-secondary:hover {
            background: #d5dbdb;
        }

        .btn i {
            font-size: 14px;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .header {
                flex-direction: column;
                gap: 15px;
                text-align: center;
            }

            .dashboard-grid {
                grid-template-columns: 1fr;
            }

            .container {
                padding: 20px 15px;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <div class="logo">
            <i class="fas fa-tree"></i>
            <span>Aserradero</span>
        </div>
        <div class="user-info">
            <span class="welcome-msg">
                <i class="fas fa-user-circle"></i>
                Bienvenido, <?php echo htmlspecialchars($_SESSION['nombre']); ?>
            </span>
            <?php if (isset($_SESSION['esAdmin']) && $_SESSION['esAdmin']): ?>
                <span class="admin-badge">Admin</span>
            <?php endif; ?>
            <a href="./model/M_Logout.php" class="logout-btn" onclick="return confirm('¿Cerrar sesión?')">
                <i class="fas fa-sign-out-alt"></i>
                Cerrar Sesión
            </a>
        </div>
    </div>

    <!-- Main Content -->
    <div class="container">
        <div class="dashboard-title">
            <h1>Panel de Control</h1>
            <p>Gestiona tu aserradero de manera eficiente</p>
        </div>

        <div class="dashboard-grid">
            <!-- Productos -->
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-boxes card-icon"></i>
                    <h2 class="card-title">Productos</h2>
                </div>
                <div class="card-actions">
                    <a href="?opc=listar_productos" class="btn btn-primary">
                        <i class="fas fa-list"></i>
                        Listar Productos
                    </a>
                    <a href="?opc=agregar_producto" class="btn btn-secondary">
                        <i class="fas fa-plus"></i>
                        Agregar Producto
                    </a>
                </div>
            </div>

            <!-- Compras -->
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-shopping-cart card-icon"></i>
                    <h2 class="card-title">Compras</h2>
                </div>
                <div class="card-actions">
                    <a href="?opc=agregar_compra" class="btn btn-primary">
                        <i class="fas fa-cart-plus"></i>
                        Ingresar Compra
                    </a>
                </div>
            </div>

            <!-- Categorías -->
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-tags card-icon"></i>
                    <h2 class="card-title">Categorías</h2>
                </div>
                <div class="card-actions">
                    <a href="?opc=listar_categorias" class="btn btn-primary">
                        <i class="fas fa-list"></i>
                        Listar Categorías
                    </a>
                    <a href="?opc=agregar_categoria" class="btn btn-secondary">
                        <i class="fas fa-plus"></i>
                        Agregar Categoría
                    </a>
                </div>
            </div>

            <!-- Proveedores -->
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-truck card-icon"></i>
                    <h2 class="card-title">Proveedores</h2>
                </div>
                <div class="card-actions">
                    <a href="?opc=listar_proveedores" class="btn btn-primary">
                        <i class="fas fa-list"></i>
                        Listar Proveedores
                    </a>
                    <a href="?opc=agregar_proveedor" class="btn btn-secondary">
                        <i class="fas fa-plus"></i>
                        Agregar Proveedor
                    </a>
                </div>
            </div>

            <!-- Ventas -->
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-cash-register card-icon"></i>
                    <h2 class="card-title">Ventas</h2>
                </div>
                <div class="card-actions">
                    <a href="?opc=listar_ventas" class="btn btn-primary">
                        <i class="fas fa-list"></i>
                        Listar Ventas
                    </a>
                    <a href="?opc=agregar_venta" class="btn btn-secondary">
                        <i class="fas fa-plus"></i>
                        Agregar Venta
                    </a>
                </div>
            </div>

            <!-- Mis Pedidos -->
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-shopping-bag card-icon"></i>
                    <h2 class="card-title">Mis Pedidos</h2>
                </div>
                <div class="card-actions">
                    <a href="?opc=mis_pedidos" class="btn btn-primary">
                        <i class="fas fa-list"></i>
                        Ver Mis Pedidos
                    </a>
                </div>
            </div>

            <!-- Clientes -->
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-users card-icon"></i>
                    <h2 class="card-title">Clientes</h2>
                </div>
                <div class="card-actions">
                    <a href="?opc=listar_clientes" class="btn btn-primary">
                        <i class="fas fa-list"></i>
                        Listar Clientes
                    </a>
                    <a href="?opc=agregar_cliente" class="btn btn-secondary">
                        <i class="fas fa-plus"></i>
                        Agregar Cliente
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Animación de entrada
        document.addEventListener('DOMContentLoaded', function() {
            const cards = document.querySelectorAll('.card');
            cards.forEach((card, index) => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(30px)';
                
                setTimeout(() => {
                    card.style.transition = 'all 0.6s ease';
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, index * 150);
            });
        });
    </script>
</body>
</html>
