<?php
           include "PdfToText.phpclass.php";
           $pdf    =  new PdfToText ( 'detalleCuenta.pdf' ) ;
           echo $pdf -> Text ;             // or : echo ( string ) $pdf ;
?>
