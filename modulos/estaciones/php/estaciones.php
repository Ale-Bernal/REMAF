<?php 

session_start();
require "../../../bd/CRUD.php";
require "../../../libs/template/template.inc";
$t = new Template('../template');

//Archivos comunes
$t->set_file(array(

    "estaciones" => "estaciones.html",
    "unaEstacion"=>"una_estacion.html"
));

      $t->set_var('todas_estaciones',null);
      $t->set_var('descri_estacion',null);
      $t->set_var('direccion_estacion',null);
      $t->set_var('localidad_estacion',null);
      $t->set_var('estado_estacion',null);
      $t->set_var('id_estacion',null);
      $t->set_var('todas_estaciones',null);
      
      $con = new crud();
      $sql="SELECT * FROM estaciones, localidad WHERE rela_localidad= id_localidad;";
      $sentencia=$con->conexion_db->prepare($sql); 
      $sentencia->execute(array());
      $resultado=$sentencia->fetchAll(PDO::FETCH_ASSOC);
      foreach ($resultado as $clave => $valor) {
            $t->set_var('descri_estacion',$valor['descri_estaciones']);
            $t->set_var('direccion_estacion',$valor['direccion_estaciones']);
            $t->set_var('localidad_estacion',$valor['descri_localidad']);
            
            
            // debo hacer un post
            $t->set_var('estado_estacion','activo');
            $t->set_var('tipo_estado','success');
            

            $t->set_var('id_estacion',$valor['id_estaciones']);
            $t->parse('todas_estaciones','unaEstacion',true);
      } 
      
      
      $con->conexion_db=null;
      $t->pparse("OUT", "estaciones");
?>