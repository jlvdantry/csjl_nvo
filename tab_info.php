<?php
    session_unset();
//$modulo=34;
//$tablero=13;
//$desmodulo="ATENCIÓN PRIORITARÍA";
// $turnos=2;
    $_REQUEST['tablero']=8;
    $_REQUEST['modulo']=1;
    $_REQUEST['desmodulo']=htmlspecialchars("ENTREGA DE TRAMITES");
    $_REQUEST['turnos']=2;
include("filas_rppc_wsNuevo.php");
?>

