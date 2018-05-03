<?php
function debugea($wlstring)
{
    $dt = date("Y-m-d H:i:s:u ");
    $dia = date("Ymd");
    error_log("$dt $wlstring \n",3,"/tmp/wsavisos$dia.log");
}
?>
