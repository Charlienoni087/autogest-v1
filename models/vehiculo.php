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
    public int $gravamen;

    public function __construct(mysqli $conexion) {
        $this->db = $conexion;
    }

    public function obtenerVehiculos() {
    $sql = "SELECT v.*, c.nombre_conductor  AS nombre_conductor
            FROM Vehiculos v
            LEFT JOIN Conductores c ON v.id_conductor = c.id_conductor";
    
    $result = $this->db->query($sql);
    return $result->fetch_all(MYSQLI_ASSOC);
}
    
    public function obtenerVehiculoPorId(int $id_vehiculo) {
        $sql = "SELECT * FROM Vehiculos WHERE id_vehiculo = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $id_vehiculo);
        $stmt->execute();
        $resultado = $stmt->get_result();
        return $resultado->fetch_assoc();
    }

   public function crearVehiculo(string $marca, string $modelo, string $color, string $chasis, 
    string $tipo_vehiculo, string $tipo_combustible, string $estado, string $numero_poliza, string $gravamen, int $id_conductor) {
    
    $sql = "INSERT INTO Vehiculos (marca, modelo, color, chasis, tipo_vehiculo, tipo_combustible, estado, numero_poliza, gravamen, id_conductor) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
    $stmt = $this->db->prepare($sql);
    $stmt->bind_param("ssssssssss", $marca, $modelo, $color, $chasis, $tipo_vehiculo, $tipo_combustible, $estado, $numero_poliza, $gravamen, $id_conductor);
    //                 ^^^^^^^^^ ahora son 10 letras "s"
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
    $stmt->bind_param("i", $id_vehiculo);
    return $stmt->execute();
    }

/*
public function obtenerCirculaciones() {
    $sql = "SELECT c.*, v.placa 
            FROM Circulaciones c
            LEFT JOIN Vehiculos v ON c.id_vehiculo = v.id_vehiculo";
    
    $result = $this->db->query($sql);
    return $result->fetch_all(MYSQLI_ASSOC);
}*/
}
?>

