<?php

class Vehiculo {
    private mysqli $db;
    public int $id_vehiculo;
    public string $marca;
    public string $modelo;
    public string $color;
    public string $chasis;
    public string $tipo_vehiculo;
    public string $tipo_combustible;
    public string $estado;
    public string $numero_poliza;
    public string $gravamen;

    public function __construct(mysqli $conexion) {
        $this->db = $conexion;
    }

    public function obtenerVehiculos() {
        $sql = "SELECT * FROM Vehiculos";
        $resultado = $this->db->query($sql);
        return $resultado->fetch_all(MYSQLI_ASSOC);
    }

    public function crearVehiculo(string $marca, string $modelo, string $color, string $chasis, string $tipo_vehiculo, string $tipo_combustible, string $estado, string $numero_poliza, string $gravamen) {
    $sql = "INSERT INTO Vehiculos (marca, modelo, color, chasis, tipo_vehiculo, tipo_combustible, estado, numero_poliza, gravamen) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $this->db->prepare($sql);
    $stmt->bind_param("ssssssss", $marca, $modelo, $color, $chasis, $tipo_vehiculo, $tipo_combustible, $estado, $numero_poliza, $gravamen);
    return $stmt->execute();
    }

    public function actualizarVehiculo(int $id_vehiculo, string $marca, string $modelo, string $color, string $chasis, string $tipo_vehiculo, string $tipo_combustible, string $estado, string $numero_poliza, string $gravamen) {
    $sql = "UPDATE Vehiculos
            SET marca = ?, modelo = ?, color = ?, chasis = ?, tipo_vehiculo = ?, tipo_combustible = ?, estado = ?, numero_poliza = ?, gravamen = ?
            WHERE id_vehiculo = ?";
    $stmt = $this->db->prepare($sql);
    $stmt->bind_param("sssssssssi", $marca, $modelo, $color, $chasis, $tipo_vehiculo, $tipo_combustible, $estado, $numero_poliza, $gravamen, $id_vehiculo);
    return $stmt->execute();
    }

    public function eliminarVehiculo(int $id_vehiculo) {
    $sql = "DELETE FROM Vehiculos WHERE id_vehiculo = ?";
    $stmt = $this->db->prepare($sql);
    $stmt->bind_param("i", $$id_vehiculo);
    return $stmt->execute();
    }
}
?>

