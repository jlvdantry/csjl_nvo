<?php
session_cache_expire(60);  ## se establece el limite de la sesion de php en 60 min
session_start(); 

##  hay que checar porque hay veces que entra la clave temporal, como que hay un limite y se pierde la session
$wlhost='10.4.12.1';
$wldbname='forapi1.1';
$wlport='5432';
if(isset($parametro1) || isset($parametro2) || isset($bada) || isset($servidor))
{	$connection = pg_connect("host=$servidor dbname=$bada user=$parametro1 password=$parametro2 port=$wlport") or die("Finalizo la session tienes que volver a ingresar");	
        pg_exec($connection,"set client_encoding to 'UTF8'");
}
else
{
    if(isset($escron))
    {	$connection = pg_connect("host=$wlhost dbname=$wldbname user='jlv' password='888aDantryR' port=$wlport") or die("Error en el cron");	}
    else
    {	
//        $_SESSION["parametro1"]='temporal';
        $connection = pg_connect("host=$wlhost	dbname=$wldbname user='temporal' password='Temporal1' port=$wlport") or die("Error con clave temporal1");
        pg_exec($connection,"set client_encoding to 'UTF8'");
    }
}
?>
