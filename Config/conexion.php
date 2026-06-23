<?php

$host = "localhost";
$user = "root";
$password = "";
$db = "farmacia";

$conexion = mysqli_connect(
    $host,
    $user,
    $password,
    $db
);

if(!$conexion){
    die("Error de conexión");
}

?>