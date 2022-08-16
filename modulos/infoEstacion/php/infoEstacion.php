<?php 

session_start();
require "../../../bd/CRUD.php";
require "../../../libs/template/template.inc";
$t = new Template('../template');

//Archivos comunes
$t->set_file(array(
    "tablaInfo" => "tablaInfo.html",
    "unFilaTablaInfo" => "unaFilaTablaInfo.html",
    "infoEstacion" => "infoEstacion.html",
    "cardsInfos"=>"cardsInfos.html"
));
       //card
      $t->set_var('descriEstacion',null);
      $t->set_var('todas_card_info',null);
      $t->set_var('Temperatura',null);
      $t->set_var('Humedad',null);
      $t->set_var('Precipitacion',null);
      $t->set_var('DireccionViento',null);
      $t->set_var('VelocidadViento',null);
      $t->set_var('descriCard',null);
      $t->set_var('Fecha',null);

      //tabla 
      $t->set_var('todasLecturas',null);
      $t->set_var('temperatura',null);
      $t->set_var('humedad',null);
      $t->set_var('precipitacion',null);
      $t->set_var('direccionViento',null);
      $t->set_var('velocidadViento',null);
      $t->set_var('fechayHora',null);
      $t->set_var('tablaInfoCompleta',null);



      date_default_timezone_set('america/argentina/buenos_aires');
      $fecha_actual = date("Y-m-d");
      $t->set_var('fechaActual',$fecha_actual);
      $id_estacion= $_POST['id'];
      $con = new crud();
      $sqlUtimaLectura="SELECT * FROM sensores,estaciones WHERE rela_estaciones=id_estaciones and id_estaciones=".$id_estacion." ORDER by id_sensores DESC LIMIT 1 ";
      $sentenciaUltimaLectura=$con->conexion_db->prepare($sqlUtimaLectura); 
      $sentenciaUltimaLectura->execute(array());
      $resultado=$sentenciaUltimaLectura->fetchAll(PDO::FETCH_ASSOC);

       //360º indican viento del norte, cercanos a 90º viento del este, 180º del sur y 270º del oeste.
      $t->set_var('descriCard','Útima lectura registrada');
      foreach ($resultado as $clave => $valor) {
            $t->set_var('Temperatura',$valor['temperatura_sensores']);
            $t->set_var('Humedad',$valor['humedad_sensores']);
            $t->set_var('Precipitacion',$valor['precipitacion_sensores']);
            $t->set_var('DireccionViento',$valor['direcc_viento_sensores']);
            $date = new DateTime($valor['date_estaciones']);
            $t->set_var('Fecha',$date->format('d-m-Y H:i:s'));
            $t->set_var('VelocidadViento',$valor['veloc_viento_sensores']);
            $t->set_var('descriEstacion',$valor['descri_estaciones']."/ ".$valor['direccion_estaciones']);
            $t->parse('todas_card_info','cardsInfos',true);
      } 

      $sqlUtimaLectura="SELECT * FROM sensores,estaciones WHERE rela_estaciones=id_estaciones and id_estaciones=".$id_estacion."  and   date_estaciones like '%$fecha_actual%' ORDER by id_sensores DESC ";
      $sentenciaUltimaLectura=$con->conexion_db->prepare($sqlUtimaLectura); 
      $sentenciaUltimaLectura->execute(array());
      $resultado=$sentenciaUltimaLectura->fetchAll(PDO::FETCH_ASSOC);
      foreach ($resultado as $clave => $valor) {
            $t->set_var('temperatura',$valor['temperatura_sensores']);
            $t->set_var('humedad',$valor['humedad_sensores']);
            $t->set_var('precipitacion',$valor['precipitacion_sensores']);
            $t->set_var('direccionViento',$valor['direcc_viento_sensores']);
            $date = new DateTime($valor['date_estaciones']);
            $t->set_var('fechayHora',$date->format('d-m-Y H:i:s'));
            $t->set_var('velocidadViento',$valor['veloc_viento_sensores']);
            $t->parse('todasLecturas','unFilaTablaInfo',true);
      } 
      
      $t->parse('tablaInfoCompleta','tablaInfo');





      $con->conexion_db=null;
      $t->pparse("OUT", "infoEstacion");
?>