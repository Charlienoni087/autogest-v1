<?php

class Reportes {
    private mysqli $db;

    public int $id_reporte;
    public string $fecha;
    public string $hora_entrada;
    public string $hora_salida;
    public int $id_conductor;
    public int $id_vehiculo;

    public function __construct(mysqli $conexion) {
        $this->db = $conexion;
    }


    public function obtenerTodos(): array {
        $sql = "SELECT r.id_reporte, r.fecha, r.hora_entrada, r.hora_salida,
                       r.id_conductor, r.id_vehiculo,
                       c.nombre_conductor,
                       v.marca, v.modelo,
                       ci.placa
                FROM reportes r
                INNER JOIN conductores c ON r.id_conductor = c.id_conductor
                INNER JOIN vehiculos v ON r.id_vehiculo = v.id_vehiculo
                INNER JOIN circulacion ci ON v.id_circulacion = ci.id_circulacion
                ORDER BY r.fecha DESC, r.hora_entrada DESC";
        $resultado = $this->db->query($sql);
        return $resultado ? $resultado->fetch_all(MYSQLI_ASSOC) : [];
    }

    public function obtenerPorId(int $id_reporte): ?array {
        $sql = "SELECT * FROM reportes WHERE id_reporte = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $id_reporte);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $fila = $resultado->fetch_assoc();
        return $fila ?: null;
    }

   
    public function obtenerFiltrados(?string $fecha_inicio, ?string $fecha_fin, ?int $id_conductor, ?int $id_vehiculo): array {
        $condiciones = [];
        $tipos = "";
        $valores = [];

        if (!empty($fecha_inicio)) {
            $condiciones[] = "r.fecha >= ?";
            $tipos .= "s";
            $valores[] = $fecha_inicio;
        }
        if (!empty($fecha_fin)) {
            $condiciones[] = "r.fecha <= ?";
            $tipos .= "s";
            $valores[] = $fecha_fin;
        }
        if (!empty($id_conductor)) {
            $condiciones[] = "r.id_conductor = ?";
            $tipos .= "i";
            $valores[] = $id_conductor;
        }
        if (!empty($id_vehiculo)) {
            $condiciones[] = "r.id_vehiculo = ?";
            $tipos .= "i";
            $valores[] = $id_vehiculo;
        }

        $sql = "SELECT r.id_reporte, r.fecha, r.hora_entrada, r.hora_salida,
                       r.id_conductor, r.id_vehiculo,
                       c.nombre_conductor,
                       v.marca, v.modelo,
                       ci.placa
                FROM reportes r
                INNER JOIN conductores c ON r.id_conductor = c.id_conductor
                INNER JOIN vehiculos v ON r.id_vehiculo = v.id_vehiculo
                INNER JOIN circulacion ci ON v.id_circulacion = ci.id_circulacion";

        if (!empty($condiciones)) {
            $sql .= " WHERE " . implode(" AND ", $condiciones);
        }
        $sql .= " ORDER BY r.fecha DESC, r.hora_entrada DESC";

        if (empty($valores)) {
            $resultado = $this->db->query($sql);
            return $resultado ? $resultado->fetch_all(MYSQLI_ASSOC) : [];
        }

        $stmt = $this->db->prepare($sql);
        $refs = [];
        $refs[] = &$tipos;
        foreach ($valores as $key => $val) {
            $refs[] = &$valores[$key];
        }
        call_user_func_array([$stmt, 'bind_param'], $refs);
        $stmt->execute();
        $resultado = $stmt->get_result();
        return $resultado->fetch_all(MYSQLI_ASSOC);
    }

    public function crear(string $fecha, string $hora_entrada, string $hora_salida, int $id_conductor, int $id_vehiculo): bool {
        $sql = "INSERT INTO reportes (fecha, hora_entrada, hora_salida, id_conductor, id_vehiculo) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("sssii", $fecha, $hora_entrada, $hora_salida, $id_conductor, $id_vehiculo);
        return $stmt->execute();
    }

    public function actualizar(int $id_reporte, string $fecha, string $hora_entrada, string $hora_salida, int $id_conductor, int $id_vehiculo): bool {
        $sql = "UPDATE reportes
                SET fecha = ?, hora_entrada = ?, hora_salida = ?, id_conductor = ?, id_vehiculo = ?
                WHERE id_reporte = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("sssiii", $fecha, $hora_entrada, $hora_salida, $id_conductor, $id_vehiculo, $id_reporte);
        return $stmt->execute();
    }

    public function eliminar(int $id_reporte): bool {
        $sql = "DELETE FROM reportes WHERE id_reporte = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $id_reporte);
        return $stmt->execute();
    }

   
    public function listarConductores(): array {
        $sql = "SELECT id_conductor, nombre_conductor FROM conductores ORDER BY nombre_conductor";
        $resultado = $this->db->query($sql);
        return $resultado ? $resultado->fetch_all(MYSQLI_ASSOC) : [];
    }

    
    public function listarVehiculos(): array {
        $sql = "SELECT v.id_vehiculo, v.marca, v.modelo, ci.placa
                FROM vehiculos v
                INNER JOIN circulacion ci ON v.id_circulacion = ci.id_circulacion
                ORDER BY v.marca";
        $resultado = $this->db->query($sql);
        return $resultado ? $resultado->fetch_all(MYSQLI_ASSOC) : [];
    }
}
?>
