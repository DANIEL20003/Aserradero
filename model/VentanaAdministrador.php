 <?php 
session_start();

// Verificar si el usuario está autenticado y es administrador
// if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] !== 'administrador') {
    // header('Location: ../public/layaout/Login.php');
    // exit();
// } 

$nombre_admin = $_SESSION['nombre'] ?? 'Administrador';
$pageTitle = "Panel Administrador - Aserradero";
$basePath = "../";
$additionalCSS = ["../public/css/admin-dashboard.css"];
?>

<?php include '../public/layaout/header.php'; ?>

<!-- Main Content -->
<main class="admin-main">
    <div class="admin-container">

        <div class="dashboard-grid">
            <div class="dashboard-card productos" onclick="navigateTo('productos')">
                <div class="card-header">
                    <div class="card-icon">
                        <i class="fas fa-cubes"></i>
                    </div>
                    <div class="card-badge">
                        <span id="totalProductos">0</span>
                    </div>
                </div>
                <div class="card-content">
                    <h3>GESTIONAR</h3>
                    <h2>PRODUCTOS</h2>
                    <p>Administrar inventario de madera y productos del aserradero</p>
                </div>
                <div class="card-footer">
                    <span>Ver todos los productos</span>
                    <i class="fas fa-arrow-right"></i>
                </div>
            </div>

            <!-- Gestionar Clientes -->
            <div class="dashboard-card clientes" onclick="navigateTo('clientes')">
                <div class="card-header">
                    <div class="card-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="card-badge">
                        <span id="totalClientes">0</span>
                    </div>
                </div>
                <div class="card-content">
                    <h3>GESTIONAR</h3>
                    <h2>CLIENTES</h2>
                    <p>Administrar base de datos de clientes y sus pedidos</p>
                </div>
                <div class="card-footer">
        
                    <span>Ver todos los clientes</span>
                    <i class="fas fa-arrow-right"></i>
                </div>
            </div>

            <div class="dashboard-card reportes" onclick="navigateTo('reportes')">
                <div class="card-header">
                    <div class="card-icon">
                        <i class="fas fa-chart-bar"></i>
                    </div>
                    <div class="card-badge">
                        <span id="reportesGenerados">0</span>
                    </div>
                </div>
                <div class="card-content">
                    <h3>GENERAR</h3>
                    <h2>REPORTES</h2>
                    <p>Crear informes de ventas, inventario y estadísticas</p>
                </div>
                <div class="card-footer">
                    <span>Generar nuevo reporte</span>
                    <i class="fas fa-arrow-right"></i>
                </div>
            </div>

            <div class="dashboard-card facturas" onclick="navigateTo('facturas')">
                <div class="card-header">
                    <div class="card-icon">
                        <i class="fas fa-file-invoice-dollar"></i>
                    </div>
                    <div class="card-badge">
                        <span id="facturasHoy">0</span>
                    </div>
                </div>
                <div class="card-content">
                    <h3>GENERAR</h3>
                    <h2>FACTURAS</h2>
                    <p>Crear y gestionar facturas de ventas y servicios</p>
                </div>
                <div class="card-footer">
                    <span>Nueva factura</span>
                    <i class="fas fa-arrow-right"></i>
                </div>
            </div>
        </div>

</main>

<script>
    function navigateTo(seccion) {
        switch(seccion) {
            case 'productos':
                window.location.href = '../controller/Copciones.php?opc=productos';
                break;
            case 'clientes':
                window.location.href = '../controller/Copciones.php?opc=clientes';
                break;
            case 'reportes':
                window.location.href = '../controller/Copciones.php?opc=reportes';
                break;
            case 'facturas':
                window.location.href = '../controller/Copciones.php?opc=facturas';
                break;
            case 'usuarios':
                window.location.href = '../controller/Copciones.php?opc=usuarios';
                break;
            case 'configuracion':
                window.location.href = '../controller/Copciones.php?opc=configuracion';
                break;
            case 'inventario':
                window.location.href = '../controller/Copciones.php?opc=inventario';
                break;
            case 'ventas':
                window.location.href = '../controller/Copciones.php?opc=ventas';
                break;
            case 'proveedores':
                window.location.href = '../controller/Copciones.php?opc=proveedores';
                break;
            default:
                console.log('Sección no encontrada:', seccion);
                window.location.href = '../controller/Copciones.php?opc=dashboard';
        }
    }

   
    

    document.querySelectorAll('.dashboard-card').forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-10px) scale(1.02)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0) scale(1)';
        });
    });
</script>

<?php include '../public/layaout/footer.php'; ?>
