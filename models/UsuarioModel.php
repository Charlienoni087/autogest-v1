<?php

class UsuarioModel {
    private mysqli $conexion;

    public function __construct(mysqli $conexion) {
        $this->conexion = $conexion;
    }

    public function autenticar(string $usuario, string $password): ?array {
        $query = "SELECT id, usuario, password FROM usuarios WHERE usuario = ?";
        $stmt = $this->conexion->prepare($query);
        $stmt->bind_param("s", $usuario);
        $stmt->execute();
        
        $resultado = $stmt->get_result();

        if ($fila = $resultado->fetch_assoc()) {
            if ($password === $fila['password']) {
                return $fila;
            }
        }
        
        return null;
    }
}
?>