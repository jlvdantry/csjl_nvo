<?php
include('class.pdf2text.php');
$a = new PDF2Text();
$a->setFilename('detalleCuenta.pdf');
$a->showprogress=false;
$a->decodePDF();
echo $a->output();
?>
