<?php
	$_SESSION['challenge']=md5(rand(1,100000));
	include "conneccion.php";		
	require "soldatos.php";
	session_unset();	
##	print_r($_SESSION);
	$soldatos = new soldatos();
	$soldatos->destino = 'ccajas';
	$soldatos->connection = $connection;
	$soldatos->datos = array('MTA');
	$soldatos->opcionesbin = array('','');
	$soldatos->descripcion='Entrada Al Sistema';
	$soldatos->idmenu='';
##20071105	if(isset($_GET['filtro']))
	if(isset($_POST['filtro']))
	{
##20071105		$soldatos->filtro=$_GET['filtro'];
		$soldatos->filtro=$_POST['filtro'];##20071105
	}
	//$soldatos->	desplemanto();
//	echo "<br><br><br><br><br><br>";
	$soldatos->despledatos();
	$arraydes = array( 0 => "IE", 1 => "AC", 2=> "ZIP",3=> "CONECT");
	$soldatos->descargas ($arraydes,$soldatos->menu["table_width"],$soldatos->menu["table_height"],$soldatos->menu["table_align"]);
    echo "	<script language=\"JavaScript\">";
	echo "	actualizaRelog ();	";
	echo "	</script>	";
?>
