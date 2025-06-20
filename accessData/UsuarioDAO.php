<?php
require_once __DIR__ . '/../misc/Conexion.php';
require_once __DIR__ . '/../model/Usuario.php';

class UsuarioDAO {
    
    public function loginUsuario($nombre_usuario, $clave) {
        try {
            $conexion = Conexion::conectar();
            
            // Buscar usuario por nombre de usuario
            $stmt = $conexion->prepare("SELECT id, nombre_usuario, clave, estado, fecha_creacion FROM usuarios_carnet WHERE nombre_usuario = ? AND estado = 'activo'");
            $stmt->execute([$nombre_usuario]);
            
            $resultado = $stmt->fetch();
            
            if ($resultado) {
                // Verificar la contraseña hasheada
                if (password_verify($clave, $resultado['clave'])) {
                    return new Usuario(
                        $resultado['id'],
                        $resultado['nombre_usuario'],
                        $resultado['clave'],
                        $resultado['estado'],
                        $resultado['fecha_creacion']
                    );
                }
            }
            
            return null;
            
        } catch (PDOException $e) {
            error_log("Error en login: " . $e->getMessage());
            return null;
        } catch (Exception $e) {
            error_log("Error general en login: " . $e->getMessage());
            return null;
        }
    }
    
    public function crearUsuario($usuario) {
        try {
            // Validar datos del usuario antes de crear
            $errores = $usuario->validar();
            if (!empty($errores)) {
                error_log("Errores de validación: " . implode(', ', $errores));
                return false;
            }

            $conexion = Conexion::conectar();
            
            // Verificar si el usuario ya existe
            if ($this->existeUsuario($usuario->getNombreUsuario())) {
                error_log("Usuario ya existe: " . $usuario->getNombreUsuario());
                return false;
            }
            
            // Hashear la contraseña
            $claveHasheada = password_hash($usuario->getClave(), PASSWORD_DEFAULT);
            
            // Insertar usuario
            $stmt = $conexion->prepare("INSERT INTO usuarios_carnet (nombre_usuario, clave, estado) VALUES (?, ?, ?)");
            $resultado = $stmt->execute([
                $usuario->getNombreUsuario(),
                $claveHasheada,
                $usuario->getEstado()
            ]);
            
            return $resultado;
            
        } catch (PDOException $e) {
            error_log("Error PDO al crear usuario: " . $e->getMessage());
            return false;
        } catch (Exception $e) {
            error_log("Error general al crear usuario: " . $e->getMessage());
            return false;
        }
    }
    
    public function existeUsuario($nombre_usuario) {
        try {
            $conexion = Conexion::conectar();
            
            $stmt = $conexion->prepare("SELECT COUNT(*) as total FROM usuarios_carnet WHERE nombre_usuario = ?");
            $stmt->execute([$nombre_usuario]);
            
            $resultado = $stmt->fetch();
            return $resultado['total'] > 0;
            
        } catch (PDOException $e) {
            error_log("Error al verificar usuario existente: " . $e->getMessage());
            return true; // Retornar true para prevenir duplicados en caso de error
        } catch (Exception $e) {
            error_log("Error general al verificar usuario: " . $e->getMessage());
            return true;
        }
    }

    public function obtenerUsuario($id) {
        try {
            $conexion = Conexion::conectar();
            
            $stmt = $conexion->prepare("SELECT id, nombre_usuario, clave, estado, fecha_creacion FROM usuarios_carnet WHERE id = ?");
            $stmt->execute([$id]);
            
            $resultado = $stmt->fetch();
            
            if ($resultado) {
                return new Usuario(
                    $resultado['id'],
                    $resultado['nombre_usuario'],
                    $resultado['clave'],
                    $resultado['estado'],
                    $resultado['fecha_creacion']
                );
            }
            
            return null;
            
        } catch (PDOException $e) {
            error_log("Error al obtener usuario: " . $e->getMessage());
            return null;
        } catch (Exception $e) {
            error_log("Error general al obtener usuario: " . $e->getMessage());
            return null;
        }
    }

    public function actualizarUltimaConexion($id) {
        try {
            $conexion = Conexion::conectar();
            
            $stmt = $conexion->prepare("UPDATE usuarios_carnet SET fecha_creacion = CURRENT_TIMESTAMP WHERE id = ?");
            return $stmt->execute([$id]);
            
        } catch (PDOException $e) {
            error_log("Error al actualizar última conexión: " . $e->getMessage());
            return false;
        } catch (Exception $e) {
            error_log("Error general al actualizar última conexión: " . $e->getMessage());
            return false;
        }
    }

    public function cambiarEstadoUsuario($id, $estado) {
        try {
            if (!in_array($estado, ['activo', 'inactivo'])) {
                return false;
            }

            $conexion = Conexion::conectar();
            
            $stmt = $conexion->prepare("UPDATE usuarios_carnet SET estado = ? WHERE id = ?");
            return $stmt->execute([$estado, $id]);
            
        } catch (PDOException $e) {
            error_log("Error al cambiar estado de usuario: " . $e->getMessage());
            return false;
        } catch (Exception $e) {
            error_log("Error general al cambiar estado: " . $e->getMessage());
            return false;
        }
    }

    public function obtenerTodosLosUsuarios() {
        try {
            $conexion = Conexion::conectar();
            
            $stmt = $conexion->prepare("SELECT id, nombre_usuario, estado, fecha_creacion FROM usuarios_carnet ORDER BY fecha_creacion DESC");
            $stmt->execute();
            
            $usuarios = [];
            while ($fila = $stmt->fetch()) {
                $usuarios[] = new Usuario(
                    $fila['id'],
                    $fila['nombre_usuario'],
                    '', // No incluir contraseña
                    $fila['estado'],
                    $fila['fecha_creacion']
                );
            }
            
            return $usuarios;
            
        } catch (PDOException $e) {
            error_log("Error al obtener todos los usuarios: " . $e->getMessage());
            return [];
        } catch (Exception $e) {
            error_log("Error general al obtener usuarios: " . $e->getMessage());
            return [];
        }
    }
}
?>