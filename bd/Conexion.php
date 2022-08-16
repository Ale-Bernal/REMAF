<?php 
 require "config.php";

  class Conexion{
     
     public $conexion_db;

     public function __construct () {
       
	
     	try {
     		$this->conexion_db= new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME,DB_USER,DB_PASS);

     		$this->conexion_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

				 $this->conexion_db->exec("SET CHARACTER  SET utf8");
             return $this->conexion_db;

     	}catch(PDOException $e){
			
			 echo " Error de Conexion . La linea de error es:  " . $e->getLine();
			 echo "Error: ".$e->getMessage();
     	}
     	
       /*
	   if ($this->conexion_db->connect_errno {
	   	echo "Fallo la conexion a la base de datos " . $this->conexion_db->connect_errno ;
	     return;
	   }
	  
	  $this->conexion_db->set_charset(DB_CHARSET);
	  */
      

    }
  }

 ?>