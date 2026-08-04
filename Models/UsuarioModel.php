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
        // 1. Comprobar si el correo ya existe en la base de datos
        $checkSql = "SELECT id_usuario FROM usuarios WHERE correo = ?";
        $checkStmt = $this->db->prepare($checkSql);
        
        if (!$checkStmt) {
            die("Error prepare en validación: " . $this->db->error);
        }
        
        $checkStmt->bind_param("s", $correo);
        $checkStmt->execute();
        $checkStmt->store_result(); // Necesario para contar las filas
        
        // Si num_rows es mayor a 0, el correo ya está registrado
        if ($checkStmt->num_rows > 0) {
            $checkStmt->close();
            // Puedes retornar false o lanzar una excepción, dependiendo de cómo manejes los errores
            // throw new Exception("El correo ingresado ya está en uso.");
            return false; 
        }
        $checkStmt->close();

        // 2. Si el correo no existe, procedemos con la inserción
        $sql = "INSERT INTO usuarios (nombre_usuario, correo, contrasena, rol) VALUES (?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        
        if (!$stmt) {
            die("Error prepare en inserción: " . $this->db->error);
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