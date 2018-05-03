<?php
    function conneccion()
    {
        global $parametro1, $parametro2;
        $wlstring = "host='".DBHOST."' dbname='".DB."' user='".$parametro1."' password='".$parametro2."' port=".PORT;
        debugea("string con=".$wlstring);
        $connection = @pg_connect($wlstring);
        if ($connection == "")
        { //debugea('No se pudo conectar'.$parametro1.' password'.$parametro2);
        }
        return $connection;
    }

    function conneccion_tmp()
    {
        $wlstring = "host='".DBHOST."' dbname='".DB."' user='".USERTEMP."' password='".PASSTEMP."' port=".PORT;
        debugea("string con=".$wlstring);
        $connection = pg_connect($wlstring);
        return $connection;
    }
?>
