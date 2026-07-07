<?php
class Licencia {
    private mysqli $db;

    public int $id_licencia;
    public string $numero_licencia;
    public string $tipo_licencia;
    public string $categorias;
    public string $fecha_vencimiento;

    public function __construct(mysqli $conexion) {
        $this->db = $conexion;
    }

    public function obtenerTodas() {
        $sql = "SELECT * FROM Licencia";
        $resultado = $this->db->query($sql);
        return $resultado->fetch_all(MYSQLI_ASSOC);
    }

    public function crear(string $numero_licencia, string $tipo_licencia, string $categorias, string $fecha_vencimiento) {
        $sql = "INSERT INTO Licencia (numero_licencia, tipo_licencia, categorias, fecha_vencimiento) VALUES (?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("ssss", $numero_licencia, $tipo_licencia, $categorias, $fecha_vencimiento);
        return $stmt->execute();
    }

    public function actualizar(int $id_licencia, string $numero_licencia, string $tipo_licencia, string $categorias, string $fecha_vencimiento) {
        $sql = "UPDATE Licencia 
                SET numero_licencia = ?, tipo_licencia = ?, categorias = ?, fecha_vencimiento = ? 
                WHERE id_licencia = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("ssssi", $numero_licencia, $tipo_licencia, $categorias, $fecha_vencimiento, $id_licencia);
        return $stmt->execute();
    }
}
?>