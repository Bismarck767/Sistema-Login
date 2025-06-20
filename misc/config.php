<?php
// Configuración global del sistema
define('DB_HOST', 'localhost');
define('DB_NAME', 'sistema_login');
define('DB_USER', 'root');
define('DB_PASS', '');

// Configuraciones de sesión
ini_set('session.cookie_httponly', 1);
ini_set('session.use_only_cookies', 1);
ini_set('session.cookie_secure', 0); // Cambiar a 1 si usas HTTPS

// Configuraciones de seguridad
define('HASH_COST', 12);

// Configuraciones de la aplicación
define('APP_NAME', 'Sistema de Usuarios');
define('APP_VERSION', '1.0.0');

// Zona horaria
date_default_timezone_set('America/Costa_Rica');

// Función para debug (solo en desarrollo)
function debug($data) {
    if (defined('DEBUG') && DEBUG) {
        echo '<pre>';
        var_dump($data);
        echo '</pre>';
    }
}

// Configurar nivel de errores para desarrollo
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>
