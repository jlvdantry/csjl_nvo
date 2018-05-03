<?php
	include "conneccion.php";		
	require "xmlhttp_class.php";
	$xm = new xmlhttp_class();
        $xm->Enviaemail('prueba envio email');
?>
