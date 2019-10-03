<?php
/*
	include "conneccion.php";		
	require "xmlhttp_class.php";
	$xm = new xmlhttp_class();
        $xm->Enviaemail('prueba envio email');
*/
	require "xmlhttp_class.php";
                session_start();
                include("conneccion.php");
                $va = new xmlhttp_class();
                $va->connection = $connection;
                $va->argumentos = $_POST;               ##20071105
                $va->funcion = 'Enviaemail';
                ##$va->servidort = $wlhost;  //20070822
                ##$va->badat= $wldbname;  //20070822
                ##$va->puerto= $wlport;  //20070822
                $va->procesa();

?>
