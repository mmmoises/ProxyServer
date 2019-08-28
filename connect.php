<?php
$servername = "localhost";
$username = "root";
$password = "";
$db = "proxy";
$conn = mysqli_connect($servername, $username, $password, $db);

$dirRoot = "/proxy/index.php";

$ifPuertoDef =  (!isset($_SERVER["HTTPS"]) && $_SERVER["SERVER_PORT"] === 80) || (isset($_SERVER["HTTPS"]) && $_SERVER["SERVER_PORT"] === 443);
$prefixPort = $ifPuertoDef ? "" : ":" . $_SERVER["SERVER_PORT"];
$prefixHost = $_SERVER["HTTP_HOST"];
$prefixHost = strpos($prefixHost, ":") ? implode(":", explode(":", $_SERVER["HTTP_HOST"], -1)) : $prefixHost;
if (!defined('PROXY_PREFIX_ALL'))
	define("PROXY_PREFIX_ALL", "http" . (isset($_SERVER["HTTPS"]) ? "s" : "") . "://" . $prefixHost . $prefixPort . $dirRoot . "?");
$docRoot = $_SERVER['DOCUMENT_ROOT']; //Referencia la dir root


if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if (!empty($_SERVER['HTTP_CLIENT_IP']))
    {
      $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
    }
elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
    {
      $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
    }
else
    {
      $ipaddress = $_SERVER['REMOTE_ADDR'];
    }

?>