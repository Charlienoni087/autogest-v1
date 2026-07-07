<?php
$password = '123456';
$hash_generado = password_hash($password, PASSWORD_DEFAULT);

echo "Hash recién generado por tu servidor: " . $hash_generado . "<br>";

if (password_verify($password, $hash_generado)) {
    echo "¡VERIFICACIÓN EXITOSA con el nuevo hash!";
} else {
    echo "FALLÓ. Hay un problema grave con la función password_verify en tu servidor.";
}
?>