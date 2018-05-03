<?php
	include "conneccion.php";		
	require "soldatos.php";
	$soldatos = new soldatos();
        $soldatos->Enviaemail('prueba envio email');
?>
