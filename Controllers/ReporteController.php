<?php

require_once "../Config/conexion.php";

class ReporteController
{
    private mysqli $conexion;

    public function __construct(mysqli $conexion)
    {
        $this->conexion = $conexion;
    }


    public function obtenerVehiculos()
    {
        $sql = "SELECT * FROM vehiculos";
        $resultado = mysqli_query($this->conexion, $sql);

        return $resultado;
    }

    public function obtenerConductores()
    {
        $sql = "SELECT * FROM conductores";
        return mysqli_query($this->conexion, $sql);
    }

    public function obtenerLicencias()
    {
        $sql = "SELECT * FROM licencias";
        return mysqli_query($this->conexion, $sql);
    }
}