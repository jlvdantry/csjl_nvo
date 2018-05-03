<?php
error_reporting(-1);
require_once("nusoap-0.9.5/nusoap.php");
##require_once("nusoap-0.6.7/lib/nusoap.php");
##$client = new nusoap_client("http://forapi.dyndns-work.com:82/csjl_nvo20130705/ws_avisostesta.php?wsdl", true);
##$client = new nusoap_client("http://csjl.dnsalias.net/contra/csjl_nvo/csjl_nvo/ws_avisostesta.php?wsdl", true);
$client = new nusoap_client("http://10.4.3.220/contra/csjl_nvo/csjl_nvo/ws_avisostesta.php?wsdl", true);
##$client->use_curl=true;
##$err = $client->getError();
##if ($err) { // error if any
##echo ' Constructor error ' . $err . '
##'; }
$result = $client->call('insertaaviso', array('username' => 'jlv','password'=>'jlv','nombre'=>'jose luis', 'paterno'=>'vasquez', 'materno'=>'barbosa', 'escritura'=>'12322', 'notario'=>'101','folioaviso' => 'aviso', 'monto' => '61.50' )
                         ,'','','',true);
// fault if any
if ($client->fault)
{ echo ' Fault ';
  print_r($result);
  echo ' ';
} else
{ // Check for errors
  $err = $client->getError();
  if ($err)
  { // Display the error
    echo 'Error '.$err.' ';
  } else
  {
     // Display the result
    if($result!=false)
    {
        echo "Results".var_dump($result);
    }
  }
}
?>

