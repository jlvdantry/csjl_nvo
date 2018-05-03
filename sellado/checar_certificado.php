<?php 

$fp = fopen("/var/www/htdocs/contra/csjl_nvo/csjl_nvo/sellado/certjlv.cer", "r"); 
$cert = fread($fp, 8192); 
fclose($fp); 

echo "Read<br>"; 
echo openssl_x509_read($cert); 
echo "<br>"; 
echo "*********************"; 
echo "<br>"; 
echo "Parse<br>"; 
print_r(openssl_x509_parse($cert)); 
/* 
// or 
print_r(openssl_x509_parse( openssl_x509_read($cert) ) ); 
*/ 

?> 
