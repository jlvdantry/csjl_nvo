<? 

	include "conneccion.php";		
	require "soldatos.php";
	$soldatos = new soldatos();
	$soldatos->destino = 'ccajas';
	$soldatos->connection = $connection;
	$soldatos->datos = array('MTA');
	$soldatos->opcionesbin = array('','');
	$soldatos->idmenu=1028;
	if(isset($_GET['filtro']))
	{
		$soldatos->filtro=$_GET['filtro'];
	}
	$soldatos->despledatos();
?>
