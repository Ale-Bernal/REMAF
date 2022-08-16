<?php
define('DB_NAME', 'remaf');

define('DB_USER', 'root');
define('DB_PASS', '');

try {
 $conexion= new PDO("mysql:host=localhost;dbname=".DB_NAME,DB_USER,DB_PASS);
   $conexion->exec("SET CHARACTER  SET utf8");
   echo "successfully connected";

}catch(PDOException $e){
   
    echo " Error de Conexion . La linea de error es:  " . $e->getLine();
    echo "Error: ".$e->getMessage();
}



?>