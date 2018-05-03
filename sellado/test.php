<?php

include("../chilkat-9.5.0-php-5.3-x86_64-linux/chilkat_9_5_0.php");
$ckb = new CkByteData();
$pri = new CkPrivateKey();
$pub = new CkPublicKey();
$cer = new CkCert();
$pub->DebugLogFilePath="chilkat.log";
echo "encode=".$ckb->encode("base64",file_get_contents("../upload_ficheros/44448.key"));
echo "privada=".$pri->LoadPkcs8EncryptedFile("../upload_ficheros/44448.key","12345678a")."\n";
echo "cer=".$cer->LoadFromFile("../upload_ficheros/44447.cer")."\n";
echo "isuer=".$cer->FindIssuer()."\n";
echo "VerifySignature=".$cer->VerifySignature()."\n";
echo "ValidFrom=".$cer->ValidFrom."\n";
##echo "ExportCertXml=".$cer->ExportCertXml()."\n";
echo "HasPrivateKey=".$cer->HasPrivateKey()."\n";
##echo "publica=".$pub->LoadOpenSslDerFile("../upload_ficheros/44448.key","12345678a")."\n";
echo "error=".$pub->LastErrorText();
##echo "resultado=".$obj->LoadPkcs8EncryptedFile("../upload_ficheros/44447.cer");
##$obj->VerboseLogging=1;
echo "LoadPkcs8Encrypted=".$pri->LoadPkcs8Encrypted(file_get_contents("../upload_ficheros/44448.key"),"12345678a");


?>

