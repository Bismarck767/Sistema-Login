<?php
class Usuario {
    private $id;
    private $nombre_usuario;
    private $clave;
    private $estado;
    private $fecha_creacion;

    public function __construct($id = null, $nombre_usuario = '', $clave = '', $estado = 'activo', $fecha_creacion = null) {
        $this->id = $id;
        $this->nombre_usuario = $nombre_usuario;
        $this->clave = $clave;
        $this->estado = $estado;
        $this->fecha_creacion = $fecha_creacion;
    }

    // Getters
    public function getId() {
        return $this->id;
    }

    public function getNombreUsuario() {
        return $this->nombre_usuario;
    }

    public function getClave() {
        return $this->clave;
    }

    public function getEstado() {
        return $this->estado;
    }

    public function getFechaCreacion() {
        return $this->fecha_creacion;
    }

    // Setters
    public function setId($id) {
        $this->id = $id;
    }

    public function setNombreUsuario($nombre_usuario) {
        $this->nombre_usuario = $nombre_usuario;
    }

    public function setClave($clave) {
        $this->clave = $clave;
    }

    public function setEstado($estado) {
        $this->estado = $estado;
    }

    public function setFechaCreacion($fecha_creacion) {
        $this->fecha_creacion = $fecha_creacion;
    }

    // Método para validar datos
    public function validar() {
        $errores = [];

        if (empty($this->nombre_usuario)) {
            $errores[] = "El nombre de usuario es obligatorio";
        } elseif (strlen($this->nombre_usuario) < 3) {
            $errores[] = "El nombre de usuario debe tener al menos 3 caracteres";
        } elseif (strlen($this->nombre_usuario) > 50) {
            $errores[] = "El nombre de usuario no puede exceder 50 caracteres";
        }

        if (empty($this->clave)) {
            $errores[] = "La contraseña es obligatoria";
        } elseif (strlen($this->clave) < 6) {
            $errores[] = "La contraseña debe tener al menos 6 caracteres";
        }

        if (!in_array($this->estado, ['activo', 'inactivo'])) {
            $errores[] = "El estado debe ser 'activo' o 'inactivo'";
        }

        return $errores;
    }

    // Método para obtener información básica del usuario (sin contraseña)
    public function toArray($incluirClave = false) {
        $datos = [
            'id' => $this->id,
            'nombre_usuario' => $this->nombre_usuario,
            'estado' => $this->estado,
            'fecha_creacion' => $this->fecha_creacion
        ];

        if ($incluirClave) {
            $datos['clave'] = $this->clave;
        }

        return $datos;
    }
}
?>