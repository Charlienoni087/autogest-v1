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

    public function obtenerPorId(int $id) {
        $sql = "SELECT * FROM Licencia WHERE id_licencia = ?";
        $stmt = $this->db->prepare($sql);
        if (!$stmt) {
            die("Error prepare: " . $this->db->error);
        }
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $resultado = $stmt->get_result();
        return $resultado->fetch_assoc();
    }

    public function crear(string $numero_licencia, string $tipo_licencia, string $categorias, string $fecha_vencimiento) {
        $sql = "INSERT INTO Licencia (numero_licencia, tipo_licencia, categorias, fecha_vencimiento) VALUES (?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("ssss", $numero_licencia, $tipo_licencia, $categorias, $fecha_vencimiento);
        return $stmt->execute();
    }

    //XD
    public function obtenerLicenciasVencidas() {
        $sql = "SELECT id_licencia, numero_licencia, tipo_licencia, categorias, fecha_vencimiento FROM Licencia
        WHERE fecha_vencimiento < CURDATE()
        ORDER BY fecha_vencimiento ASC";
        $resultado = $this->db->query($sql);
        return $resultado->fetch_all(MYSQLI_ASSOC);
    }

    public function actualizar(int $id_licencia, string $numero_licencia, string $tipo_licencia, string $categorias, string $fecha_vencimiento) {
        $sql = "UPDATE Licencia 
                SET numero_licencia = ?, tipo_licencia = ?, categorias = ?, fecha_vencimiento = ? 
                WHERE id_licencia = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("ssssi", $numero_licencia, $tipo_licencia, $categorias, $fecha_vencimiento, $id_licencia);
        return $stmt->execute();
    }

    public function eliminar(int $id_licencia) {
        // 1. Verificar si la licencia está asignada a un conductor
        $sqlCheck = "SELECT COUNT(*) as total FROM Conductores WHERE id_licencia = ?";
        $stmtCheck = $this->db->prepare($sqlCheck);
        if (!$stmtCheck) {
            die("Error prepare: " . $this->db->error);
        }
        
        $stmtCheck->bind_param("i", $id_licencia);
        $stmtCheck->execute();
        $resultadoCheck = $stmtCheck->get_result()->fetch_assoc();

        if ($resultadoCheck['total'] > 0) {
            // Lanzamos la excepción que será capturada por el controlador
            throw new Exception("Esta licencia está actualmente asignada a un conductor y no puede ser eliminada.");
        }

        // 2. Si no tiene conductores, procedemos a eliminar
        $sql = "DELETE FROM Licencia WHERE id_licencia = ?";
        $stmt = $this->db->prepare($sql);
        if (!$stmt) {
            die("Error al eliminar: " . $this->db->error);
        }
        $stmt->bind_param("i", $id_licencia);
        return $stmt->execute();
    }
}
?>