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
    $sql = "SELECT v.*, c.placa, cond.nombre_conductor,
                   (SELECT m.estado FROM Mantenimiento m 
                    WHERE m.id_vehiculo = v.id_vehiculo 
                    ORDER BY m.fecha_mantenimiento DESC LIMIT 1) AS estado_mantenimiento
            FROM vehiculos v
            LEFT JOIN circulacion c ON v.id_circulacion = c.id_circulacion
            LEFT JOIN conductores cond ON v.id_conductor = cond.id_conductor";
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
    
    public function puedeAsignarVehiculo(int $id_vehiculo): array {
    $sql = "SELECT estado FROM Vehiculos WHERE id_vehiculo = ?";
    $stmt = $this->db->prepare($sql);
    $stmt->bind_param("i", $id_vehiculo);
    $stmt->execute();
    $vehiculo = $stmt->get_result()->fetch_assoc();

    if (!$vehiculo || strtolower($vehiculo['estado']) !== 'activo') {
        return ['permitido' => false, 'motivo' => 'el vehículo está inactivo'];
    }

    $sql = "SELECT estado FROM Mantenimiento 
            WHERE id_vehiculo = ? 
            ORDER BY fecha_mantenimiento DESC LIMIT 1";
    $stmt = $this->db->prepare($sql);
    $stmt->bind_param("i", $id_vehiculo);
    $stmt->execute();
    $mantenimiento = $stmt->get_result()->fetch_assoc();

    if ($mantenimiento && in_array(strtolower($mantenimiento['estado']), ['pendiente', 'en proceso'])) {
        return ['permitido' => false, 'motivo' => 'el vehículo está en mantenimiento'];
    }

    return ['permitido' => true, 'motivo' => ''];
}

   public function crearVehiculo(string $marca, string $modelo, string $color, string $chasis, 
    string $tipo_vehiculo, string $tipo_combustible, string $estado, string $numero_poliza, int $gravamen, int $id_conductor, int $id_circulacion) {
    
    $sql = "INSERT INTO Vehiculos (marca, modelo, color, chasis, tipo_vehiculo, tipo_combustible, estado, numero_poliza, gravamen, id_conductor, id_circulacion) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
    $stmt = $this->db->prepare($sql);
    $stmt->bind_param("ssssssssiii", $marca, $modelo, $color, $chasis, $tipo_vehiculo, $tipo_combustible, $estado, $numero_poliza, $gravamen, $id_conductor, $id_circulacion);
    return $stmt->execute();
}

public function actualizarVehiculo(int $id_vehiculo, string $marca, string $modelo, string $color, string $chasis, string $tipo_vehiculo, string $tipo_combustible, string $estado, string $numero_poliza, int $gravamen, int $id_conductor, int $id_circulacion) {
    $sql = "UPDATE Vehiculos
            SET marca = ?, modelo = ?, color = ?, chasis = ?, tipo_vehiculo = ?, tipo_combustible = ?, estado = ?, numero_poliza = ?, gravamen = ?, id_conductor = ?, id_circulacion = ?
            WHERE id_vehiculo = ?";
    $stmt = $this->db->prepare($sql);
    $stmt->bind_param(
        "ssssssssiiii",
        $marca, $modelo, $color, $chasis, $tipo_vehiculo, $tipo_combustible, $estado, $numero_poliza, $gravamen,
        $id_conductor, $id_circulacion, $id_vehiculo
    );
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


