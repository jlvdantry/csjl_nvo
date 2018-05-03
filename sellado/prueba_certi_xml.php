<?php
ini_set('display_errors','On');
error_reporting(E_ALL);
require_once("certi_XML_class.php");
$x = new certi_XML_class;
$x->arr=array(
                       "NoFirma"=>"No de firma",
                       "sello"=>"Sello digital",
                       "certificado"=>"LKJLIN(KLKLJL)DLKDLJDLDLLDLLDLDOCCCHFGFDGDJUMAÑDJDJJDJDJJ",
                       "FolioCertificado"=>"123ADIUIOJKL",
                       "Concepto"=>"Descripcion del cobro",
                       "NombreDelContribuyente"=>"Nombre del contribuyente",
                       "LineaDeCaptura"=>"lineadecaptura",
                       "Cuenta"=>"cuenta",
                       "FechaEmision"=>"2013-09-13T10:35:35",
                       "Cobros"=> array (
                             "1"=>array (
                              "PuntoDeRecaudacion"=>'PuntoDeRecaudacion1',
                              "FechaDeCobro"=>'2013-09-01',
                              "Caja"=>'123',
                              "Partida"=>'234',
                              "Periodos"=>'Periodos1',
                              "LineaDeCaptura"=>'LineaDeCaptura1',
                              "TotalDelCobro"=>'20'
                                         ),
                             "2"=>array (
                              "PuntoDeRecaudacion"=>'PuntoDeRecaudacion2',
                              "FechaDeCobro"=>'2003-09-01',
                              "Caja"=>'231',
                              "Partida"=>'1233',
                              "Periodos"=>'Periodos2',
                              "LineaDeCaptura"=>'LineaDeCaptura2',
                              "TotalDelCobro"=>'15'
                                         ),
                                     )
                 );
echo $x->genera_xml();
?>

