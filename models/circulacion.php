<?php

class Circulacion
{
    private mysqli $conn;
    private string $tabla = "circulacion";

    public function __construct(mysqli $conexion)
    {
        $this->conn = $conexion;
    }

    /**
     * Listar todos los registros de circulacion
     */
    public function obtenerCirculaciones(): array
    {
        $sql = "SELECT id_circulacion, codigo_circulacion, cilindraje, tonelaje, pasajeros, placa
                FROM {$this->tabla}
                ORDER BY id_circulacion DESC";

        $resultado = $this->conn->query($sql);

        $registros = [];
        if ($resultado && $resultado->num_rows > 0) {
            while ($fila = $resultado->fetch_assoc()) {
                $registros[] = $fila;
            }
        }

        return $registros;
    }

    /**
     * Obtener un registro por su id (util para cargar el formulario de edicion)
     */
    public function obtenerCirculacionPorId(int $id): ?array
    {
        $sql = "SELECT id_circulacion, codigo_circulacion, cilindraje, tonelaje, pasajeros, placa
                FROM {$this->tabla}
                WHERE id_circulacion = ?";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();

        $registro = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        return $registro ?: null;
    }

    /**
     * Crear un nuevo registro
     */
    public function crearCirculacion(
        string $codigo_circulacion,
        string $cilindraje,
        string $tonelaje,
        int $pasajeros,
        string $placa
    ): bool {
        $sql = "INSERT INTO {$this->tabla}
                (codigo_circulacion, cilindraje, tonelaje, pasajeros, placa)
                VALUES (?, ?, ?, ?, ?)";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param(
            "sssis",
            $codigo_circulacion,
            $cilindraje,
            $tonelaje,
            $pasajeros,
            $placa
        );

        $exito = $stmt->execute();
        $stmt->close();

        return $exito;
    }

    /**
     * Editar/actualizar un registro existente
     */
    public function actualizarCirculacion(
        int $id,
        string $codigo_circulacion,
        string $cilindraje,
        string $tonelaje,
        int $pasajeros,
        string $placa
    ): bool {
        $sql = "UPDATE {$this->tabla}
                SET codigo_circulacion = ?,
                    cilindraje = ?,
                    tonelaje = ?,
                    pasajeros = ?,
                    placa = ?
                WHERE id_circulacion = ?";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param(
            "sssisi",
            $codigo_circulacion,
            $cilindraje,
            $tonelaje,
            $pasajeros,
            $placa,
            $id
        );

        $exito = $stmt->execute();
        $stmt->close();

        return $exito;
    }

    /**
     * Verifica si algun vehiculo esta usando esta circulacion
     * (llamar antes de eliminarCirculacion para evitar el error de foreign key)
     */
    public function estaEnUso(int $id): bool
    {
        $sql = "SELECT COUNT(*) as total FROM vehiculos WHERE id_circulacion = ?";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();

        $resultado = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        return $resultado['total'] > 0;
    }

    /**
     * Eliminar un registro por su id
     */
    public function eliminarCirculacion(int $id): bool
    {
        $sql = "DELETE FROM {$this->tabla} WHERE id_circulacion = ?";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);

        $exito = $stmt->execute();
        $stmt->close();

        return $exito;
    }
}