<?php
include('../phpseclib1.0.0/File/X509.php');

$x509 = new File_X509();
$cert = $x509->loadX509(file_get_contents('../upload_ficheros/44447.cer'));
echo "que paso\n";
print_r($cert);
?>
