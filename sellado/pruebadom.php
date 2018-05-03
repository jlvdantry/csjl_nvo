<?php
error_reporting(E_ALL | E_STRICT);
$xml = DOMDocument::load("./1.xml",16398);
$xsl = DOMDocument::load("vuat_XSL_cadena.xsl");
       $xsltProc = new XSLTProcessor();
       $xsltProc->importStyleSheet($xsl);
       echo $xsltProc->transformToXML($xml);

##echo $doc->saveXML();
##echo $doc;
?>
