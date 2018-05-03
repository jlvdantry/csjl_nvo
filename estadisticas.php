<?php
session_start();
include("conneccion.php");
require_once("esta_class.php");
$va = new esta_class();
$va->connection = $connection;
if ($opcion=="")
{
        $va->inicio();
        echo "<error>No esta definida la opcion a ejecutar</error>";
        $va->termina();
}
else
{
        ##echo "sipi".var_dump($_POST)."vas";
        ##echo "sipiget".var_dump($_GET)."vasget";
        $va->argumentos = $_GET;
        $va->funcion = $opcion;
        $va->procesa();
}
?>

