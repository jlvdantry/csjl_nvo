<?php
session_start();
require "soldatos_ant.php";
include "conneccion.php";

##$connection = pg_connect("host=$servidor
##                          dbname=$bada
##                          user=$parametro1
##                          password=$parametro2")
##                          or die("Couldn't make connection.");

$soldatos = new soldatos();
$soldatos->titulos = 'Captura de Ingresos';
$soldatos->accion = 'brosea.php';
$soldatos->destino = 'ccajas';
$soldatos->boton = 'Mostrarpr';
$soldatos->connection = $connection;
$soldatos->wlfechaini = $wlfechaini;
$soldatos->wlfechafin = $wlfechafin;
$soldatos->datos = array('FE');
$soldatos->opcionesbin = array('','');
$soldatos->despledatos();
?>
