<?php
session_start();
include("conneccion.php");
require_once("firma_digital_class.php");
$va = new firma_digital_class();	
$va->connection = $connection;
$va->argumentos = $_POST;
$va->funcion = $opcion;
$va->procesa();	
?>
