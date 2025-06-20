<?php
session_start();

require_once __DIR__ . '/../accessData/UsuarioDAO.php';
require_once __DIR__ . '/../model/Usuario.php';

class UsuarioController {
    private $usuarioDAO;

    public function __construct() {
        $this->usuarioDAO = new UsuarioDAO();
    }

    public function login() {
        // Verificar que sea método POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirigir('../view/usuarios/login.php', 'error', 'Método no permitido');
            return;
        }

        // Sanitizar y validar datos de entrada
        $nombre_usuario = $this->sanitizarInput($_POST['nombre_usuario'] ?? '');
        $clave = $_POST['clave'] ?? '';

        // Validaciones básicas
        if (empty($nombre_usuario) || empty($clave)) {
            $this->redirigir('../view/usuarios/login.php', 'error', 'Usuario y contraseña son obligatorios.');
            return;
        }

        // Validar longitud mínima
        if (strlen($nombre_usuario) < 3) {
            $this->redirigir('../view/usuarios/login.php', 'error', 'El nombre de usuario debe tener al menos 3 caracteres.');
            return;
        }

        if (strlen($clave) < 6) {
            $this->redirigir('../view/usuarios/login.php', 'error', 'La contraseña debe tener al menos 6 caracteres.');
            return;
        }

        // Intentar login
        $usuario = $this->usuarioDAO->loginUsuario($nombre_usuario, $clave);

        if ($usuario !== null) {
            // Login exitoso
            $this->iniciarSesion($usuario);
            $this->redirigir('../view/usuarios/inicio.php');
        } else {
            // Login fallido
            $this->redirigir('../view/usuarios/login.php', 'error', 'Usuario o contraseña incorrectos.');
        }
    }

    public function registro() {
        // Verificar que sea método POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirigir('../view/usuarios/registro.php', 'error', 'Método no permitido');
            return;
        }

        // Sanitizar datos de entrada
        $nombre_usuario = $this->sanitizarInput($_POST['nombre_usuario'] ?? '');
        $clave = $_POST['clave'] ?? '';
        $confirmar_clave = $_POST['confirmar_clave'] ?? '';
        $estado = $this->sanitizarInput($_POST['estado'] ?? 'activo');

        // Guardar datos del formulario para mantenerlos en caso de error
        $_SESSION['datos_form'] = [
            'nombre_usuario' => $nombre_usuario,
            'estado' => $estado
        ];

        $errores = [];

        // Validaciones de nombre de usuario
        if (empty($nombre_usuario)) {
            $errores[] = "El nombre de usuario es obligatorio";
        } elseif (strlen($nombre_usuario) < 3) {
            $errores[] = "El nombre de usuario debe tener al menos 3 caracteres";
        } elseif (strlen($nombre_usuario) > 50) {
            $errores[] = "El nombre de usuario no puede exceder 50 caracteres";
        } elseif (!preg_match('/^[a-zA-Z0-9_]+$/', $nombre_usuario)) {
            $errores[] = "El nombre de usuario solo puede contener letras, números y guiones bajos";
        }

        // Validaciones de contraseña
        if (empty($clave)) {
            $errores[] = "La contraseña es obligatoria";
        } elseif (strlen($clave) < 6) {
            $errores[] = "La contraseña debe tener al menos 6 caracteres";
        } elseif (strlen($clave) > 255) {
            $errores[] = "La contraseña es demasiado larga";
        }

        // Validar confirmación de contraseña
        if ($clave !== $confirmar_clave) {
            $errores[] = "Las contraseñas no coinciden";
        }

        // Validar estado
        if (!in_array($estado, ['activo', 'inactivo'])) {
            $errores[] = "Estado inválido";
        }

        // Si hay errores, redirigir con errores
        if (!empty($errores)) {
            $_SESSION['errores'] = $errores;
            $this->redirigir('../view/usuarios/registro.php');
            return;
        }

        // Crear objeto usuario
        $usuario = new Usuario(null, $nombre_usuario, $clave, $estado);

        // Validar con el modelo
        $erroresModelo = $usuario->validar();
        if (!empty($erroresModelo)) {
            $_SESSION['errores'] = $erroresModelo;
            $this->redirigir('../view/usuarios/registro.php');
            return;
        }

        // Intentar crear usuario
        $exito = $this->usuarioDAO->crearUsuario($usuario);

        if ($exito) {
            // Éxito: limpiar datos del formulario y redirigir al login
            unset($_SESSION['datos_form']);
            $this->redirigir('../view/usuarios/login.php', 'exito', 'Usuario creado exitosamente. Puedes iniciar sesión.');
        } else {
            // Error: puede ser usuario duplicado u otro error
            if ($this->usuarioDAO->existeUsuario($nombre_usuario)) {
                $this->redirigir('../view/usuarios/registro.php', 'error', 'El nombre de usuario ya existe.');
            } else {
                $this->redirigir('../view/usuarios/registro.php', 'error', 'Error al crear el usuario. Intenta nuevamente.');
            }
        }
    }

    public function logout() {
        // Destruir toda la información de la sesión
        session_unset();
        session_destroy();
        
        // Iniciar nueva sesión para mostrar mensaje
        session_start();
        
        // Regenerar ID de sesión por seguridad
        session_regenerate_id(true);
        
        $this->redirigir('../view/usuarios/login.php', 'exito', 'Has cerrado sesión correctamente.');
    }

    // Método para verificar si el usuario está autenticado
    public static function verificarAutenticacion() {
        if (!isset($_SESSION['autenticado']) || $_SESSION['autenticado'] !== true) {
            header('Location: ../login.php');
            exit;
        }
    }

    // Método para iniciar sesión
    private function iniciarSesion($usuario) {
        // Regenerar ID de sesión por seguridad
        session_regenerate_id(true);
        
        $_SESSION['autenticado'] = true;
        $_SESSION['usuario'] = [
            'id' => $usuario->getId(),
            'nombre_usuario' => $usuario->getNombreUsuario(),
            'estado' => $usuario->getEstado(),
            'fecha_login' => date('Y-m-d H:i:s')
        ];
        
        // Actualizar última conexión en la base de datos
        $this->usuarioDAO->actualizarUltimaConexion($usuario->getId());
    }

    // Método para sanitizar entrada
    private function sanitizarInput($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
        return $data;
    }

    // Método para redirigir con mensajes
    private function redirigir($ubicacion, $tipo = null, $mensaje = null) {
        if ($tipo && $mensaje) {
            $_SESSION[$tipo] = $mensaje;
        }
        header("Location: $ubicacion");
        exit;
    }

    // Método para validar token CSRF (opcional pero recomendado)
    private function validarCSRF($token) {
        return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
    }

    // Método para generar token CSRF
    public static function generarCSRF() {
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }
}

// Ruteo mejorado con validación
if ($_SERVER['REQUEST_METHOD'] === 'POST' || $_SERVER['REQUEST_METHOD'] === 'GET') {
    $accion = $_GET['accion'] ?? '';
    
    // Validar acción
    $accionesPermitidas = ['login', 'registro', 'logout'];
    if (!in_array($accion, $accionesPermitidas)) {
        header("Location: ../view/usuarios/login.php");
        exit;
    }

    $controller = new UsuarioController();

    try {
        switch ($accion) {
            case 'login':
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $controller->login();
                } else {
                    header("Location: ../view/usuarios/login.php");
                    exit;
                }
                break;
                
            case 'registro':
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $controller->registro();
                } else {
                    header("Location: ../view/usuarios/registro.php");
                    exit;
                }
                break;
                
            case 'logout':
                $controller->logout();
                break;
                
            default:
                header("Location: ../view/usuarios/login.php");
                exit;
        }
    } catch (Exception $e) {
        error_log("Error en controlador: " . $e->getMessage());
        $_SESSION['error'] = "Ha ocurrido un error interno. Por favor, intenta nuevamente.";
        header("Location: ../view/usuarios/login.php");
        exit;
    }
} else {
    header("Location: ../view/usuarios/login.php");
    exit;
}
?>