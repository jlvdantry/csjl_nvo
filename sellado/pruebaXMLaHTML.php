<?php
        require('XSLTProc.php');
        $x = new XSLTProc();
        echo $x->transformToXML(file_get_contents("../upload_ficheros/46106.xml"),"vuat_XSL_html.xsl");
function debugea($wlstring)
{
    $dt = date("Y-m-d H:i:s:u ");
    $dia = date("Ymd");
    error_log("$dt $wlstring \n",3,"ws_oficinaSellado$dia.log");
}

?>
