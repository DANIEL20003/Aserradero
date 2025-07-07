<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($pageTitle) ? $pageTitle : 'Aserradería Pequinez | Maderas de Calidad'; ?></title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css" rel="stylesheet">
    
    <!-- CSS personalizado -->
    <link rel="stylesheet" href="<?php echo $basePath ?? ''; ?>public/css/Loging.css">
    <link rel="stylesheet" href="<?php echo $basePath ?? ''; ?>public/css/forest-theme.css">
    
    <!-- Ionicons -->
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    
    <!-- CSS adicional específico de la página -->
    <?php if (isset($additionalCSS)): ?>
        <?php foreach ($additionalCSS as $css): ?>
            <link rel="stylesheet" href="<?php echo $css; ?>">
        <?php endforeach; ?>
    <?php endif; ?>
    
    <!-- Polyfill para dialog en navegadores antiguos -->
    <script>
        // Polyfill simple para el elemento dialog
        if (!window.HTMLDialogElement) {
            function dialogPolyfill() {
                const dialogs = document.querySelectorAll('dialog');
                dialogs.forEach(dialog => {
                    if (!dialog.showModal) {
                        dialog.showModal = function() {
                            this.style.display = 'block';
                            this.setAttribute('open', '');
                            document.body.style.overflow = 'hidden';
                        };
                    }
                    if (!dialog.close) {
                        dialog.close = function() {
                            this.style.display = 'none';
                            this.removeAttribute('open');
                            document.body.style.overflow = '';
                        };
                    }
                });
            }
            
            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', dialogPolyfill);
            } else {
                dialogPolyfill();
            }
        }
    </script>
    
    <!-- Estilos para footer sticky -->
    <style>
        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
        }
        
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        
        .main-content {
            flex: 1;
            display: flex;
            flex-direction: column;
        }
        
        /* Asegurar que el contenido principal ocupe el espacio disponible */
        .admin-main {
            flex: 1;
            min-height: calc(100vh - 200px);
        }
        
        /* Footer siempre al final */
        footer {
            margin-top: auto;
        }
    </style>
</head>
<body>

    <!-- ENCABEZADO -->
    <header class="header">
        <a href="<?php echo $basePath ?? ''; ?>index.php" class="logo">
            <ion-icon name="leaf" class="icono-madera"></ion-icon>
            <span>Aserradería Pequinez</span>
        </a>
        
        <!-- CAMPO DE BÚSQUEDA -->
        <div class="search-container">
            <div class="search-box">
                <ion-icon name="search" class="search-icon"></ion-icon>
                <input type="text" placeholder="Buscar maderas, tablones, vigas..." class="search-input" id="searchInput">
                <button class="search-btn" onclick="realizarBusqueda()">
                    <ion-icon name="filter"></ion-icon>
                </button>
            </div>
        </div>
        
        <nav class="nav">
            <a href="<?php echo $basePath ?? ''; ?>public/Login/Quienessomos.html">Nuestra Historia</a>
            <a href="#" onclick="abrirModalSeleccionUsuario()">Iniciar Sesión</a>
        </nav>
    </header>

    
    <!-- Scripts JavaScript -->
    <script src="<?php echo $basePath ?? ''; ?>public/js/Login.js"></script>
    
    <!-- Contenedor para contenido principal -->
    <div class="main-content">
        <!-- El contenido de la página se insertará aquí -->
