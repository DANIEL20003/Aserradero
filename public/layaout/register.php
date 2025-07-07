<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aserradero - Registrarse</title>
    <link rel="stylesheet" href="../css/auth.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <div class="auth-container">
        <div class="auth-form">
            <div class="auth-header">
                <div class="logo-container">
                    <i class="fas fa-tree"></i>
                    <h1>Aserradero</h1>
                </div>
                <h2>Crear Cuenta</h2>
                <p>Regístrate para acceder al sistema</p>
            </div>

            <form id="registerForm" action="../../controller/procesar_registro.php" method="POST">
                <div class="form-group">
                    <label for="nombre">Nombre Completo</label>
                    <div class="input-container">
                        <i class="fas fa-user"></i>
                        <input type="text" id="nombre" name="nombre" required placeholder="Tu nombre completo">
                    </div>
                </div>

                <div class="form-group">
                    <label for="cedula">Cédula</label>
                    <div class="input-container">
                        <i class="fas fa-id-card"></i>
                        <input type="text" id="cedula" name="cedula" required placeholder="Número de cédula">
                    </div>
                </div>

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
                        <input type="password" id="password" name="password" required placeholder="Mínimo 8 caracteres">
                        <button type="button" class="toggle-password" onclick="togglePassword('password', 'eyeIcon1')">
                            <i class="fas fa-eye" id="eyeIcon1"></i>
                        </button>
                    </div>
                </div>

                <div class="form-group">
                    <label for="confirm_password">Confirmar Contraseña</label>
                    <div class="input-container">
                        <i class="fas fa-lock"></i>
                        <input type="password" id="confirm_password" name="confirm_password" required placeholder="Confirma tu contraseña">
                        <button type="button" class="toggle-password" onclick="togglePassword('confirm_password', 'eyeIcon2')">
                            <i class="fas fa-eye" id="eyeIcon2"></i>
                        </button>
                    </div>
                </div>

                <div class="form-options">
                    <div class="checkbox-group">
                        <input type="checkbox" id="terms" name="terms" required>
                        <label for="terms">Acepto los <a href="terms.php">términos y condiciones</a></label>
                    </div>
                </div>

                <button type="submit" class="auth-btn">
                    <i class="fas fa-user-plus"></i>
                    Registrarse
                </button>

                <div class="nav-section">
                    <p>¿Ya tienes cuenta?</p>
                    <a href="Login.php" class="nav-btn">
                        <i class="fas fa-sign-in-alt"></i>
                        Iniciar Sesión
                    </a>
                </div>
            </form>

            <div class="error-message message" id="errorMessage" style="display: none;">
                <i class="fas fa-exclamation-triangle"></i>
                <span id="errorText"></span>
            </div>

            <div class="success-message message" id="successMessage" style="display: none;">
                <i class="fas fa-check-circle"></i>
                <span id="successText"></span>
            </div>
        </div>

        <div class="auth-background">
            <div class="background-content">
                <h3>¡Únete a nosotros!</h3>
                <p>Crea tu cuenta y forma parte de nuestra comunidad del aserradero. Gestiona tus proyectos de manera eficiente.</p>
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

        // Validación del formulario
        document.getElementById('registerForm').addEventListener('submit', function(e) {
            const nombre = document.getElementById('nombre').value.trim();
            const cedula = document.getElementById('cedula').value.trim();
            const email = document.getElementById('email').value.trim();
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirm_password').value;
            const terms = document.getElementById('terms').checked;
            
            // Validar campos vacíos
            if (!nombre || !cedula || !email || !password || !confirmPassword) {
                e.preventDefault();
                showError('Por favor, complete todos los campos');
                return;
            }
            
            // Validar formato de cédula (solo números)
            const cedulaRegex = /^[0-9]+$/;
            if (!cedulaRegex.test(cedula)) {
                e.preventDefault();
                showError('La cédula debe contener solo números');
                return;
            }
            
            // Validar longitud de cédula
            if (cedula.length < 7 || cedula.length > 10) {
                e.preventDefault();
                showError('La cédula debe tener entre 7 y 10 dígitos');
                return;
            }
            
            // Validar formato de email
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(email)) {
                e.preventDefault();
                showError('Por favor, ingrese un correo electrónico válido');
                return;
            }
            
            // Validar longitud de contraseña
            if (password.length < 8) {
                e.preventDefault();
                showError('La contraseña debe tener al menos 8 caracteres');
                return;
            }
            
            // Validar que las contraseñas coincidan
            if (password !== confirmPassword) {
                e.preventDefault();
                showError('Las contraseñas no coinciden');
                return;
            }
            
            // Validar términos y condiciones
            if (!terms) {
                e.preventDefault();
                showError('Debe aceptar los términos y condiciones');
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

        function showSuccess(message) {
            const successDiv = document.getElementById('successMessage');
            const successText = document.getElementById('successText');
            
            successText.textContent = message;
            successDiv.style.display = 'block';
            
            setTimeout(() => {
                successDiv.style.display = 'none';
            }, 5000);
        }

        // Mostrar mensajes del servidor si existen
        <?php if (isset($_GET['error'])): ?>
            document.addEventListener('DOMContentLoaded', function() {
                const errorMessages = {
                    'email_exists': 'Este correo electrónico ya está registrado.',
                    'passwords_mismatch': 'Las contraseñas no coinciden.',
                    'weak_password': 'La contraseña debe tener al menos 8 caracteres.',
                    'invalid_email': 'Formato de correo electrónico inválido.',
                    'missing_fields': 'Por favor, complete todos los campos.',
                    'terms_not_accepted': 'Debe aceptar los términos y condiciones.',
                    'server': 'Error del servidor. Intente nuevamente más tarde.'
                };
                
                const errorType = '<?php echo htmlspecialchars($_GET['error']); ?>';
                if (errorMessages[errorType]) {
                    showError(errorMessages[errorType]);
                }
            });
        <?php endif; ?>

        <?php if (isset($_GET['success'])): ?>
            document.addEventListener('DOMContentLoaded', function() {
                const successMessages = {
                    'registered': 'Cuenta creada exitosamente. Ya puede iniciar sesión.'
                };
                
                const successType = '<?php echo htmlspecialchars($_GET['success']); ?>';
                if (successMessages[successType]) {
                    showSuccess(successMessages[successType]);
                }
            });
        <?php endif; ?>
    </script>
</body>
</html>
