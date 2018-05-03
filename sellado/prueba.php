<?php
/*
        debugea('entro a validafiel'.$pregunta["privada"]." publica=".$pregunta["publica"]);
        $filepr=$pregunta["privada"];      // Ruta al archivo
        $llavepr=file_get_contents($filepr);
        $filepu=$pregunta["publica"];      // Ruta al archivo
        $llavepu=file_get_contents($filepu);
        debugea('paso la lectura de la llave publica'.$llavepu);
        $pkeyid = openssl_pkey_get_private($llavepr,$pregunta["password"]);
        debugea('leyo la llave privada'.$pkeyid);
        $ok=openssl_x509_check_private_key($llavepu,$pkeyid);
        debugea('resultado del chequeo'.$ok);
        $respuesta =array(
                "valido" => $ok
                );
        debugea('entro en checarsello');
        return $respuesta;
*/
$path="../upload_ficheros/";

$rutaCer = file_get_contents($path."44447.cer");
//$rutaCer = file_get_contents($path."43442.cer");
$rutaKey = file_get_contents($path."44448.key");
$pri="-----BEGIN ENCRYPTED PRIVATE KEY-----\n" . chunk_split(base64_encode($rutaKey), 64, "\n") . "-----END ENCRYPTED PRIVATE KEY-----";
$pub="-----BEGIN CERTIFICATE-----\n" . chunk_split(base64_encode($rutaCer), 64, "\n") . "-----END CERTIFICATE-----";
$pkeyid = openssl_pkey_get_private($pri,"12345678b");
echo "pkeyid=".$pkeyid."\n";
$cer = openssl_pkey_get_public($pub);
echo "cer=".$cer."\n";
$ok=openssl_x509_check_private_key($pub,$pkeyid);
echo "ok=".$ok."\n";
?>
