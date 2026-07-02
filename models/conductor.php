<?php
class Conductor {
    private mysqli $db;

    public int $id_conductor;
    public string $nombre_conductor;
    public string $cedula;
    public string $telefono;
    public string $tipo_sangre;
    public int $estado; 
    public int $id_licencia;

    public function __construct(mysqli $conexion) {
        $this->db = $conexion;
    }

    public function obtenerTodos() {
        $sql = "SELECT c.*, l.numero_licencia FROM Conductores c 
                INNER JOIN Licencia l ON c.id_licencia = l.id_licencia";
        $resultado = $this->db->query($sql);
        return $resultado->fetch_all(MYSQLI_ASSOC);
    }

    public function crear(string $nombre, string $cedula, string $telefono, string $tipo_sangre, int $id_licencia, int $estado = 1) {
        $sql = "INSERT INTO Conductores (nombre_conductor, cedula, telefono, tipo_sangre, estado, id_licencia) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("ssssii", $nombre, $cedula, $telefono, $tipo_sangre, $estado, $id_licencia);
        return $stmt->execute();
    }
}
?>