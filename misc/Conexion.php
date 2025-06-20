<?php
class Conexion {
    private static $host = 'maglev.proxy.rlwy.net';
    private static $dbname = 'railway';
    private static $username = 'root';
    private static $password = 'kTjTfEXQyFhwbUaVtZduFNCwsPgnJITK';
    private static $port = 40111;
    private static $charset = 'utf8mb4';
    private static $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ];
    private static $pdo = null;

    // Constructor privado para evitar instanciación
    private function __construct() {}

    // Método para obtener la conexión (Singleton)
    public static function conectar() {
        if (self::$pdo === null) {
            try {
                $dsn = "mysql:host=" . self::$host . ";port=" . self::$port . ";dbname=" . self::$dbname . ";charset=" . self::$charset;
                self::$pdo = new PDO($dsn, self::$username, self::$password, self::$options);
            } catch (PDOException $e) {
                error_log("Error de conexión a la base de datos: " . $e->getMessage());
                throw new Exception("Error de conexión a la base de datos. Por favor, contacte al administrador.");
            }
        }
        return self::$pdo;
    }

    // Método para cerrar la conexión
    public static function desconectar() {
        self::$pdo = null;
    }

    // Método para cambiar configuración de base de datos (útil para testing)
    public static function configurar($host, $dbname, $username, $password, $port = 3306) {
        self::$host = $host;
        self::$dbname = $dbname;
        self::$username = $username;
        self::$password = $password;
        self::$port = $port;
        self::$pdo = null; // Forzar nueva conexión
    }

    // Método para verificar si la conexión está activa
    public static function probarConexion() {
        try {
            $pdo = self::conectar();
            $stmt = $pdo->query('SELECT 1');
            return true;
        } catch (Exception $e) {
            error_log("Error al probar conexión: " . $e->getMessage());
            return false;
        }
    }
}
?>