<?php
    session_unset();
    $_REQUEST['tablero']=8;
    $_REQUEST['modulo']=1;
    $_REQUEST['desmodulo']=htmlspecialchars("ENTREGA DE TRAMITES");
    $_REQUEST['turnos']=2;
    include("filas_rppc_wsNuevo.php");
?>

