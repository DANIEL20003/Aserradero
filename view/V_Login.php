<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - Aserradero</title>
    <link rel="stylesheet" href="./public/css/auth.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .alert {
            padding: 12px 16px;
            margin-bottom: 20px;
            border-radius: 8px;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .alert-error {
            background-color: #fee;
            color: #c53030;
            border: 1px solid #feb2b2;
        }
        .alert-success {
            background-color: #f0fff4;
            color: #22543d;
            border: 1px solid #9ae6b4;
        }
        .alert-info {
            background-color: #ebf8ff;
            color: #2a69ac;
            border: 1px solid #90cdf4;
        }
        .loading {
            display: none;
            opacity: 0.7;
        }
        .loading .fas {
            animation: spin 1s linear infinite;
        }
        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
    </style>
</head>
<body>
    <div class="auth-container">
        <div class="auth-form">
            <div class="auth-header">
                <div class="logo-container">
                    <i class="fas fa-tree"></i>
                    <h1>Aserradero</h1>
                </div>
                <h2>Iniciar Sesión</h2>
                <p>Accede a tu cuenta para gestionar el sistema</p>
            </div>

            <!-- Mensajes de alerta -->
            <div id="alertContainer"></div>

            <form action="./model/M_Login.php" method="POST" id="loginForm">
                <div class="form-group">
                    <label for="email">Correo Electrónico</label>
                    <div class="input-container">
                        <i class="fas fa-envelope"></i>
                        <input type="email" 
                               id="email" 
                               name="email" 
                               required 
                               placeholder="admin@aserradero.com"
                               autocomplete="email">
                    </div>
                    <small class="form-hint">Ingresa el correo registrado en el sistema</small>
                </div>

                <div class="form-group">
                    <label for="password">Contraseña</label>
                    <div class="input-container">
                        <i class="fas fa-lock"></i>
                        <input type="password" 
                               id="password" 
                               name="password" 
                               required 
                               placeholder="Tu contraseña"
                               autocomplete="current-password">
                        <button type="button" class="toggle-password" onclick="togglePassword('password', 'eyeIcon')">
                            <i class="fas fa-eye" id="eyeIcon"></i>
                        </button>
                    </div>
                    <small class="form-hint">Mínimo 6 caracteres</small>
                </div>

                <div class="form-options">
                    <div class="checkbox-group">
                        <input type="checkbox" id="remember" name="remember">
                        <label for="remember">Recordar sesión</label>
                    </div>
                    <a href="#" class="forgot-password" onclick="showForgotPassword()">¿Olvidaste tu contraseña?</a>
                </div>

                <button type="submit" class="auth-btn" id="loginBtn">
                    <span class="btn-text">
                        <i class="fas fa-sign-in-alt"></i>
                        Iniciar Sesión
                    </span>
                    <span class="loading">
                        <i class="fas fa-spinner"></i>
                        Validando...
                    </span>
                </button>

                <div class="nav-section">
                    <p>¿No tienes cuenta?</p>
                    <a href="index.php?opc=registro" class="nav-btn">
                        <i class="fas fa-user-plus"></i>
                        Registrarse
                    </a>
                </div>

                <!-- Credenciales de demostración -->
                <div class="demo-credentials">
                    <h4><i class="fas fa-info-circle"></i> Credenciales de Demostración:</h4>
                    <div class="demo-item">
                        <strong>Administrador:</strong>
                        <button type="button" onclick="fillDemoCredentials('admin@aserradero.com', 'admin123')" class="demo-btn">
                            <i class="fas fa-user-shield"></i>
                            Usar credenciales de admin
                        </button>
                    </div>
                </div>
            </form>

            <div class="auth-links">
                <a href="index.php">← Volver al inicio</a>
            </div>
        </div>

        <div class="auth-background">
            <div class="background-content">
                <h3>¡Bienvenido de vuelta!</h3>
                <p>Inicia sesión para acceder a todas las funcionalidades del sistema de gestión del aserradero.</p>
                <div class="features-list">
                    <div class="feature">
                        <i class="fas fa-boxes"></i>
                        <span>Gestión de Inventario</span>
                    </div>
                    <div class="feature">
                        <i class="fas fa-shopping-cart"></i>
                        <span>Control de Ventas</span>
                    </div>
                    <div class="feature">
                        <i class="fas fa-users"></i>
                        <span>Administración de Clientes</span>
                    </div>
                    <div class="feature">
                        <i class="fas fa-chart-bar"></i>
                        <span>Reportes y Estadísticas</span>
                    </div>
                </div>
            </div>
            <div class="wood-pattern"></div>
            <div class="overlay"></div>
        </div>
    </div>

    <script>
        function togglePassword(fieldId, iconId) {
            const passwordField = document.getElementById(fieldId);
            const eyeIcon = document.getElementById(iconId);
            
            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                eyeIcon.classList.remove('fa-eye');
                eyeIcon.classList.add('fa-eye-slash');
            } else {
                passwordField.type = 'password';
                eyeIcon.classList.remove('fa-eye-slash');
                eyeIcon.classList.add('fa-eye');
            }
        }

        function showAlert(message, type = 'error') {
            const alertContainer = document.getElementById('alertContainer');
            const icon = type === 'error' ? 'fas fa-exclamation-triangle' : 
                        type === 'success' ? 'fas fa-check-circle' : 'fas fa-info-circle';
            
            alertContainer.innerHTML = `
                <div class="alert alert-${type}">
                    <i class="${icon}"></i>
                    <span>${message}</span>
                </div>
            `;
            
            // Auto-hide after 5 seconds
            setTimeout(() => {
                alertContainer.innerHTML = '';
            }, 5000);
        }

        function fillDemoCredentials(email, password) {
            document.getElementById('email').value = email;
            document.getElementById('password').value = password;
            showAlert('Credenciales de demostración cargadas. Haz clic en "Iniciar Sesión".', 'info');
        }

        function showForgotPassword() {
            showAlert('Para recuperar tu contraseña, contacta al administrador del sistema.', 'info');
        }

        // Validación y manejo del formulario
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            const email = document.getElementById('email').value.trim();
            const password = document.getElementById('password').value;
            const loginBtn = document.getElementById('loginBtn');
            
            // Limpiar alertas previas
            document.getElementById('alertContainer').innerHTML = '';
            
            // Verificar si hay redirección pendiente y guardarla en sesión PHP
            const redirectAfterLogin = sessionStorage.getItem('redirectAfterLogin');
            if (redirectAfterLogin) {
                // Agregar campo hidden para enviar la redirección
                let redirectInput = document.querySelector('input[name="redirect_after_login"]');
                if (!redirectInput) {
                    redirectInput = document.createElement('input');
                    redirectInput.type = 'hidden';
                    redirectInput.name = 'redirect_after_login';
                    this.appendChild(redirectInput);
                }
                redirectInput.value = redirectAfterLogin;
                sessionStorage.removeItem('redirectAfterLogin');
            }
            
            // Validaciones
            if (!email || !password) {
                e.preventDefault();
                showAlert('Por favor, complete todos los campos obligatorios.');
                return;
            }
            
            if (!email.includes('@') || !email.includes('.')) {
                e.preventDefault();
                showAlert('Por favor, ingrese un correo electrónico válido.');
                document.getElementById('email').focus();
                return;
            }
            
            if (password.length < 6) {
                e.preventDefault();
                showAlert('La contraseña debe tener al menos 6 caracteres.');
                document.getElementById('password').focus();
                return;
            }
            
            // Mostrar estado de carga
            loginBtn.querySelector('.btn-text').style.display = 'none';
            loginBtn.querySelector('.loading').style.display = 'flex';
            loginBtn.disabled = true;
        });

        // Auto-focus en el campo de email al cargar
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('email').focus();
            
            // Verificar si hay parámetros de error en la URL
            const urlParams = new URLSearchParams(window.location.search);
            const error = urlParams.get('error');
            const message = urlParams.get('message');
            
            if (error === 'invalid') {
                showAlert('Credenciales incorrectas. Verifique su email y contraseña.');
            } else if (error === 'empty_fields') {
                showAlert('Por favor, complete todos los campos obligatorios.');
            } else if (error === 'database') {
                showAlert('Error de conexión. Intente nuevamente más tarde.');
            } else if (error === 'inactive') {
                showAlert('Su cuenta está inactiva. Contacte al administrador.');
            } else if (message === 'logout') {
                showAlert('Sesión cerrada exitosamente.', 'success');
            } else if (message === 'registered') {
                showAlert('Registro exitoso. Ya puede iniciar sesión.', 'success');
            }
        });

        // Limpiar estado de carga si hay error
        window.addEventListener('pageshow', function() {
            const loginBtn = document.getElementById('loginBtn');
            loginBtn.querySelector('.btn-text').style.display = 'flex';
            loginBtn.querySelector('.loading').style.display = 'none';
            loginBtn.disabled = false;
        });
    </script>
</body>
</html>