<?php 

session_start();
require "bd/CRUD.php";
require "libs/template/template.inc";
$t = new Template('./modulos/index/template');

//Archivos comunes
$t->set_file(array(

    "index" => "index.html",
    "login"=>"login.html",
    "indexCargado"=>"indexContenedor.html"
));


$t->set_var('contenidoAcual',null);
$t->set_var('usuario',null);

if(isset($_POST['email-username']) and isset($_POST['password'])){
    $con = new crud();
    $sql="SELECT * FROM usuarios WHERE descri_usuario='".$_POST['email-username']."' and password_usuario='".$_POST['password']."'";
    $res=$con->conexion_db->prepare($sql); 
    $res->execute(array());
    $resultado=$res->fetchAll(PDO::FETCH_ASSOC);
    if(count($resultado)>0){
        foreach ($resultado as $clave => $valor) {
            $_SESSION['username'] = $valor['descri_usuario'];
            $_SESSION['apeynom'] = $valor['apeynom_usuario'];
        }
    }
    $con->conexion_db=null;
}

if(isset($_SESSION['username']) &&  ($_SESSION['username']!="")  ){   
    $t->set_var('usuario',$_SESSION['apeynom']);
    $t->parse('contenidoAcual','indexCargado',true);
}else{
$t->parse('contenidoAcual','login',true);
}

 
  $t->pparse("OUT", "index");
?>

