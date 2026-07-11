<?php
session_start();
ob_start();
require_once '../config/conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user = trim($_POST['nombre_usuario']);
    $pass = trim($_POST['contrasena']);

    $stmt = $conexion->prepare("SELECT id_usuario, contrasena FROM usuarios WHERE nombre_usuario = ?");
    $stmt->bind_param("s", $user);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result && $row = $result->fetch_assoc()) {
        if (password_verify($pass, $row['contrasena'])) {
            $_SESSION['id_usuario'] = $row['id_usuario'];

            $ruta_dashboard = realpath(__DIR__ . '/../Views/main.php');

            if ($ruta_dashboard && file_exists($ruta_dashboard)) {
                header("Location: /AutoGest/Views/main.php");
                exit();
            } else {
                die("Error crítico: No se encuentra el archivo. Ruta buscada: " . __DIR__ . '/../Views/main.php');
            }
        } else {
            echo "<script>alert('Usuario o contraseña incorrectos.');</script>";
        }
    } else {
        echo "<script>alert('Usuario o contraseña incorrectos.');</script>";
    }


/*    if ($row = $result->fetch_assoc()) {
        echo "Usuario encontrado en BD.<br>";
        $pass_recibida = $_POST['contrasena']; // Asegúrate que este name coincida con tu HTML
$hash_en_bd = $row['contrasena'];

echo "--- DEPURACIÓN DE SEGURIDAD ---<br>";
echo "Contraseña recibida desde formulario: '" . $pass_recibida . "'<br>";
echo "Longitud de contraseña recibida: " . strlen($pass_recibida) . "<br>";
echo "Hash recuperado de BD: " . $hash_en_bd . "<br>";

if (password_verify($pass_recibida, $hash_en_bd)) {
    echo "¡ÉXITO!";
} else {
    echo "¡FALLO!<br>";
    echo "Posible causa: La contraseña en el formulario no es exactamente '123456' o hay espacios ocultos.";
}
}
}*/
}

   
?>