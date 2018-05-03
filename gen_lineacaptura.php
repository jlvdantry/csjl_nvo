<?php 
        $dir_abs=".";
        $post_data = array();
    $post_data['importe'] = '2937';
    $post_data['escritura'] = '0';
    $post_data['cantidad'] = '1';
    $post_data['reduccion'] = '0';
    $post_data['art'] = '0';
    $post_data['frac'] = '0';
    $post_data['concepto'] = 'ag2001';
    $post_data['laDependencia'] = 'AG';
    $ch = curl_init("https://data.finanzas.cdmx.gob.mx/formato_lc/rpp/rpp_fij_resultado.php");
    curl_setopt($ch, CURLOPT_COOKIEJAR, $dir_abs."cookieFileName");//ubicacion de la cookie que se genera del inicio de sesion
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);

    if( ! $result = curl_exec($ch))
    {
        trigger_error(curl_error($ch));
    }
    curl_close($ch);
    echo $result."\n";
?>
