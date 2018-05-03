<?php
ini_set('display_errors','On');
error_reporting(E_ALL);

   include("class_validalinea.php");
   include "conneccion.php";
   $x= new class_validalinea();
   $x->lc='932004029810786PA0D0';
   $pos=$x->validapago();
   $x->marcapago($pos,566277,$connection,0);

?>
