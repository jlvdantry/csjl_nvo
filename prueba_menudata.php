<?php
include("menudata.php");
include "conneccion.php";
##	$connection = pg_connect("host='localhost'
##    	                      dbname='pupan20060519f'
##        	                  user='postgres'
##            	              password='dantry12'")
##                	          or die("Couldn't make connection.");	
$v = new menudata();
$v->idmenu=999;
$v->filtro="nombre='NOMBRE'";
$v->connection=$connection;
$v->damemetadata();
echo "<pre>";
##echo "<fuente>".$v->camposm["fuente"];
print_r($v->camposm);
echo "menus_movtos";
print_r($v->camposmm);
echo "menus_campos";
print_r($v->camposmc);
echo "menus_campos_eventos";
print_r($v->camposmce);
echo "menus_eventos";
print_r($v->camposme);
echo "menus_subvistas";
print_r($v->camposmsv);
echo "menus_htmltable";
print_r($v->camposmht);
echo "</pre>";
?>
