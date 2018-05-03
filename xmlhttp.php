<?php
session_start();
header("Access-Control-Allow-Origin: *");
$t=getdate();
$today=date('Y-m-d h:i:s',$t[0]);
error_log($today." Entro xhttp.php opcion=".$_POST["opcion"]." idmenu=".$_POST["idmenu"]." movto=".$_POST["movto"]." usuario=".$_SESSION["parametro1"]."\n",3,"/var/tmp/errores.log");
include("conneccion.php");
require_once("xmlhttp_class.php");
$va = new xmlhttp_class();
$va->connection = $connection;
if ($_POST["opcion"]=="")
{ 
	$va->inicio();
	echo "<error>No esta definida la opcion a ejecutar</error>";
	$va->termina();	
}
else
{
	## 20071105  Se cambio el get por el post
	## 20071105 $va->argumentos = $_GET;
	$va->argumentos = $_POST;
	$va->funcion = $_POST["opcion"];
	$va->procesa();
}
error_log($today." paso if xhttp.php opcion ".$_POST["opcion"]."\n",3,"/var/tmp/errores.log");
?>
