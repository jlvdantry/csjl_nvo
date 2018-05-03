<?php
ini_set('display_errors','On');
error_reporting(E_ALL);
require_once("vuat_XML_class.php");
$x = new vuat_XML_class;
$x->arr=array(
                       "noFirma"=>"No de firma",
                       "sello"=>"Sello digital",
                       "certificado"=>"LKJLIN(KLKLJL)DLKDLJDLDLLDLLDLDOCCCHFGFDGDJUMADJDJJDJDJJ",
                       "folioCertificado"=>"123ADIUIOJKL",
                       "tramite"=>"Aviso de testmento",
                       "lineaDeCaptura"=>"lineadecaptura",
                       "fechaEmision"=>"2013-09-13T10:35:35",
                       "testador"=>array(
                          "nombre"=>"jose luis",
                          "apellidoPaterno"=>"vasquez",
                          "apellidoMaterno"=>"barbosa",
                          "nacionalidad"=>"mexicana",
                          "lugarDeNacimiento"=>"DF",
                          "fechaDeNacimiento"=>"1959-03-24",
                          "estadoCivil"=>"soltero"
                                         )
                       "Instrumento"=>array(
                          "tipodeTestamento"=>"tipodeTestamento",
                          "escritura"=>"escritura",
                          "volumen"=>"volumen",
                          "nacionalidad"=>"mexicana",
                          "lugarDeNacimiento"=>"DF",
                          "fechaDeNacimiento"=>"1959-03-24",
                          "estadoCivil"=>"soltero"
                 );
echo $x->genera_xml();
?>
