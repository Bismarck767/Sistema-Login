<?php
session_start();

// Si ya está autenticado, redirigir
if (isset($_SESSION['autenticado']) && $_SESSION['autenticado'] === true) {
    header('Location: inicio.php');
    exit;
}

// Recuperar datos del formulario si hay errores
$datos_form = $_SESSION['datos_form'] ?? [];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro - Sistema de Usuarios</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            padding: 2rem 0;
        }
        .registro-container {
            background: white;
            border-radius: 15px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        .registro-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            text-align: center;
            padding: 2rem;
        }
        .registro-body {
            padding: 2rem;
        }
        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }
        .btn-registro {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            padding: 12px;
            font-weight: 600;
        }
        .btn-registro:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }
        .password-strength {
            height: 4px;
            margin-top: 5px;
            border-radius: 2px;
            transition: all 0.3s;
        }
        .strength-weak { background-color: #dc3545; }
        .strength-medium { background-color: #ffc107; }
        .strength-strong { background-color: #28a745; }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="registro-container">
                    <div class="registro-header">
                        <i class="fas fa-user-plus fa-3x mb-3"></i>
                        <h3>Crear Cuenta</h3>
                        <p class="mb-0">Únete a nuestro sistema</p>
                    </div>
                    
                    <div class="registro-body">
                        <!-- Mostrar errores -->
                        <?php if (isset($_SESSION['errores'])): ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                <strong>Errores encontrados:</strong>
                                <ul class="mb-0 mt-2">
                                    <?php foreach ($_SESSION['errores'] as $error): ?>
                                        <li><?= htmlspecialchars($error) ?></li>
                                    <?php endforeach; ?>
                                </ul>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                            <?php unset($_SESSION['errores']); ?>
                        <?php endif; ?>
                        
                        <!-- Mostrar error general -->
                        <?php if (isset($_SESSION['error'])): ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                <?= htmlspecialchars($_SESSION['error']) ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                            <?php unset($_SESSION['error']); ?>
                        <?php endif; ?>
                        
                        <form action="../../controller/UsuarioController.php?accion=registro" method="POST" id="registroForm">
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <label for="nombre_usuario" class="form-label">
                                        <i class="fas fa-user me-2"></i>Nombre de Usuario *
                                    </label>
                                    <input type="text" class="form-control" id="nombre_usuario" name="nombre_usuario" 
                                           placeholder="Mínimo 3 caracteres" required minlength="3"
                                           value="<?= htmlspecialchars($datos_form['nombre_usuario'] ?? '') ?>">
                                    <div class="form-text">El nombre de usuario debe ser único</div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="clave" class="form-label">
                                        <i class="fas fa-lock me-2"></i>Contraseña *
                                    </label>
                                    <div class="input-group">
                                        <input type="password" class="form-control" id="clave" name="clave" 
                                               placeholder="Mínimo 6 caracteres" required minlength="6">
                                        <button class="btn btn-outline-secondary" type="button" id="togglePassword1">
                                            <i class="fas fa-eye" id="eyeIcon1"></i>
                                        </button>
                                    </div>
                                    <div class="password-strength" id="passwordStrength"></div>
                                    <div class="form-text" id="passwordHelp">Mínimo 6 caracteres</div>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="confirmar_clave" class="form-label">
                                        <i class="fas fa-lock me-2"></i>Confirmar Contraseña *
                                    </label>
                                    <div class="input-group">
                                        <input type="password" class="form-control" id="confirmar_clave" name="confirmar_clave" 
                                               placeholder="Repita la contraseña" required>
                                        <button class="btn btn-outline-secondary" type="button" id="togglePassword2">
                                            <i class="fas fa-eye" id="eyeIcon2"></i>
                                        </button>
                                    </div>
                                    <div class="form-text" id="confirmHelp"></div>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="estado" class="form-label">
                                    <i class="fas fa-toggle-on me-2"></i>Estado
                                </label>
                                <select class="form-select" id="estado" name="estado">
                                    <option value="activo" <?= ($datos_form['estado'] ?? 'activo') === 'activo' ? 'selected' : '' ?>>
                                        Activo
                                    </option>
                                    <option value="inactivo" <?= ($datos_form['estado'] ?? '') === 'inactivo' ? 'selected' : '' ?>>
                                        Inactivo
                                    </option>
                                </select>
                            </div>
                            
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary btn-registro">
                                    <i class="fas fa-user-plus me-2"></i>Crear Cuenta
                                </button>
                            </div>
                        </form>
                        
                        <div class="text-center mt-3">
                            <p class="mb-0">¿Ya tienes cuenta?</p>
                            <a href="login.php" class="text-decoration-none">
                                <i class="fas fa-sign-in-alt me-1"></i>Iniciar sesión aquí
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Toggle password visibility
        function togglePasswordVisibility(inputId, iconId) {
            const passwordInput = document.getElementById(inputId);
            const eyeIcon = document.getElementById(iconId);
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.classList.replace('fa-eye', 'fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                eyeIcon.classList.replace('fa-eye-slash', 'fa-eye');
            }
        }
        
        document.getElementById('togglePassword1').addEventListener('click', () => {
            togglePasswordVisibility('clave', 'eyeIcon1');
        });
        
        document.getElementById('togglePassword2').addEventListener('click', () => {
            togglePasswordVisibility('confirmar_clave', 'eyeIcon2');
        });
        
        // Password strength indicator
        document.getElementById('clave').addEventListener('input', function() {
            const password = this.value;
            const strengthBar = document.getElementById('passwordStrength');
            const helpText = document.getElementById('passwordHelp');
            
            let strength = 0;
            let message = '';
            
            if (password.length >= 6) strength++;
            if (password.match(/[a-z]/) && password.match(/[A-Z]/)) strength++;
            if (password.match(/[0-9]/)) strength++;
            if (password.match(/[^a-zA-Z0-9]/)) strength++;
            
            strengthBar.style.width = (strength * 25) + '%';
            
            if (strength < 2) {
                strengthBar.className = 'password-strength strength-weak';
                message = 'Contraseña débil';
            } else if (strength < 3) {
                strengthBar.className = 'password-strength strength-medium';
                message = 'Contraseña moderada';
            } else {
                strengthBar.className = 'password-strength strength-strong';
                message = 'Contraseña fuerte';
            }
            
            helpText.textContent = message;
        });
        
        // Confirm password validation
        document.getElementById('confirmar_clave').addEventListener('input', function() {
            const password = document.getElementById('clave').value;
            const confirmPassword = this.value;
            const helpText = document.getElementById('confirmHelp');
            
            if (confirmPassword === '') {
                helpText.textContent = '';
                this.classList.remove('is-valid', 'is-invalid');
            } else if (password === confirmPassword) {
                helpText.textContent = 'Las contraseñas coinciden';
                helpText.style.color = '#28a745';
                this.classList.remove('is-invalid');
                this.classList.add('is-valid');
            } else {
                helpText.textContent = 'Las contraseñas no coinciden';
                helpText.style.color = '#dc3545';
                this.classList.remove('is-valid');
                this.classList.add('is-invalid');
            }
        });
        
        // Form validation
        document.getElementById('registroForm').addEventListener('submit', function(e) {
            const usuario = document.getElementById('nombre_usuario').value.trim();
            const clave = document.getElementById('clave').value;
            const confirmarClave = document.getElementById('confirmar_clave').value;
            
            if (usuario.length < 3) {
                e.preventDefault();
                alert('El nombre de usuario debe tener al menos 3 caracteres');
                return;
            }
            
            if (clave.length < 6) {
                e.preventDefault();
                alert('La contraseña debe tener al menos 6 caracteres');
                return;
            }
            
            if (clave !== confirmarClave) {
                e.preventDefault();
                alert('Las contraseñas no coinciden');
                return;
            }
        });
    </script>
</body>
</html>
<?php
// Limpiar datos del formulario después de mostrarlos
unset($_SESSION['datos_form']);
?>