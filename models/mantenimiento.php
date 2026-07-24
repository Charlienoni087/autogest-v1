<?php

class MantenimientoModel {
    private $conexion;

    public function __construct($db) {
        $this->conexion = $db;
    }

    public function obtenerMantenimientos() {
        $query = "SELECT m.*, v.marca, v.modelo 
                FROM Mantenimiento m
                INNER JOIN Vehiculos v ON m.id_vehiculo = v.id_vehiculo
                ORDER BY m.fecha_mantenimiento DESC";
                
        $resultado = $this->conexion->query($query);
        if ($resultado) {
            return $resultado->fetch_all(MYSQLI_ASSOC);
        }
        return [];
    }

    public function obtenerVehiculos() {
        $query = "SELECT id_vehiculo, marca, modelo, chasis FROM Vehiculos";
        $resultado = $this->conexion->query($query);
        if ($resultado) {
            return $resultado->fetch_all(MYSQLI_ASSOC);
        }
        return [];
    }

    public function obtenerMantenimientoPorId($id) {
        $query = "SELECT * FROM Mantenimiento WHERE id_mantenimiento = ?";
        $stmt = $this->conexion->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    // para las fecha de salida
    public function insertarMantenimiento($id_vehiculo, $fecha_mantenimiento, $fecha_salida, $descripcion, $costo, $estado) {
        $query = "INSERT INTO Mantenimiento (id_vehiculo, fecha_mantenimiento, fecha_salida, descripcion, costo, estado) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $this->conexion->prepare($query);
        // "issds" -> i(int), s(string), s(string), d(double/float), s(string) con fecha_salida añadida pasa a "isssds"
        $stmt->bind_param("isssds", $id_vehiculo, $fecha_mantenimiento, $fecha_salida, $descripcion, $costo, $estado);
        return $stmt->execute();
    }

    // actualiza los registro existente
    public function actualizarMantenimiento($id, $id_vehiculo, $fecha_mantenimiento, $fecha_salida, $descripcion, $costo, $estado) {
        $query = "UPDATE Mantenimiento SET id_vehiculo = ?, fecha_mantenimiento = ?, fecha_salida = ?, descripcion = ?, costo = ?, estado = ? WHERE id_mantenimiento = ?";
        $stmt = $this->conexion->prepare($query);
        $stmt->bind_param("isssdsi", $id_vehiculo, $fecha_mantenimiento, $fecha_salida, $descripcion, $costo, $estado, $id);
        return $stmt->execute();
    }

    // para eliminar registro
    public function eliminarMantenimiento($id) {
        $query = "DELETE FROM Mantenimiento WHERE id_mantenimiento = ?";
        $stmt = $this->conexion->prepare($query);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}
?>