<?php
ini_set('display_errors','On');
error_reporting(E_ALL);
date_default_timezone_set('America/Chicago');
require('wse/soap-wsse.php');

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
    function debugea($wlstring)
    {
         $dt = date("Y-m-d H:i:s:u ");
         $dia = date("Ymd");
         error_log("$dt $wlstring \n",3,"wservice_TC$dia.log");
         chmod("wservice_TC$dia.log", 0677);
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

