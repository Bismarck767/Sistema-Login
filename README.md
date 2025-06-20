# Sistema de Login PHP

Un sistema completo de autenticación de usuarios desarrollado en PHP con MySQL, utilizando el patrón MVC y las mejores prácticas de seguridad.

## 🚀 Características

### Seguridad
- ✅ Contraseñas hasheadas con `password_hash()` y `password_verify()`
- ✅ Validación y sanitización de datos de entrada
- ✅ Protección contra inyección SQL con consultas preparadas
- ✅ Regeneración de ID de sesión por seguridad
- ✅ Validación de autenticación en páginas protegidas
- ✅ Control de estados de usuario (activo/inactivo)

### Funcionalidades
- 🔐 Sistema completo de login y registro
- 👤 Panel de usuario con dashboard interactivo
- 📊 Estadísticas en tiempo real (tiempo en línea, estado del usuario)
- 🎨 Interfaz moderna con Bootstrap 5 y Font Awesome
- 📱 Diseño completamente responsivo
- ⚙️ Panel de configuración de usuario
- 🔄 Gestión de sesiones segura

### Arquitectura
- 📁 Patrón MVC (Modelo-Vista-Controlador)
- 🗄️ Patrón DAO (Data Access Object)
- 🔌 Patrón Singleton para conexión a base de datos
- 🛡️ Manejo robusto de errores y excepciones

## 📋 Requisitos del Sistema

- **PHP**: 7.4 o superior
- **MySQL**: 5.7 o superior
- **Servidor Web**: Apache o Nginx
- **Extensiones PHP requeridas**:
  - PDO
  - PDO_MySQL
  - Session

## 🛠️ Instalación

### 1. Clonar el repositorio
```bash
git clone https://github.com/tu-usuario/sistema-login.git
cd sistema-login
```

### 2. Configurar la base de datos

Crea una base de datos MySQL y ejecuta el siguiente script:

```sql
CREATE DATABASE sistema_login;
USE sistema_login;

CREATE TABLE usuarios_carnet (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre_usuario VARCHAR(50) NOT NULL UNIQUE,
    clave VARCHAR(255) NOT NULL,
    estado ENUM('activo', 'inactivo') DEFAULT 'activo',
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Usuario de prueba (contraseña: 123456)
INSERT INTO usuarios_carnet (nombre_usuario, clave, estado) 
VALUES ('admin', '$2y$10$example_hash_here', 'activo');
```

### 3. Configurar la conexión

Edita el archivo `misc/Conexion.php` con tus credenciales:

```php
private static $host = 'localhost';
private static $dbname = 'sistema_login';
private static $username = 'tu_usuario';
private static $password = 'tu_contraseña';
private static $port = 3306;
```

### 4. Configurar el servidor web

**Apache (.htaccess)**
```apache
RewriteEngine On
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^(.*)$ index.php [QSA,L]
```

**Nginx**
```nginx
location / {
    try_files $uri $uri/ /index.php?$query_string;
}
```

### 5. Establecer permisos
```bash
chmod 755 -R .
chmod 644 *.php
```

## 📁 Estructura del Proyecto

```
sistema-login/
├── 📁 accessData/           # Capa de acceso a datos
│   └── UsuarioDAO.php       # DAO para operaciones de usuario
├── 📁 controller/           # Controladores MVC
│   └── UsuarioController.php # Controlador principal de usuarios
├── 📁 misc/                 # Archivos de configuración
│   ├── Conexion.php         # Clase de conexión a BD (Singleton)
│   └── config.php           # Configuraciones globales
├── 📁 model/                # Modelos de datos
│   └── Usuario.php          # Modelo de usuario
├── 📁 view/                 # Vistas de la aplicación
│   ├── 📁 usuarios/         # Vistas específicas de usuarios
│   │   ├── inicio.php       # Dashboard principal
│   │   ├── login.php        # Formulario de login
│   │   └── registro.php     # Formulario de registro
│   ├── inicio.php           # Vista de inicio alternativa
│   └── registro.php         # Vista de registro alternativa
├── index.php                # Punto de entrada principal
├── package.json             # Dependencias de Node.js (opcional)
└── README.md               # Este archivo
```

## 🎯 Uso del Sistema

### Acceso Inicial
1. Navega a `http://tu-dominio/sistema-login/`
2. Serás redirigido automáticamente al login
3. Utiliza las credenciales de prueba:
   - **Usuario**: `admin`
   - **Contraseña**: `123456`

### Registro de Nuevos Usuarios
1. Haz clic en "Registrarse aquí" desde el login
2. Completa el formulario con:
   - Nombre de usuario (mínimo 3 caracteres)
   - Contraseña (mínimo 6 caracteres)
   - Confirmación de contraseña
   - Estado (activo/inactivo)
3. El sistema validará automáticamente la información

### Panel de Usuario
Una vez autenticado, tendrás acceso a:
- **Dashboard**: Estadísticas y información del usuario
- **Mi Perfil**: Detalles de la cuenta
- **Configuración**: Opciones de personalización
- **Tiempo en línea**: Contador automático de sesión

## 🔧 Configuración Avanzada

### Variables de Entorno
```php
// misc/config.php
define('DB_HOST', 'localhost');
define('DB_NAME', 'sistema_login');
define('DB_USER', 'root');
define('DB_PASS', '');
define('HASH_COST', 12);
```

### Seguridad de Sesiones
```php
// Configuración automática en config.php
ini_set('session.cookie_httponly', 1);
ini_set('session.use_only_cookies', 1);
ini_set('session.cookie_secure', 0); // Cambiar a 1 para HTTPS
```

## 🧪 Pruebas y Validación

### Casos de Prueba
- ✅ Login con credenciales válidas
- ✅ Login con credenciales inválidas
- ✅ Registro con datos válidos
- ✅ Registro con usuario duplicado
- ✅ Validación de campos requeridos
- ✅ Protección de páginas sin autenticación
- ✅ Logout y limpieza de sesión

### Usuario de Prueba
```
Usuario: admin
Contraseña: 123456
Estado: activo
```

## 🔐 Características de Seguridad

### Autenticación
- Hash seguro de contraseñas con `password_hash()`
- Verificación con `password_verify()`
- Regeneración de ID de sesión
- Control de estado de usuario

### Validación
- Sanitización de datos de entrada
- Validación del lado del servidor y cliente
- Protección contra inyección SQL
- Validación de longitud y formato

### Sesiones
- Configuración segura de cookies
- Limpieza completa al cerrar sesión
- Verificación de autenticación en cada página

## 🎨 Personalización

### Temas y Estilos
- Bootstrap 5 para responsive design
- Font Awesome para iconografía
- CSS personalizado para gradientes y efectos
- Modo oscuro disponible en configuración

### Componentes Interactivos
- Indicador de fortaleza de contraseña
- Contador de tiempo en línea
- Notificaciones toast
- Modales de confirmación

## 🚨 Troubleshooting

### Problemas Comunes

**Error de conexión a la base de datos**
```php
// Verificar credenciales en misc/Conexion.php
// Asegurar que la base de datos existe
// Verificar que el usuario MySQL tiene permisos
```

**Sesiones no funcionan**
```php
// Verificar que session_start() se llama al inicio
// Comprobar permisos de escritura en /tmp
// Verificar configuración de PHP para sesiones
```

**Página en blanco**
```php
// Activar display_errors en desarrollo
error_reporting(E_ALL);
ini_set('display_errors', 1);
```

## 📈 Próximas Mejoras

- [ ] Sistema de recuperación de contraseña
- [ ] Autenticación de dos factores (2FA)
- [ ] Panel de administración
- [ ] Historial de sesiones
- [ ] API RESTful
- [ ] Integración con OAuth (Google, Facebook)
- [ ] Sistema de roles y permisos
- [ ] Logs de auditoría

## 🤝 Contribución

1. Fork el proyecto
2. Crea una rama para tu feature (`git checkout -b feature/nueva-caracteristica`)
3. Commit tus cambios (`git commit -am 'Añadir nueva característica'`)
4. Push a la rama (`git push origin feature/nueva-caracteristica`)
5. Abre un Pull Request

## 📄 Licencia

Este proyecto está bajo la Licencia MIT. Ver el archivo `LICENSE` para más detalles.

## 👨‍💻 Autor

**Tu Nombre**
- GitHub: [@tu-usuario](https://github.com/tu-usuario)
- Email: tu-email@ejemplo.com

## 🙏 Reconocimientos

- Bootstrap 5 por el framework CSS
- Font Awesome por los iconos
- PHP Community por la documentación
- MySQL por la base de datos

---

⭐ **¡Si te gusta este proyecto, dale una estrella!** ⭐
