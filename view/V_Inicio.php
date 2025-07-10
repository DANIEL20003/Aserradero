<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aserradero - Inicio</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .navbar-brand i {
            color: #28a745;
            margin-right: 8px;
        }
        .hero-section {
            background: linear-gradient(135deg, #2d5016 0%, #3d6b1f 100%);
            color: white;
            padding: 80px 0;
        }
        .product-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            border: none;
            border-radius: 12px;
            overflow: hidden;
        }
        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }
        .product-image {
            height: 200px;
            object-fit: cover;
            width: 100%;
        }
        .price {
            color: #28a745;
            font-weight: bold;
            font-size: 1.2rem;
        }
        .cart-icon {
            position: relative;
        }
        .cart-badge {
            position: absolute;
            top: -8px;
            right: -8px;
            background: #dc3545;
            color: white;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            font-size: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .category-filter {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 12px;
            margin-bottom: 30px;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold fs-3" href="index.php">
                <i class="fas fa-tree"></i>
                Aserradero
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="index.php">Inicio</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#productos">Productos</a>
                    </li>
                    <?php if (isset($_SESSION['sesion_iniciada']) && $_SESSION['sesion_iniciada'] === "iniciado"): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="index.php?opc=mis_pedidos">Mis Pedidos</a>
                    </li>
                    <?php endif; ?>
                </ul>
                
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link position-relative cart-icon" href="#" onclick="toggleCart()">
                            <i class="fas fa-shopping-cart"></i>
                            <span class="cart-badge" id="cartBadge" style="display: none;">0</span>
                        </a>
                    </li>
                    <?php if (isset($_SESSION['sesion_iniciada']) && $_SESSION['sesion_iniciada'] === "iniciado"): ?>
                    <li class="nav-item d-lg-none">
                        <a class="nav-link" href="./model/M_Logout.php">
                            <i class="fas fa-sign-out-alt"></i> Cerrar Sesión
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-user"></i>
                            <?php echo htmlspecialchars($_SESSION['nombre']); ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="index.php?opc=mis_pedidos">
                                <i class="fas fa-shopping-bag"></i> Mis Pedidos
                            </a></li>
                            <?php if (isset($_SESSION['esAdmin']) && $_SESSION['esAdmin']): ?>
                            <li><a class="dropdown-item" href="index.php?opc=dashboard">
                                <i class="fas fa-tachometer-alt"></i> Dashboard
                            </a></li>
                            <?php endif; ?>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="./model/M_Logout.php" onclick="return confirm('¿Desea cerrar la sesión?')">
                                <i class="fas fa-sign-out-alt"></i> Cerrar Sesión
                            </a></li>
                        </ul>
                    </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="index.php?opc=login">
                                <i class="fas fa-sign-in-alt"></i> Iniciar Sesión
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="index.php?opc=registro">
                                <i class="fas fa-user-plus"></i> Registrarse
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container text-center">
            <h1 class="display-4 fw-bold mb-4">Bienvenido a Aserradero</h1>
            <p class="lead mb-4">Los mejores productos de madera para tu hogar y negocio</p>
            <a href="#productos" class="btn btn-light btn-lg">
                <i class="fas fa-eye"></i> Ver Productos
            </a>
        </div>
    </section>

    <!-- Productos Section -->
    <section id="productos" class="py-5">
        <div class="container">
            <h2 class="text-center mb-5">Nuestros Productos</h2>
            
            <!-- Filtros de categoría -->
            <div class="category-filter">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <h5 class="mb-3 mb-md-0">Filtrar por categoría:</h5>
                    </div>
                    <div class="col-md-6">
                        <select class="form-select" id="categoryFilter" onchange="filterProducts()">
                            <option value="">Todas las categorías</option>
                            <!-- Las categorías se cargarán dinámicamente -->
                        </select>
                    </div>
                </div>
            </div>

            <!-- Productos Grid -->
            <div class="row" id="productGrid">
                <!-- Los productos se cargarán aquí dinámicamente -->
            </div>
            
            <!-- Loading -->
            <div class="text-center" id="loadingProducts">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Cargando productos...</span>
                </div>
            </div>
        </div>
    </section>

    <!-- Carrito Modal -->
    <div class="modal fade" id="cartModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-shopping-cart"></i> Mi Carrito
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div id="cartItems">
                        <!-- Items del carrito se cargarán aquí -->
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="w-100">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5>Total: <span id="cartTotal">$0.00</span></h5>
                        </div>
                        <div class="d-grid gap-2">
                            <button type="button" class="btn btn-success btn-lg" onclick="proceedToCheckout()">
                                <i class="fas fa-credit-card"></i> Proceder al Pago
                            </button>
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                Continuar Comprando
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-dark text-white py-4 mt-5">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h5><i class="fas fa-tree"></i> Aserradero</h5>
                    <p>Los mejores productos de madera para tu hogar y negocio.</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <p>&copy; 2025 Aserradero. Todos los derechos reservados.</p>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        let cart = JSON.parse(localStorage.getItem('cart')) || [];
        let products = [];
        let categories = [];

        // Cargar productos y categorías al iniciar
        document.addEventListener('DOMContentLoaded', function() {
            loadProducts();
            loadCategories();
            updateCartBadge();
            
            // Initialize Bootstrap components
            // Enable dropdowns
            document.querySelectorAll('.dropdown-toggle').forEach(function(element) {
                new bootstrap.Dropdown(element);
            });
            
            // Add click event to dropdown items to ensure they work
            document.querySelectorAll('.dropdown-item').forEach(function(element) {
                element.addEventListener('click', function(e) {
                    if (this.getAttribute('href') === '#') {
                        e.preventDefault();
                    }
                });
            });
        });

        async function loadProducts() {
            try {
                // Cargar productos directamente de la base de datos
                const response = await fetch('./model/M_ListarProductosPublicos.php');
                
                if (!response.ok) {
                    throw new Error(`Error HTTP: ${response.status} - ${response.statusText}`);
                }
                
                const data = await response.json();
                console.log('Productos cargados:', data); // Debug
                
                if (!data.success) {
                    console.error('Error en respuesta:', data.error);
                    throw new Error(data.error || 'Error desconocido al cargar productos');
                }
                
                products = data.products || [];
                displayProducts(products);
                
                if (products.length === 0) {
                    console.warn('⚠️ No se encontraron productos en la base de datos');
                }
                
            } catch (error) {
                console.error('Error cargando productos:', error);
                document.getElementById('productGrid').innerHTML = 
                    '<div class="col-12 text-center">' +
                    '<div class="alert alert-danger">' +
                    '<h5>Error al cargar productos</h5>' +
                    '<p>' + error.message + '</p>' +
                    '<p><small>Verifique la conexión a la base de datos y que existan productos activos.</small></p>' +
                    '</div></div>';
            } finally {
                document.getElementById('loadingProducts').style.display = 'none';
            }
        }

        async function loadCategories() {
            try {
                // Cargar categorías directamente de la base de datos
                const response = await fetch('./model/M_ListarCategoriasPublicas.php');
                
                if (!response.ok) {
                    throw new Error(`Error HTTP: ${response.status} - ${response.statusText}`);
                }
                
                const data = await response.json();
                console.log('Categorías cargadas:', data); // Debug
                
                if (!data.success) {
                    console.error('Error en respuesta de categorías:', data.error);
                    // Continuar sin mostrar error crítico para categorías
                    return;
                }
                
                categories = data.categories || [];
                
                const select = document.getElementById('categoryFilter');
                categories.forEach(category => {
                    const option = document.createElement('option');
                    option.value = category.id_categoria;
                    option.textContent = category.nombre;
                    select.appendChild(option);
                });
                
                if (categories.length === 0) {
                    console.warn('⚠️ No se encontraron categorías en la base de datos');
                }
                
            } catch (error) {
                console.error('Error cargando categorías:', error);
                // No bloquear la aplicación por error de categorías
            }
        }

        function displayProducts(productsToShow) {
            const grid = document.getElementById('productGrid');
            
            if (productsToShow.length === 0) {
                grid.innerHTML = '<div class="col-12 text-center"><p>No se encontraron productos</p></div>';
                return;
            }

            grid.innerHTML = productsToShow.map(product => `
                <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                    <div class="card product-card h-100">
                        <img src="${product.imagen || './public/img/no-image.svg'}" 
                             class="product-image" 
                             alt="${product.nombre}"
                             onerror="this.src='./public/img/no-image.svg'">
                        <div class="card-body d-flex flex-column">
                            <h6 class="card-title">${product.nombre}</h6>
                            <p class="card-text text-muted small">${product.descripcion || ''}</p>
                            <div class="mt-auto">
                                <div class="price mb-2">$${parseFloat(product.precio_venta).toFixed(2)}</div>
                                <div class="d-flex gap-2">
                                    <input type="number" 
                                           class="form-control form-control-sm" 
                                           id="qty-${product.id_producto}" 
                                           value="1" 
                                           min="1" 
                                           max="${product.stock}"
                                           style="width: 70px;">
                                    <button class="btn btn-success btn-sm flex-grow-1" 
                                            onclick="addToCart(${product.id_producto})"
                                            ${product.stock <= 0 ? 'disabled' : ''}>
                                        <i class="fas fa-plus"></i>
                                    </button>
                                </div>
                                <small class="text-muted">Stock: ${product.stock}</small>
                            </div>
                        </div>
                    </div>
                </div>
            `).join('');
        }

        function filterProducts() {
            const categoryFilter = document.getElementById('categoryFilter');
            const categoryId = categoryFilter ? categoryFilter.value : '';
            
            if (!categoryId) {
                displayProducts(products);
            } else {
                const filtered = products.filter(product => product.id_categoria == categoryId);
                displayProducts(filtered);
                
                // Scroll to products section after filtering
                document.getElementById('productos').scrollIntoView({ behavior: 'smooth' });
            }
            
            // Add visual indication that filter was applied
            if (categoryFilter) {
                categoryFilter.classList.add('border-success');
                setTimeout(() => {
                    categoryFilter.classList.remove('border-success');
                }, 1000);
            }
        }

        function addToCart(productId) {
            const product = products.find(p => p.id_producto == productId);
            const qtyInput = document.getElementById(`qty-${productId}`);
            const quantity = parseInt(qtyInput.value);

            if (!product || quantity <= 0) return;

            const existingItem = cart.find(item => item.id == productId);
            
            if (existingItem) {
                existingItem.quantity += quantity;
            } else {
                cart.push({
                    id: productId,
                    name: product.nombre,
                    price: parseFloat(product.precio_venta),
                    quantity: quantity,
                    image: product.imagen
                });
            }

            localStorage.setItem('cart', JSON.stringify(cart));
            updateCartBadge();
            
            // Mostrar feedback
            qtyInput.value = 1;
            showAlert(`${product.nombre} agregado al carrito`, 'success');
        }

        function updateCartBadge() {
            const badge = document.getElementById('cartBadge');
            const totalItems = cart.reduce((sum, item) => sum + item.quantity, 0);
            
            if (totalItems > 0) {
                badge.style.display = 'flex';
                badge.textContent = totalItems;
            } else {
                badge.style.display = 'none';
            }
        }

        function toggleCart() {
            updateCartDisplay();
            new bootstrap.Modal(document.getElementById('cartModal')).show();
        }

        function updateCartDisplay() {
            const cartItems = document.getElementById('cartItems');
            const cartTotal = document.getElementById('cartTotal');
            
            if (cart.length === 0) {
                cartItems.innerHTML = '<p class="text-center text-muted">Tu carrito está vacío</p>';
                cartTotal.textContent = '$0.00';
                return;
            }

            let total = 0;
            cartItems.innerHTML = cart.map(item => {
                const itemTotal = item.price * item.quantity;
                total += itemTotal;
                
                return `
                    <div class="d-flex align-items-center border-bottom py-3">
                        <img src="${item.image || './public/img/no-image.svg'}" 
                             class="me-3" 
                             width="60" 
                             height="60" 
                             style="object-fit: cover; border-radius: 8px;"
                             onerror="this.src='./public/img/no-image.svg'">
                        <div class="flex-grow-1">
                            <h6 class="mb-1">${item.name}</h6>
                            <div class="d-flex align-items-center gap-2">
                                <button class="btn btn-sm btn-outline-secondary" onclick="updateCartQuantity(${item.id}, -1)">-</button>
                                <span class="mx-2">${item.quantity}</span>
                                <button class="btn btn-sm btn-outline-secondary" onclick="updateCartQuantity(${item.id}, 1)">+</button>
                            </div>
                        </div>
                        <div class="text-end">
                            <div class="fw-bold">$${itemTotal.toFixed(2)}</div>
                            <button class="btn btn-sm btn-outline-danger" onclick="removeFromCart(${item.id})">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                `;
            }).join('');

            cartTotal.textContent = `$${total.toFixed(2)}`;
        }

        function updateCartQuantity(productId, change) {
            const item = cart.find(item => item.id == productId);
            if (!item) return;

            item.quantity += change;
            
            if (item.quantity <= 0) {
                removeFromCart(productId);
                return;
            }

            localStorage.setItem('cart', JSON.stringify(cart));
            updateCartBadge();
            updateCartDisplay();
        }

        function removeFromCart(productId) {
            cart = cart.filter(item => item.id != productId);
            localStorage.setItem('cart', JSON.stringify(cart));
            updateCartBadge();
            updateCartDisplay();
        }

        function proceedToCheckout() {
            if (cart.length === 0) {
                showAlert('Tu carrito está vacío', 'warning');
                return;
            }

            // Verificar si está logueado
            <?php if (isset($_SESSION['sesion_iniciada']) && $_SESSION['sesion_iniciada'] === "iniciado"): ?>
                // Usuario logueado, proceder al checkout
                window.location.href = 'index.php?opc=checkout';
            <?php else: ?>
                // Usuario no logueado, ir al login
                sessionStorage.setItem('redirectAfterLogin', 'checkout');
                showAlert('Debes iniciar sesión para continuar con la compra', 'info');
                setTimeout(() => {
                    window.location.href = 'index.php?opc=login';
                }, 2000);
            <?php endif; ?>
        }

        function showAlert(message, type = 'info') {
            const alertDiv = document.createElement('div');
            alertDiv.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
            alertDiv.style.cssText = 'top: 20px; right: 20px; z-index: 9999; max-width: 300px;';
            alertDiv.innerHTML = `
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;
            
            document.body.appendChild(alertDiv);
            
            setTimeout(() => {
                alertDiv.remove();
            }, 5000);
        }
    </script>
</body>
</html>