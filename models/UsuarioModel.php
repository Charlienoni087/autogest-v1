<?php

class UsuarioModel {
    private mysqli $db;

    public int $id_usuario;
    public string $nombre_usuario;
    public string $correo;
    public string $contrasena;
    public string $rol;

    public function __construct(mysqli $conexion) {
        $this->db = $conexion;
    }

    public function autenticar(string $usuario, string $password): ?array {
        // Corregidos los nombres de columnas para que coincidan con tu BD
        $query = "SELECT id_usuario, nombre_usuario, contrasena, rol FROM usuarios WHERE nombre_usuario = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("s", $usuario);
        $stmt->execute();
        
        $resultado = $stmt->get_result();

        if ($fila = $resultado->fetch_assoc()) {
            // Soporta hash de contraseña o texto plano
            if (password_verify($password, $fila['contrasena']) || $password === $fila['contrasena']) {
                return $fila;
            }
        }
        
        return null;
    }

    // Método que requiere UsuarioController.php
    public function obtenerUsuarios(): array {
        $sql = "SELECT id_usuario, nombre_usuario, correo, contrasena, rol FROM usuarios";
        $resultado = $this->db->query($sql);
        
        if (!$resultado) {
            die("Error query: " . $this->db->error);
        }
        
        return $resultado->fetch_all(MYSQLI_ASSOC);
    }

    // Alias por si usas obtenerTodos() en otra parte
    public function obtenerTodos(): array {
        return $this->obtenerUsuarios();
    }

    // Obtener un usuario específico
    public function obtenerPorId(int $id): ?array {
        $sql = "SELECT * FROM usuarios WHERE id_usuario = ?";
        $stmt = $this->db->prepare($sql);
        
        if (!$stmt) {
            die("Error prepare: " . $this->db->error);
        }
        
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $resultado = $stmt->get_result();
        
        return $resultado->fetch_assoc() ?: null;
    }

    // Agregar Usuario
    public function crear(string $nombre, string $correo, string $contrasena, string $rol): bool {
        $sql = "INSERT INTO usuarios (nombre_usuario, correo, contrasena, rol) VALUES (?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        
        if (!$stmt) {
            die("Error prepare: " . $this->db->error);
        }

        $passHash = password_hash($contrasena, PASSWORD_DEFAULT); 
        $stmt->bind_param("ssss", $nombre, $correo, $passHash, $rol);
        
        return $stmt->execute();
    }

    // Editar Usuario
    public function actualizar(int $id, string $nombre, string $correo, string $contrasena, string $rol): bool {
        // Si la contraseña viene vacía, actualizamos todo MENOS la contraseña
        if (empty(trim($contrasena))) {
            $sql = "UPDATE usuarios SET nombre_usuario = ?, correo = ?, rol = ? WHERE id_usuario = ?";
            $stmt = $this->db->prepare($sql);
            
            if (!$stmt) {
                die("Error prepare: " . $this->db->error);
            }
            
            $stmt->bind_param("sssi", $nombre, $correo, $rol, $id);
        } else {
            // Si trae contraseña, actualizamos todo
            $sql = "UPDATE usuarios SET nombre_usuario = ?, correo = ?, contrasena = ?, rol = ? WHERE id_usuario = ?";
            $stmt = $this->db->prepare($sql);
            
            if (!$stmt) {
                die("Error prepare: " . $this->db->error);
            }
            
            $passHash = password_hash($contrasena, PASSWORD_DEFAULT);
            $stmt->bind_param("ssssi", $nombre, $correo, $passHash, $rol, $id);
        }
        
        return $stmt->execute();
    }

    // Eliminar Usuario
    public function eliminar(int $id): bool {
        $sql = "DELETE FROM usuarios WHERE id_usuario = ?";
        $stmt = $this->db->prepare($sql);
        
        if (!$stmt) {
            die("Error al eliminar: " . $this->db->error);
        }
        
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}
?>