<?php
// Iniciar sesión si no está iniciada
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Verificar si hay sesión activa para mostrar opciones del usuario
$sesion_activa = isset($_SESSION['sesion_iniciada']) && $_SESSION['sesion_iniciada'] === "iniciado";
$es_admin = isset($_SESSION['esAdmin']) && $_SESSION['esAdmin'];
$nombre_usuario = isset($_SESSION['nombre']) ? $_SESSION['nombre'] : '';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? $page_title . ' - Aserradero' : 'Aserradero'; ?></title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    
    <style>
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #27ae60;
            --accent-color: #e74c3c;
            --light-bg: #f8f9fa;
            --dark-bg: #2c3e50;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: var(--light-bg);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* Header Styles */
        .header-main {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
            padding: 1rem 0;
            box-shadow: 0 2px 15px rgba(0,0,0,0.1);
        }

        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo-section {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .logo-section i {
            font-size: 28px;
            color: #d4a574;
        }

        .logo-section h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 700;
        }

        .user-section {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 8px;
            color: rgba(255,255,255,0.9);
        }

        .admin-badge {
            background: var(--accent-color);
            color: white;
            padding: 2px 8px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 600;
        }

        .btn-logout {
            background: rgba(231, 76, 60, 0.8);
            border: 1px solid rgba(231, 76, 60, 0.6);
            color: white;
            padding: 6px 12px;
            border-radius: 6px;
            text-decoration: none;
            font-size: 14px;
            transition: all 0.3s ease;
        }

        .btn-logout:hover {
            background: var(--accent-color);
            color: white;
            transform: translateY(-1px);
        }

        .btn-dashboard {
            background: rgba(39, 174, 96, 0.8);
            border: 1px solid rgba(39, 174, 96, 0.6);
            color: white;
            padding: 6px 12px;
            border-radius: 6px;
            text-decoration: none;
            font-size: 14px;
            transition: all 0.3s ease;
        }

        .btn-dashboard:hover {
            background: var(--secondary-color);
            color: white;
        }

        /* Breadcrumb */
        .breadcrumb-section {
            background: white;
            padding: 1rem 0;
            border-bottom: 1px solid #dee2e6;
        }

        .breadcrumb {
            background: none;
            padding: 0;
            margin: 0;
        }

        .breadcrumb-item + .breadcrumb-item::before {
            content: ">";
            color: var(--secondary-color);
        }

        .breadcrumb-item.active {
            color: var(--primary-color);
            font-weight: 600;
        }

        /* Main Content */
        .main-content {
            flex: 1;
            padding: 2rem 0;
        }

        .page-header {
            background: white;
            padding: 2rem 0;
            margin-bottom: 2rem;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }

        .page-title {
            color: var(--primary-color);
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .page-subtitle {
            color: #6c757d;
            font-size: 16px;
            margin: 0;
        }

        /* Action Buttons */
        .action-buttons {
            display: flex;
            gap: 10px;
            margin-bottom: 2rem;
        }

        .btn-action {
            padding: 10px 20px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .btn-primary-custom {
            background: var(--primary-color);
            color: white;
            border: 1px solid var(--primary-color);
        }

        .btn-primary-custom:hover {
            background: #34495e;
            color: white;
            transform: translateY(-2px);
        }

        .btn-secondary-custom {
            background: white;
            color: var(--primary-color);
            border: 1px solid var(--primary-color);
        }

        .btn-secondary-custom:hover {
            background: var(--primary-color);
            color: white;
        }

        .btn-success-custom {
            background: var(--secondary-color);
            color: white;
            border: 1px solid var(--secondary-color);
        }

        .btn-success-custom:hover {
            background: #229954;
            color: white;
        }

        /* Content Cards */
        .content-card {
            background: white;
            border-radius: 10px;
            padding: 2rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            margin-bottom: 2rem;
        }

        /* Tables */
        .table-container {
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }

        .table thead {
            background: var(--primary-color);
            color: white;
        }

        .table tbody tr:hover {
            background-color: rgba(39, 174, 96, 0.1);
        }

        /* Forms */
        .form-container {
            background: white;
            border-radius: 10px;
            padding: 2rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            font-weight: 600;
            color: var(--primary-color);
            margin-bottom: 0.5rem;
        }

        .form-control:focus {
            border-color: var(--secondary-color);
            box-shadow: 0 0 0 0.2rem rgba(39, 174, 96, 0.25);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .header-content {
                flex-direction: column;
                gap: 15px;
                text-align: center;
            }

            .user-section {
                flex-direction: column;
                gap: 10px;
            }

            .action-buttons {
                flex-direction: column;
            }

            .page-title {
                font-size: 24px;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header class="header-main">
        <div class="container">
            <div class="header-content">
                <div class="logo-section">
                    <i class="fas fa-tree"></i>
                    <h1>Aserradero</h1>
                </div>
                
                <div class="user-section">
                    <?php if ($sesion_activa): ?>
                        <div class="user-info">
                            <i class="fas fa-user-circle"></i>
                            <span>Bienvenido, <?php echo htmlspecialchars($nombre_usuario); ?></span>
                            <?php if ($es_admin): ?>
                                <span class="admin-badge">Admin</span>
                            <?php endif; ?>
                        </div>
                        <a href="index.php?opc=dashboard" class="btn-dashboard">
                            <i class="fas fa-tachometer-alt"></i>
                            Dashboard
                        </a>
                        <a href="index.php?opc=logout" class="btn-logout" onclick="return confirm('¿Cerrar sesión?')">
                            <i class="fas fa-sign-out-alt"></i>
                            Cerrar Sesión
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </header>

    <!-- Breadcrumb -->
    <?php if (isset($breadcrumbs) && !empty($breadcrumbs)): ?>
    <div class="breadcrumb-section">
        <div class="container">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="index.php?opc=dashboard">
                            <i class="fas fa-home"></i> Dashboard
                        </a>
                    </li>
                    <?php foreach ($breadcrumbs as $breadcrumb): ?>
                        <?php if (isset($breadcrumb['active']) && $breadcrumb['active']): ?>
                            <li class="breadcrumb-item active" aria-current="page">
                                <?php echo htmlspecialchars($breadcrumb['title']); ?>
                            </li>
                        <?php else: ?>
                            <li class="breadcrumb-item">
                                <a href="<?php echo htmlspecialchars($breadcrumb['url']); ?>">
                                    <?php echo htmlspecialchars($breadcrumb['title']); ?>
                                </a>
                            </li>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </ol>
            </nav>
        </div>
    </div>
    <?php endif; ?>

    <!-- Page Header -->
    <?php if (isset($page_title) || isset($page_subtitle)): ?>
    <div class="page-header">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <?php if (isset($page_title)): ?>
                        <h1 class="page-title">
                            <?php if (isset($page_icon)): ?>
                                <i class="<?php echo $page_icon; ?>"></i>
                            <?php endif; ?>
                            <?php echo htmlspecialchars($page_title); ?>
                        </h1>
                    <?php endif; ?>
                    <?php if (isset($page_subtitle)): ?>
                        <p class="page-subtitle"><?php echo htmlspecialchars($page_subtitle); ?></p>
                    <?php endif; ?>
                </div>
                <div class="col-md-4 text-md-end">
                    <?php if (isset($header_actions) && !empty($header_actions)): ?>
                        <div class="action-buttons">
                            <?php foreach ($header_actions as $action): ?>
                                <a href="<?php echo htmlspecialchars($action['url']); ?>" 
                                   class="btn-action <?php echo $action['class'] ?? 'btn-primary-custom'; ?>">
                                    <?php if (isset($action['icon'])): ?>
                                        <i class="<?php echo $action['icon']; ?>"></i>
                                    <?php endif; ?>
                                    <?php echo htmlspecialchars($action['title']); ?>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- Main Content -->
    <main class="main-content">
        <div class="container">
