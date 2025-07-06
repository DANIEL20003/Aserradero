<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aserradero - Iniciar Sesión</title>
    <link rel="stylesheet" href="../css/Loging.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <div class="login-container">
        <div class="login-form">
            <div class="login-header">
                <div class="logo-container">
                    <i class="fas fa-tree"></i>
                    <h1>Aserradero</h1>
                </div>
                <h2>Iniciar Sesión</h2>
                <p>Ingresa a tu cuenta para continuar</p>
            </div>

            <form id="loginForm" action="../../controller/procesar_login.php" method="POST">
                <div class="form-group">
                    <label for="email">Correo Electrónico</label>
                    <div class="input-container">
                        <i class="fas fa-envelope"></i>
                        <input type="email" id="email" name="email" required placeholder="tu@email.com">
                    </div>
                </div>

                <div class="form-group">
                    <label for="password">Contraseña</label>
                    <div class="input-container">
                        <i class="fas fa-lock"></i>
                        <input type="password" id="password" name="password" required placeholder="Tu contraseña">
                        <button type="button" class="toggle-password" onclick="togglePassword()">
                            <i class="fas fa-eye" id="eyeIcon"></i>
                        </button>
                    </div>
                </div>

                

                <button type="submit" class="login-btn">
                    <i class="fas fa-sign-in-alt"></i>
                    Iniciar Sesión
                </button>

                <div class="register-section">
                    <p>¿No tienes cuenta?</p>
                    <a href="register.php" class="register-btn">
                        <i class="fas fa-user-plus"></i>
                        Registrarse
                    </a>
                </div>
            </form>

            <div class="error-message" id="errorMessage" style="display: none;">
                <i class="fas fa-exclamation-triangle"></i>
                <span id="errorText"></span>
            </div>
        </div>

        <div class="login-background">
            <div class="wood-pattern"></div>
            <div class="overlay"></div>
        </div>
    </div>

    <script>
        function togglePassword() {
            const passwordField = document.getElementById('password');
            const eyeIcon = document.getElementById('eyeIcon');
            
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

        // Validación del formulario
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;
            
            if (!email || !password) {
                e.preventDefault();
                showError('Por favor, complete todos los campos');
                return;
            }
            
            // Validar formato de email
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(email)) {
                e.preventDefault();
                showError('Por favor, ingrese un correo electrónico válido');
                return;
            }
        });

        function showError(message) {
            const errorDiv = document.getElementById('errorMessage');
            const errorText = document.getElementById('errorText');
            
            errorText.textContent = message;
            errorDiv.style.display = 'block';
            
            setTimeout(() => {
                errorDiv.style.display = 'none';
            }, 5000);
        }

        // Mostrar errores del servidor si existen
        <?php if (isset($_GET['error'])): ?>
            document.addEventListener('DOMContentLoaded', function() {
                const errorMessages = {
                    'invalid': 'Credenciales inválidas. Verifique su correo y contraseña.',
                    'missing': 'Por favor, complete todos los campos.',
                    'blocked': 'Su cuenta ha sido bloqueada. Contacte al administrador.',
                    'server': 'Error del servidor. Intente nuevamente más tarde.'
                };
                
                const errorType = '<?php echo htmlspecialchars($_GET['error']); ?>';
                if (errorMessages[errorType]) {
                    showError(errorMessages[errorType]);
                }
            });
        <?php endif; ?>
    </script>
</body>
</html>