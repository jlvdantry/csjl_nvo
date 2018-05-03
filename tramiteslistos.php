<?php
	session_unset();	
        session_destroy(); 
	$_SESSION['challenge']=md5(rand(1,100000));
	include "conneccion.php";		
	require "soldatos.php";
	$soldatos = new soldatos();
	$soldatos->destino = 'ccajas';
	$soldatos->connection = $connection;
	$soldatos->datos = array('MTA');
	$soldatos->opcionesbin = array('','');
	##$soldatos->descripcion='Tramites listos para entregar';
	$soldatos->idmenu='2396';
	if(isset($_POST['filtro']))
	{
		$soldatos->filtro=$_POST['filtro'];##20071105
	}
	$soldatos->despledatos();
?>
