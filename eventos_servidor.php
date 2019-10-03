<?php
session_start();
include("conneccion.php");
require_once("eventos_servidor_class.php");
$va = new eventos_servidor_class();	
$va->connection = $connection;
## 20071105  Se cambio el get por el post
## 20071105 $va->argumentos = $_GET;
$va->argumentos = $_POST;
$va->funcion = $_POST['opcion'];
$va->procesa();	
?>
