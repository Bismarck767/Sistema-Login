<?php
session_start();

// Si ya está autenticado, redirigir
if (isset($_SESSION['autenticado']) && $_SESSION['autenticado'] === true) {
    header('Location: inicio.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistema de Usuarios</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
        }
        .login-container {
            background: white;
            border-radius: 15px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        .login-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            text-align: center;
            padding: 2rem;
        }
        .login-body {
            padding: 2rem;
        }
        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }
        .btn-login {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            padding: 12px;
            font-weight: 600;
        }
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-4">
                <div class="login-container">
                    <div class="login-header">
                        <i class="fas fa-user-circle fa-3x mb-3"></i>
                        <h3>Iniciar Sesión</h3>
                        <p class="mb-0">Bienvenido de vuelta</p>
                    </div>
                    
                    <div class="login-body">
                        <!-- Mostrar mensajes de error -->
                        <?php if (isset($_SESSION['error'])): ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                <?= htmlspecialchars($_SESSION['error']) ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                            <?php unset($_SESSION['error']); ?>
                        <?php endif; ?>
                        
                        <!-- Mostrar mensaje de éxito -->
                        <?php if (isset($_SESSION['exito'])): ?>
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="fas fa-check-circle me-2"></i>
                                <?= htmlspecialchars($_SESSION['exito']) ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                            <?php unset($_SESSION['exito']); ?>
                        <?php endif; ?>
                        
                        <form action="../../controller/UsuarioController.php?accion=login" method="POST" id="loginForm">
                            <div class="mb-3">
                                <label for="nombre_usuario" class="form-label">
                                    <i class="fas fa-user me-2"></i>Usuario
                                </label>
                                <input type="text" class="form-control" id="nombre_usuario" name="nombre_usuario" 
                                       placeholder="Ingresa tu usuario" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="clave" class="form-label">
                                    <i class="fas fa-lock me-2"></i>Contraseña
                                </label>
                                <div class="input-group">
                                    <input type="password" class="form-control" id="clave" name="clave" 
                                           placeholder="Ingresa tu contraseña" required>
                                    <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                        <i class="fas fa-eye" id="eyeIcon"></i>
                                    </button>
                                </div>
                            </div>
                            
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary btn-login">
                                    <i class="fas fa-sign-in-alt me-2"></i>Iniciar Sesión
                                </button>
                            </div>
                        </form>
                        
                        <div class="text-center mt-3">
                            <p class="mb-0">¿No tienes cuenta?</p>
                            <a href="registro.php" class="text-decoration-none">
                                <i class="fas fa-user-plus me-1"></i>Registrarse aquí
                            </a>
                        </div>
                        
                        <!-- Información de usuario de prueba -->
                        <div class="mt-4 p-3 bg-light rounded">
                            <small class="text-muted">
                                <strong>Usuario de prueba:</strong><br>
                                Usuario: admin<br>
                                Contraseña: 123456
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Toggle password visibility
        document.getElementById('togglePassword').addEventListener('click', function() {
            const passwordInput = document.getElementById('clave');
            const eyeIcon = document.getElementById('eyeIcon');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.classList.replace('fa-eye', 'fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                eyeIcon.classList.replace('fa-eye-slash', 'fa-eye');
            }
        });
        
        // Validación del formulario
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            const usuario = document.getElementById('nombre_usuario').value.trim();
            const clave = document.getElementById('clave').value;
            
            if (usuario === '' || clave === '') {
                e.preventDefault();
                alert('Por favor, complete todos los campos');
            }
        });
    </script>
</body>
</html>