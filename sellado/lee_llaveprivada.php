<?php
include('../phpseclib1.0.0/Crypt/RSA.php');

$rsa = new Crypt_RSA();

//$rsa->setPrivateKeyFormat(CRYPT_RSA_PRIVATE_FORMAT_PKCS8);
//$rsa->setPublicKeyFormat(CRYPT_RSA_PUBLIC_FORMAT_PKCS8);
$rsa->setPassword('12345678a');
echo "va a ejecutar loadkey\n";
echo "resultado=".$rsa->loadKey(base64_decode(file_get_contents('../upload_ficheros/44448.key')),CRYPT_RSA_PRIVATE_FORMAT_PKCS8)."\n";
echo "resultadoPK=".$rsa->setPrivateKey(file_get_contents('../upload_ficheros/44448.key'),CRYPT_RSA_PRIVATE_FORMAT_PKCS8)."\n";
echo "resultadoPK1=".$rsa->getPrivateKey(file_get_contents('../upload_ficheros/44448.key'),CRYPT_RSA_PRIVATE_FORMAT_PKCS8)."\n";

//extract($rsa->createKey());

//echo $privatekey . "\r\n\r\n";
//echo $publickey;
?>
