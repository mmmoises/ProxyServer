CREATE DATABASE proxy;
USE proxy;

CREATE TABLE movimietnos(
	id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    ip varchar(100),
    pagina varchar(100)   
);

CREATE TABLE premium(
	id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user varchar(100),
    pass varchar(100),
    pr INT(6)  
);

CREATE TABLE cookies(
	id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    keyy TEXT,
    val TEXT
);

CREATE TABLE credenciales(
	id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    usuario TEXT,
    contrasena TEXT
);