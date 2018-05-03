<?php
ini_set('display_errors','On');
error_reporting(E_ALL);
date_default_timezone_set('America/Chicago');
require('../wse/soap-wsse.php');

define('PRIVATE_KEY', 'privkey.pem');
define('CERT_FILE', 'certificado.pem');

class mySoap extends SoapClient {

    private $_username;
    private $_password;
    private $_digest;
    
    function addUserToken($username, $password, $digest = false) {
        $this->_username = $username;
        $this->_password = $password;
        $this->_digest = $digest;
    }
    
    function __doRequest($request, $location, $saction, $version, $one_way = 0) {
        $doc = new DOMDocument('1.0');
        $doc->loadXML($request);
        
        $objWSSE = new WSSESoap($doc);
        $objWSSE->debugea('cliente paso new WSSESoap');
        
        /* Sign all headers to include signing the WS-Addressing headers */
        $objWSSE->signAllHeaders = TRUE;

        $objWSSE->addTimestamp();
        $objWSSE->debugea('cliente paso el addTimestamp');
        $objWSSE->addUserToken($this->_username, $this->_password, $this->_digest);
        $objWSSE->debugea('cliente paso el addUserToken');

        /* create new XMLSec Key using RSA SHA-1 and type is private key */
        //$objKey = new XMLSecurityKey(XMLSecurityKey::RSA_SHA1, array('type'=>'private'));
        //$objKey->passphrase='jlvdantry';

        /* load the private key from file - last arg is bool if key in file (TRUE) or is string (FALSE) */
        //$objKey->loadKey(PRIVATE_KEY, TRUE);

        /* Sign the message - also signs appropraite WS-Security items */
        //$objWSSE->signSoapDoc($objKey);
        //$objWSSE->debugea('cliente paso el signSoap');

        /* Add certificate (BinarySecurityToken) to the message and attach pointer to Signature */
        //$token = $objWSSE->addBinaryToken(file_get_contents(CERT_FILE));
        //$objWSSE->attachTokentoSig($token);
        
        $request = $objWSSE->saveXML();
        $objWSSE->debugea('cliente paso el save');
        return parent::__doRequest($request, $location, $saction, $version);
    }
}

##$wsdl = 'http://187.141.34.31/dgjyel/ws_oficinaSellado.php?wsdl';
$wsdl = 'http://10.250.103.116/htdocs/contra/csjl_nvo/csjl_nvo/sellado/ws_oficinaSellado.php?wsdl';

debugea('antes de new mysoap');
$sClient = new mySoap($wsdl,array('trace' => true, 'cache_wsdl' => WSDL_CACHE_MEMORY));
debugea('cliente paso mysoap');
$sClient->addUserToken('jlv', 'dantry', true);
debugea('cliente paso addUserToken');

try {
//    $out = $sClient->sellado(array("cadena" => 'Este dato es muy importante' ));
/*
    $out = $sClient->checarsellado(array("cadena" => 'Este dato es muy importante', 
                               "sello" => "fOXSDOBRq34rAjO8NLENJbj8qO2ifVpJMxf6/O/fTeLJjMRO3qOcRdQN+OzmPiKU+1dv6qlFq4SwjzLmck40/8r09AkCNEnlH0rs3lncSL3zgSes2fy3BP/gjR+1u4FN6/vuF5YYHwyOUo4T6C4YdZgwrLfp5SxMB1kPJR3zoG0=",
                               "certificado" => "-----BEGIN CERTIFICATE-----
MIICXjCCAcegAwIBAgIJAOGXMc83Wy3IMA0GCSqGSIb3DQEBBQUAMEgxCzAJBgNV
BAYTAk1YMQswCQYDVQQIDAJERjELMAkGA1UEBwwCREYxHzAdBgNVBAMMFnd3dy5m
aW5hbnphcy5kZi5nb2IubXgwHhcNMTMwOTEwMTQ0NjEwWhcNMTMxMDEwMTQ0NjEw
WjBIMQswCQYDVQQGEwJNWDELMAkGA1UECAwCREYxCzAJBgNVBAcMAkRGMR8wHQYD
VQQDDBZ3d3cuZmluYW56YXMuZGYuZ29iLm14MIGfMA0GCSqGSIb3DQEBAQUAA4GN
ADCBiQKBgQDEe1HdYYP8/j1tDFfR3xswl/ePPqLDPoHlXnWkBHfMSNS61NFtwQPJ
RAFsjKTy2qxMXavPUjgaTkuoTa4PxV0NIaTVdYPcIUZ9NYfd+X0bBQZ8g+DeO1WA
POnL1yOu37c6W4KpPZvItpNsUPKdXneVBbodU48ZN6vJjpx/etop5wIDAQABo1Aw
TjAdBgNVHQ4EFgQUvYsZcNrDRPCOEPYcSoqA5llQVZ8wHwYDVR0jBBgwFoAUvYsZ
cNrDRPCOEPYcSoqA5llQVZ8wDAYDVR0TBAUwAwEB/zANBgkqhkiG9w0BAQUFAAOB
gQAi0HC2DjVZ22DWt+EAI7+k03v0IOhvNPrGuY97QlDLw6MNa1SDSPxWMHgKcrJq
qJM15t/TbHFKsuFf2ygqaCkIJ9dtk91/rKjpAoC5ANNTJepj0fQuxApAihxszmEj
f2pzrazxPB7w/h8BWn8TDe6n/Y2C0hy+dttDkpWDeSoyQA==
-----END CERTIFICATE-----
",
                               "nocertificado" => "1"
                              ));
*/

    $out = $sClient->generacadena(array("xml" => "3.xml"));

    debugea('cliente paso selladselladoo');
    var_dump($out);
} catch (SoapFault $fault) {
//    debugea("regreso:".$sClient->__getLastResponse());
    var_dump($fault);
}
function debugea($wlstring)
{
    $dt = date("Y-m-d H:i:s:u ");
    $dia = date("Ymd");
    error_log("$dt $wlstring \n",3,"wservice_TC$dia.log");
}


