<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aserradero - Registrarse</title>
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
        .password-strength {
            margin-top: 5px;
            font-size: 12px;
        }
        .strength-bar {
            height: 4px;
            border-radius: 2px;
            margin-top: 3px;
            transition: all 0.3s;
        }
        .strength-weak { background-color: #e53e3e; width: 33%; }
        .strength-medium { background-color: #dd6b20; width: 66%; }
        .strength-strong { background-color: #38a169; width: 100%; }
        .form-hint {
            font-size: 12px;
            color: #666;
            margin-top: 4px;
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
                <h2>Crear Cuenta</h2>
                <p>Regístrate para acceder al sistema de gestión</p>
            </div>

            <!-- Mensajes de alerta -->
            <div id="alertContainer"></div>

            <form id="registerForm" action="./model/M_Registro.php" method="POST">
                <div class="form-group">
                    <label for="nombre">Nombre Completo *</label>
                    <div class="input-container">
                        <i class="fas fa-user"></i>
                        <input type="text" 
                               id="nombre" 
                               name="nombre" 
                               required 
                               placeholder="Ej: Juan Pérez García"
                               autocomplete="name"
                               maxlength="100">
                    </div>
                    <small class="form-hint">Ingresa tu nombre completo como aparece en tu documento</small>
                </div>

                <div class="form-group">
                    <label for="cedula">Cédula *</label>
                    <div class="input-container">
                        <i class="fas fa-id-card"></i>
                        <input type="text" 
                               id="cedula" 
                               name="cedula" 
                               required 
                               placeholder="1234567890"
                               pattern="[0-9]+"
                               title="Solo números"
                               maxlength="20">
                    </div>
                    <small class="form-hint">Solo números, mínimo 8 dígitos</small>
                </div>

                <div class="form-group">
                    <label for="email">Correo Electrónico *</label>
                    <div class="input-container">
                        <i class="fas fa-envelope"></i>
                        <input type="email" 
                               id="email" 
                               name="email" 
                               required 
                               placeholder="ejemplo@correo.com"
                               autocomplete="email"
                               maxlength="100">
                    </div>
                    <small class="form-hint">Usarás este correo para iniciar sesión</small>
                </div>

                <div class="form-group">
                    <label for="password">Contraseña *</label>
                    <div class="input-container">
                        <i class="fas fa-lock"></i>
                        <input type="password" 
                               id="password" 
                               name="password" 
                               required 
                               placeholder="Mínimo 8 caracteres"
                               autocomplete="new-password"
                               minlength="8">
                        <button type="button" class="toggle-password" onclick="togglePassword('password', 'eyeIcon1')">
                            <i class="fas fa-eye" id="eyeIcon1"></i>
                        </button>
                    </div>
                    <div class="password-strength">
                        <div id="strengthText">Fortaleza de la contraseña</div>
                        <div class="strength-bar" id="strengthBar"></div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="confirm_password">Confirmar Contraseña *</label>
                    <div class="input-container">
                        <i class="fas fa-lock"></i>
                        <input type="password" 
                               id="confirm_password" 
                               name="confirm_password" 
                               required 
                               placeholder="Repite tu contraseña"
                               autocomplete="new-password"
                               minlength="8">
                        <button type="button" class="toggle-password" onclick="togglePassword('confirm_password', 'eyeIcon2')">
                            <i class="fas fa-eye" id="eyeIcon2"></i>
                        </button>
                    </div>
                    <small class="form-hint" id="passwordMatch"></small>
                </div>

                <div class="form-options">
                    <div class="checkbox-group">
                        <input type="checkbox" id="terms" name="terms" required>
                        <label for="terms">
                            Acepto los <a href="#" onclick="showTerms()">términos y condiciones</a> *
                        </label>
                    </div>
                    <div class="checkbox-group">
                        <input type="checkbox" id="newsletter" name="newsletter">
                        <label for="newsletter">
                            Quiero recibir notificaciones sobre actualizaciones del sistema
                        </label>
                    </div>
                </div>

                <button type="submit" class="auth-btn" id="registerBtn">
                    <span class="btn-text">
                        <i class="fas fa-user-plus"></i>
                        Crear Cuenta
                    </span>
                    <span class="loading">
                        <i class="fas fa-spinner"></i>
                        Registrando...
                    </span>
                </button>

                <div class="nav-section">
                    <p>¿Ya tienes cuenta?</p>
                    <a href="index.php?opc=login" class="nav-btn">
                        <i class="fas fa-sign-in-alt"></i>
                        Iniciar Sesión
                    </a>
                </div>
            </form>

            <div class="auth-links">
                <a href="index.php">← Volver al inicio</a>
            </div>
        </div>

        <div class="auth-background">
            <div class="background-content">
                <h3>¡Únete a nosotros!</h3>
                <p>Crea tu cuenta y forma parte de nuestra comunidad del aserradero. Gestiona tus proyectos de manera eficiente.</p>
                <div class="benefits-list">
                    <div class="benefit">
                        <i class="fas fa-shield-alt"></i>
                        <span>Datos seguros y protegidos</span>
                    </div>
                    <div class="benefit">
                        <i class="fas fa-clock"></i>
                        <span>Acceso 24/7 al sistema</span>
                    </div>
                    <div class="benefit">
                        <i class="fas fa-mobile-alt"></i>
                        <span>Compatible con móviles</span>
                    </div>
                    <div class="benefit">
                        <i class="fas fa-headset"></i>
                        <span>Soporte técnico disponible</span>
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
            
            // Auto-hide after 6 seconds
            setTimeout(() => {
                alertContainer.innerHTML = '';
            }, 6000);
        }

        function showTerms() {
            alert('Términos y Condiciones:\n\n1. El usuario se compromete a usar el sistema de manera responsable.\n2. No compartir credenciales con terceros.\n3. Reportar cualquier problema de seguridad.\n4. Los datos personales serán protegidos según la ley.\n5. El acceso puede ser revocado por mal uso del sistema.');
        }

        function checkPasswordStrength(password) {
            const strengthBar = document.getElementById('strengthBar');
            const strengthText = document.getElementById('strengthText');
            
            let strength = 0;
            let feedback = [];
            
            if (password.length >= 8) strength++;
            else feedback.push('mínimo 8 caracteres');
            
            if (/[a-z]/.test(password)) strength++;
            else feedback.push('una letra minúscula');
            
            if (/[A-Z]/.test(password)) strength++;
            else feedback.push('una letra mayúscula');
            
            if (/[0-9]/.test(password)) strength++;
            else feedback.push('un número');
            
            if (/[^A-Za-z0-9]/.test(password)) strength++;
            else feedback.push('un carácter especial');
            
            strengthBar.className = 'strength-bar';
            
            if (strength < 2) {
                strengthBar.classList.add('strength-weak');
                strengthText.textContent = 'Débil - Falta: ' + feedback.join(', ');
                strengthText.style.color = '#e53e3e';
            } else if (strength < 4) {
                strengthBar.classList.add('strength-medium');
                strengthText.textContent = 'Media - Falta: ' + feedback.join(', ');
                strengthText.style.color = '#dd6b20';
            } else {
                strengthBar.classList.add('strength-strong');
                strengthText.textContent = 'Fuerte - ¡Excelente contraseña!';
                strengthText.style.color = '#38a169';
            }
            
            return strength >= 3;
        }

        function checkPasswordMatch() {
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirm_password').value;
            const matchElement = document.getElementById('passwordMatch');
            
            if (confirmPassword.length === 0) {
                matchElement.textContent = '';
                return false;
            }
            
            if (password === confirmPassword) {
                matchElement.textContent = '✓ Las contraseñas coinciden';
                matchElement.style.color = '#38a169';
                return true;
            } else {
                matchElement.textContent = '✗ Las contraseñas no coinciden';
                matchElement.style.color = '#e53e3e';
                return false;
            }
        }

        // Event listeners
        document.getElementById('password').addEventListener('input', function() {
            checkPasswordStrength(this.value);
            checkPasswordMatch();
        });

        document.getElementById('confirm_password').addEventListener('input', checkPasswordMatch);

        // Validar solo números en cédula
        document.getElementById('cedula').addEventListener('input', function(e) {
            this.value = this.value.replace(/[^0-9]/g, '');
        });

        // Validación del formulario
        document.getElementById('registerForm').addEventListener('submit', function(e) {
            const nombre = document.getElementById('nombre').value.trim();
            const cedula = document.getElementById('cedula').value.trim();
            const email = document.getElementById('email').value.trim();
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirm_password').value;
            const terms = document.getElementById('terms').checked;
            const registerBtn = document.getElementById('registerBtn');
            
            // Limpiar alertas previas
            document.getElementById('alertContainer').innerHTML = '';
            
            // Validar campos vacíos
            if (!nombre || !cedula || !email || !password || !confirmPassword) {
                e.preventDefault();
                showAlert('Por favor, complete todos los campos obligatorios (*)');
                return;
            }
            
            // Validar nombre
            if (nombre.length < 2) {
                e.preventDefault();
                showAlert('El nombre debe tener al menos 2 caracteres');
                document.getElementById('nombre').focus();
                return;
            }
            
            // Validar cédula
            if (cedula.length < 8 || cedula.length > 20) {
                e.preventDefault();
                showAlert('La cédula debe tener entre 8 y 20 dígitos');
                document.getElementById('cedula').focus();
                return;
            }
            
            // Validar formato de email
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(email)) {
                e.preventDefault();
                showAlert('Por favor, ingrese un correo electrónico válido');
                document.getElementById('email').focus();
                return;
            }
            
            // Validar fortaleza de contraseña
            if (!checkPasswordStrength(password)) {
                e.preventDefault();
                showAlert('La contraseña debe ser más fuerte. Revise los requisitos.');
                document.getElementById('password').focus();
                return;
            }
            
            // Validar que las contraseñas coincidan
            if (password !== confirmPassword) {
                e.preventDefault();
                showAlert('Las contraseñas no coinciden');
                document.getElementById('confirm_password').focus();
                return;
            }
            
            // Validar términos y condiciones
            if (!terms) {
                e.preventDefault();
                showAlert('Debe aceptar los términos y condiciones para continuar');
                document.getElementById('terms').focus();
                return;
            }
            
            // Mostrar estado de carga
            registerBtn.querySelector('.btn-text').style.display = 'none';
            registerBtn.querySelector('.loading').style.display = 'flex';
            registerBtn.disabled = true;
        });

        // Auto-focus en el campo de nombre al cargar
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('nombre').focus();
            
            // Verificar si hay parámetros de error en la URL
            const urlParams = new URLSearchParams(window.location.search);
            const error = urlParams.get('error');
            const success = urlParams.get('success');
            
            if (error) {
                const errorMessages = {
                    'empty_fields': 'Por favor, complete todos los campos obligatorios.',
                    'missing_data': 'Faltan datos requeridos.',
                    'invalid_email': 'Formato de correo electrónico inválido.',
                    'password_mismatch': 'Las contraseñas no coinciden.',
                    'password_short': 'La contraseña debe tener al menos 6 caracteres.',
                    'invalid_cedula': 'La cédula debe contener solo números y tener entre 8 y 12 dígitos.',
                    'email_exists': 'Ya existe un usuario registrado con este correo electrónico.',
                    'cedula_exists': 'Ya existe un usuario registrado con esta cédula.',
                    'database': 'Error al registrar el usuario. Intente nuevamente.',
                    'passwords_mismatch': 'Las contraseñas no coinciden.',
                    'weak_password': 'La contraseña no cumple con los requisitos mínimos.',
                    'missing_fields': 'Por favor, complete todos los campos obligatorios.',
                    'terms_not_accepted': 'Debe aceptar los términos y condiciones.',
                    'server': 'Error del servidor. Intente nuevamente más tarde.'
                };
                
                showAlert(errorMessages[error] || 'Error desconocido');
            }
            
            if (success === 'registered') {
                showAlert('¡Cuenta creada exitosamente! Ya puede iniciar sesión.', 'success');
            }
        });

        // Limpiar estado de carga si hay error
        window.addEventListener('pageshow', function() {
            const registerBtn = document.getElementById('registerBtn');
            registerBtn.querySelector('.btn-text').style.display = 'flex';
            registerBtn.querySelector('.loading').style.display = 'none';
            registerBtn.disabled = false;
        });
    </script>
</body>
</html>
