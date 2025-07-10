<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aserradería Pequinez | Maderas de Calidad</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css" rel="stylesheet">
    
    <!-- CSS personalizado -->
    <link rel="stylesheet" href="public/css/Loging.css">
    <link rel="stylesheet" href="public/css/forest-theme.css">
	<link rel="stylesheet" href="public/css/paginaprincipal.css">
    
    <!-- Ionicons -->
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
  
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
        <a href="index.php" class="logo">
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
            <a href="?opc=inicio">Inicio</a>
            <a href="?opc=productos">Productos</a>
            <?php if (isset($_SESSION['sesion_iniciada']) && $_SESSION['sesion_iniciada'] == 'iniciado'): ?>
                <div class="dropdown">
                    <a class="nav-link dropdown-toggle text-white" href="#" id="navbarDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-person-fill"></i> <?php echo htmlspecialchars($_SESSION['nombre']); ?>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdownMenuLink">
                        <li><a class="dropdown-item" href="?opc=mis_pedidos">Mis Pedidos</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="./model/M_Logout.php">Cerrar Sesión</a></li>
                    </ul>
                </div>
            <?php else: ?>
                <a href="public/Login/Quienessomos.html">Nuestra Historia</a>
                <a href="?opc=login">Iniciar Sesión</a>
            <?php endif; ?>
        </nav>
    </header>

    <script>
        // Función de búsqueda
        function realizarBusqueda() {
            const searchInput = document.querySelector('.search-input');
            const searchTerm = searchInput.value.trim();
            
            if (searchTerm) {
                console.log('Buscando:', searchTerm);
                // Aquí puedes implementar la lógica de búsqueda
                alert(`Buscando: ${searchTerm}`);
            } else {
                alert('Por favor, ingresa un término de búsqueda');
            }
        }
        
        // Ejecutar búsqueda al presionar Enter
		const searchInput = document.querySelector('.search-input');
		if (searchInput) {
			searchInput.addEventListener('keypress', function(e) {
				if (e.key === 'Enter') {
					realizarBusqueda();
				}
			});
		}
        
        // Inicializar dropdown cuando el DOM esté listo
        document.addEventListener('DOMContentLoaded', function() {
            // Esperar a que Bootstrap se cargue
            function initializeDropdown() {
                if (typeof bootstrap !== 'undefined') {
                    const dropdownElement = document.getElementById('navbarDropdownMenuLink');
                    if (dropdownElement) {
                        new bootstrap.Dropdown(dropdownElement);
                    }
                } else {
                    // Intentar de nuevo en 100ms si Bootstrap no está cargado
                    setTimeout(initializeDropdown, 100);
                }
            }
            initializeDropdown();
        });
        
    </script>

    <!-- Scripts JavaScript -->
    <script src="public/js/Login.js"></script>