<?php
session_start();

// Verificar que el usuario esté autenticado
if (!isset($_SESSION['autenticado']) || $_SESSION['autenticado'] !== true) {
    header('Location: ../login.php');
    exit;
}

$usuario = $_SESSION['usuario'];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Sistema de Usuarios</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: #f8f9fa;
        }
        .navbar-brand {
            font-weight: 700;
        }
        .welcome-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 15px;
            padding: 2rem;
            margin-bottom: 2rem;
        }
        .stats-card {
            background: white;
            border-radius: 10px;
            padding: 1.5rem;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 1rem;
            transition: transform 0.3s;
        }
        .stats-card:hover {
            transform: translateY(-5px);
        }
        .stats-icon {
            font-size: 2.5rem;
            margin-bottom: 1rem;
        }
        .sidebar {
            background: white;
            min-height: calc(100vh - 56px);
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
        }
        .nav-link {
            color: #666;
            padding: 1rem 1.5rem;
            border-left: 3px solid transparent;
            transition: all 0.3s;
        }
        .nav-link:hover, .nav-link.active {
            color: #667eea;
            background: #f8f9ff;
            border-left-color: #667eea;
        }
        .user-avatar {
            width: 80px;
            height: 80px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            margin-bottom: 1rem;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">
                <i class="fas fa-users me-2"></i>Sistema de Usuarios
            </a>
            
            <div class="navbar-nav ms-auto">
                <div class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button" 
                       data-bs-toggle="dropdown">
                        <i class="fas fa-user-circle me-2"></i>
                        <?= htmlspecialchars($usuario['nombre_usuario']) ?>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#perfilModal">
                                <i class="fas fa-user me-2"></i>Mi Perfil
                            </a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <a class="dropdown-item text-danger" href="#" onclick="confirmarLogout()">
                                <i class="fas fa-sign-out-alt me-2"></i>Cerrar Sesión
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>

    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 px-0 sidebar">
                <div class="p-3">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link active" href="#dashboard" onclick="mostrarSeccion('dashboard')">
                                <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#perfil" onclick="mostrarSeccion('perfil')">
                                <i class="fas fa-user me-2"></i>Mi Perfil
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#configuracion" onclick="mostrarSeccion('configuracion')">
                                <i class="fas fa-cog me-2"></i>Configuración
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Main content -->
            <div class="col-md-9 col-lg-10">
                <div class="p-4">
                    <!-- Dashboard Section -->
                    <div id="dashboard-section">
                        <!-- Welcome Card -->
                        <div class="welcome-card">
                            <div class="row align-items-center">
                                <div class="col-md-8">
                                    <h2 class="mb-3">
                                        <i class="fas fa-hand-wave me-2"></i>
                                        ¡Bienvenido, <?= htmlspecialchars($usuario['nombre_usuario']) ?>!
                                    </h2>
                                    <p class="mb-3">Has iniciado sesión exitosamente en el sistema.</p>
                                    <p class="mb-0">
                                        <i class="fas fa-calendar me-2"></i>
                                        Último acceso: <?= date('d/m/Y H:i:s') ?>
                                    </p>
                                </div>
                                <div class="col-md-4 text-center">
                                    <div class="user-avatar">
                                        <i class="fas fa-user"></i>
                                    </div>
                                    <span class="badge bg-success fs-6">
                                        <i class="fas fa-circle me-1"></i>En línea
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Statistics Cards -->
                        <div class="row">
                            <div class="col-md-3">
                                <div class="stats-card text-center">
                                    <div class="stats-icon text-primary">
                                        <i class="fas fa-user-check"></i>
                                    </div>
                                    <h5>Estado</h5>
                                    <span class="badge bg-<?= $usuario['estado'] === 'activo' ? 'success' : 'warning' ?> fs-6">
                                        <?= ucfirst($usuario['estado']) ?>
                                    </span>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="stats-card text-center">
                                    <div class="stats-icon text-success">
                                        <i class="fas fa-clock"></i>
                                    </div>
                                    <h5>Tiempo en línea</h5>
                                    <p class="mb-0" id="tiempoEnLinea">00:00:00</p>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="stats-card text-center">
                                    <div class="stats-icon text-warning">
                                        <i class="fas fa-shield-alt"></i>
                                    </div>
                                    <h5>Seguridad</h5>
                                    <span class="badge bg-success fs-6">
                                        <i class="fas fa-check me-1"></i>Protegido
                                    </span>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="stats-card text-center">
                                    <div class="stats-icon text-info">
                                        <i class="fas fa-key"></i>
                                    </div>
                                    <h5>ID Usuario</h5>
                                    <p class="mb-0">#<?= $usuario['id'] ?></p>
                                </div>
                            </div>
                        </div>

                        <!-- Recent Activity -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="mb-0">
                                            <i class="fas fa-history me-2"></i>Actividad Reciente
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="list-group list-group-flush">
                                            <div class="list-group-item d-flex align-items-center">
                                                <div class="me-3">
                                                    <i class="fas fa-sign-in-alt text-success"></i>
                                                </div>
                                                <div>
                                                    <h6 class="mb-1">Inicio de sesión exitoso</h6>
                                                    <small class="text-muted">Hoy a las <?= date('H:i:s') ?></small>
                                                </div>
                                            </div>
                                            <div class="list-group-item d-flex align-items-center">
                                                <div class="me-3">
                                                    <i class="fas fa-shield-alt text-info"></i>
                                                </div>
                                                <div>
                                                    <h6 class="mb-1">Sesión segura verificada</h6>
                                                    <small class="text-muted">Sistema validado correctamente</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Perfil Section -->
                    <div id="perfil-section" style="display: none;">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">
                                    <i class="fas fa-user me-2"></i>Información del Perfil
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4 text-center">
                                        <div class="user-avatar mx-auto" style="background: #667eea;">
                                            <i class="fas fa-user text-white"></i>
                                        </div>
                                        <h5><?= htmlspecialchars($usuario['nombre_usuario']) ?></h5>
                                        <span class="badge bg-<?= $usuario['estado'] === 'activo' ? 'success' : 'warning' ?>">
                                            <?= ucfirst($usuario['estado']) ?>
                                        </span>
                                    </div>
                                    <div class="col-md-8">
                                        <h6>Detalles de la cuenta</h6>
                                        <table class="table table-borderless">
                                            <tr>
                                                <td><strong>ID:</strong></td>
                                                <td>#<?= $usuario['id'] ?></td>
                                            </tr>
                                            <tr>
                                                <td><strong>Usuario:</strong></td>
                                                <td><?= htmlspecialchars($usuario['nombre_usuario']) ?></td>
                                            </tr>
                                            <tr>
                                                <td><strong>Estado:</strong></td>
                                                <td>
                                                    <span class="badge bg-<?= $usuario['estado'] === 'activo' ? 'success' : 'warning' ?>">
                                                        <?= ucfirst($usuario['estado']) ?>
                                                    </span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><strong>Última conexión:</strong></td>
                                                <td><?= date('d/m/Y H:i:s') ?></td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Configuración Section -->
                    <div id="configuracion-section" style="display: none;">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">
                                    <i class="fas fa-cog me-2"></i>Configuración del Sistema
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <h6><i class="fas fa-palette me-2"></i>Apariencia</h6>
                                        <div class="form-check form-switch mb-3">
                                            <input class="form-check-input" type="checkbox" id="darkMode">
                                            <label class="form-check-label" for="darkMode">
                                                Modo oscuro
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <h6><i class="fas fa-bell me-2"></i>Notificaciones</h6>
                                        <div class="form-check form-switch mb-3">
                                            <input class="form-check-input" type="checkbox" id="notifications" checked>
                                            <label class="form-check-label" for="notifications">
                                                Recibir notificaciones
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-12">
                                        <h6><i class="fas fa-shield-alt me-2"></i>Seguridad</h6>
                                        <p class="text-muted">Tu cuenta está protegida con las mejores prácticas de seguridad.</p>
                                        <div class="alert alert-success" role="alert">
                                            <i class="fas fa-check-circle me-2"></i>
                                            Contraseña cifrada con hash seguro
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Perfil -->
    <div class="modal fade" id="perfilModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-user me-2"></i>Mi Perfil
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center">
                    <div class="user-avatar mx-auto mb-3" style="background: #667eea;">
                        <i class="fas fa-user text-white"></i>
                    </div>
                    <h5><?= htmlspecialchars($usuario['nombre_usuario']) ?></h5>
                    <span class="badge bg-<?= $usuario['estado'] === 'activo' ? 'success' : 'warning' ?> mb-3">
                        <?= ucfirst($usuario['estado']) ?>
                    </span>
                    <p class="text-muted">ID de usuario: #<?= $usuario['id'] ?></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Confirmación de Logout -->
    <div class="modal fade" id="logoutModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-sign-out-alt me-2"></i>Cerrar Sesión
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>¿Estás seguro de que deseas cerrar sesión?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <a href="../../controller/UsuarioController.php?accion=logout" class="btn btn-danger">
                        <i class="fas fa-sign-out-alt me-2"></i>Cerrar Sesión
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Función para mostrar diferentes secciones
        function mostrarSeccion(seccion) {
            // Ocultar todas las secciones
            document.getElementById('dashboard-section').style.display = 'none';
            document.getElementById('perfil-section').style.display = 'none';
            document.getElementById('configuracion-section').style.display = 'none';
            
            // Mostrar la sección seleccionada
            document.getElementById(seccion + '-section').style.display = 'block';
            
            // Actualizar navegación activa
            document.querySelectorAll('.nav-link').forEach(link => {
                link.classList.remove('active');
            });
            event.target.classList.add('active');
        }
        
        // Función para confirmar logout
        function confirmarLogout() {
            const modal = new bootstrap.Modal(document.getElementById('logoutModal'));
            modal.show();
        }
        
        // Contador de tiempo en línea
        let tiempoInicio = new Date();
        
        function actualizarTiempoEnLinea() {
            const ahora = new Date();
            const diferencia = ahora - tiempoInicio;
            
            const horas = Math.floor(diferencia / (1000 * 60 * 60));
            const minutos = Math.floor((diferencia % (1000 * 60 * 60)) / (1000 * 60));
            const segundos = Math.floor((diferencia % (1000 * 60)) / 1000);
            
            const tiempoFormateado = 
                String(horas).padStart(2, '0') + ':' +
                String(minutos).padStart(2, '0') + ':' +
                String(segundos).padStart(2, '0');
            
            document.getElementById('tiempoEnLinea').textContent = tiempoFormateado;
        }
        
        // Actualizar tiempo cada segundo
        setInterval(actualizarTiempoEnLinea, 1000);
        
        // Modo oscuro
        document.getElementById('darkMode').addEventListener('change', function() {
            if (this.checked) {
                document.body.classList.add('bg-dark');
                document.body.style.color = 'white';
            } else {
                document.body.classList.remove('bg-dark');
                document.body.style.color = '';
            }
        });
        
        // Mostrar mensaje de bienvenida
        window.addEventListener('load', function() {
            setTimeout(function() {
                const toast = document.createElement('div');
                toast.className = 'toast position-fixed top-0 end-0 m-3';
                toast.innerHTML = `
                    <div class="toast-header">
                        <i class="fas fa-check-circle text-success me-2"></i>
                        <strong class="me-auto">Sistema</strong>
                        <button type="button" class="btn-close" data-bs-dismiss="toast"></button>
                    </div>
                    <div class="toast-body">
                        ¡Bienvenido <?= htmlspecialchars($usuario['nombre_usuario']) ?>! Sesión iniciada correctamente.
                    </div>
                `;
                document.body.appendChild(toast);
                
                const bsToast = new bootstrap.Toast(toast);
                bsToast.show();
                
                // Remover el toast después de que se oculte
                toast.addEventListener('hidden.bs.toast', function() {
                    document.body.removeChild(toast);
                });
            }, 1000);
        });
    </script>
</body>
</html>