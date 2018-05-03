<? 
session_start();
require "soldatos.php";
include "conneccion.php";
$soldatos = new soldatos();
$soldatos->destino = 'ccajas';
$soldatos->connection = $connection;
$soldatos->datos = array('MTA');
$soldatos->opcionesbin = array('','');
$soldatos->idmenu=118;
$soldatos->despledatos();
?>
