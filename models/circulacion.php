<?php
class Circulacion {
    private mysqli $db;

    public int $id_circulacion;
    public string $codigo_circulacion;
    public ?int $cilindraje;   
    public ?float $tonelaje;   
    public int $pasajeros;
    public string $placa;

    public function __construct(mysqli $conexion) {
        $this->db = $conexion;
    }

    public function obtenerPorPlaca(string $placa) {
        $sql = "SELECT * FROM Circulación WHERE placa = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("s", $placa);
        $stmt->execute();
        $resultado = $stmt->get_result();
        return $resultado->fetch_assoc();
    }

    public function crear(string $codigo, ?int $cilindraje, ?float $tonelaje, int $pasajeros, string $placa) {
        $sql = "INSERT INTO Circulación (codigo_circulacion, cilindraje, tonelaje, pasajeros, placa) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("sidis", $codigo, $cilindraje, $tonelaje, $pasajeros, $placa);
        return $stmt->execute();
    }
}
?>