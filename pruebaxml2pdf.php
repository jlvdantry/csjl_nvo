<?php
include('xml2pdf/Xml2Pdf.php');
$obj = new Xml2Pdf('upload_ficheros/46106.xml');
$pdf = $obj->render();
$pdf->Output();
?>

