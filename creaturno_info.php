<?php
    session_unset();
//$modulo=34;
//$tablero=13;
// $turnos=2;
    $_REQUEST['tablero']=13;
    $_REQUEST['modulo']=25;
    $_REQUEST['desmodulo']=htmlspecialchars("INFORMES");
    $_REQUEST['turnos']=2;
include("creaturno.php");
?>

