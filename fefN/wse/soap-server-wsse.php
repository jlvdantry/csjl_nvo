<?php  
/** 
 * soap-server-wsse.php 
 * 
 * Copyright (c) 2007, Robert Richards <rrichards@ctindustries.net>. 
 * All rights reserved. 
 * 
 * Redistribution and use in source and binary forms, with or without 
 * modification, are permitted provided that the following conditions 
 * are met: 
 * 
 *   * Redistributions of source code must retain the above copyright 
 *     notice, this list of conditions and the following disclaimer. 
 * 
 *   * Redistributions in binary form must reproduce the above copyright 
 *     notice, this list of conditions and the following disclaimer in 
 *     the documentation and/or other materials provided with the 
 *     distribution. 
 * 
 *   * Neither the name of Robert Richards nor the names of his 
 *     contributors may be used to endorse or promote products derived 
 *     from this software without specific prior written permission. 
 * 
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS 
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT 
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS 
 * FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE 
 * COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, 
 * INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, 
 * BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; 
 * LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER 
 * CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT 
 * LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN 
 * ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE 
 * POSSIBILITY OF SUCH DAMAGE. 
 * 
 * @author     Robert Richards <rrichards@ctindustries.net> 
 * @copyright  2007 Robert Richards <rrichards@ctindustries.net> 
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License 
 * @version    1.0.0 
 */ 

require('xmlseclibs.php'); 

class WSSESoapServer { 
    const WSSENS = 'http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd'; 
    const WSSENS_2003 = 'http://schemas.xmlsoap.org/ws/2003/06/secext'; 
    const WSUNS = 'http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-utility-1.0.xsd'; 
    const WSSEPFX = 'wsse'; 
    const WSUPFX = 'wsu'; 
    private $soapNS, $soapPFX; 
    private $soapDoc = NULL; 
    private $envelope = NULL; 
    private $SOAPXPath = NULL; 
    private $secNode = NULL; 
    public $signAllHeaders = FALSE; 

    private function locateSecurityHeader($setActor=NULL) { 
        $this->debugea('entro en locateSecurityHeader');
        $wsNamespace = NULL; 
        if ($this->secNode == NULL) { 
            $headers = $this->SOAPXPath->query('//wssoap:Envelope/wssoap:Header'); 
            $this->debugea('headers');
            if ($header = $headers->item(0)) { 
                $this->debugea('headers iguales');
                $secnodes = $this->SOAPXPath->query('./*[local-name()="Security"]', $header); 
                $secnode = NULL; 
                foreach ($secnodes AS $node) { 
                    $this->debugea('foreach secnodes');
                    $nsURI = $node->namespaceURI; 
                    if (($nsURI == self::WSSENS) || ($nsURI == self::WSSENS_2003)) { 
                        $actor = $node->getAttributeNS($this->soapNS, 'actor'); 
                        if (empty($actor) || ($actor == $setActor)) { 
                            $this->debugea('encontro actor');
                            $secnode = $node; 
                            $wsNamespace = $nsURI; 
                            break; 
                        } 
                    } 
                } 
            } 
            $this->secNode = $secnode; 
        } 
        $this->debugea('locateSecurityHeader regreso'.$wsNamespace.' this->secNode=');
        return $wsNamespace; 
    } 

    public function __construct($doc) { 
        $this->soapDoc = $doc; 
        $this->envelope = $doc->documentElement; 
        $this->soapNS = $this->envelope->namespaceURI; 
        $this->soapPFX = $this->envelope->prefix; 
        $this->SOAPXPath = new DOMXPath($doc); 
        $this->SOAPXPath->registerNamespace('wssoap', $this->soapNS); 
        $this->SOAPXPath->registerNamespace('wswsu', WSSESoapServer::WSUNS); 
        $wsNamespace = $this->locateSecurityHeader(); 
        if (! empty($wsNamespace)) { 
            $this->SOAPXPath->registerNamespace('wswsse', $wsNamespace); 
        } 
    } 

    public function processSignature($refNode) { 
        $this->debugea('entro a signature');
        $objXMLSecDSig = new XMLSecurityDSig(); 
        $objXMLSecDSig->idKeys[] = 'wswsu:Id'; 
        $objXMLSecDSig->idNS['wswsu'] = WSSESoapServer::WSUNS; 
        $objXMLSecDSig->sigNode = $refNode; 

        /* Canonicalize the signed info */ 
        $objXMLSecDSig->canonicalizeSignedInfo(); 

        $retVal = $objXMLSecDSig->validateReference(); 

        if (! $retVal) { 
            throw new Exception("Validation Failed"); 
        } 

        $key = NULL; 
        $objKey = $objXMLSecDSig->locateKey(); 

        if ($objKey) { 
            if ($objKeyInfo = XMLSecEnc::staticLocateKeyInfo($objKey, $refNode)) { 
                /* Handle any additional key processing such as encrypted keys here */ 
            } 
        } 

        if (empty($objKey)) { 
            throw new Exception("Error loading key to handle Signature esta vacia"); 
        } 
        do { 
            if (empty($objKey->key)) { 
                $this->SOAPXPath->registerNamespace('xmlsecdsig', XMLSecurityDSig::XMLDSIGNS); 
                $query = "./xmlsecdsig:KeyInfo/wswsse:SecurityTokenReference/wswsse:Reference"; 
                $nodeset = $this->SOAPXPath->query($query, $refNode); 
                $this->debugea('paso SOAPXPath->query(');
                if ($encmeth = $nodeset->item(0)) { 
                    $this->debugea('entro primer if');
                    if ($uri = $encmeth->getAttribute("URI")) { 
                        $this->debugea('entro segundo if');
                        $arUrl = parse_url($uri); 
                        if (empty($arUrl['path']) && ($identifier = $arUrl['fragment'])) { 
                            $this->debugea('entro tercer if');
                            $query = '//wswsse:BinarySecurityToken[@wswsu:Id="'.$identifier.'"]'; 
                            $nodeset = $this->SOAPXPath->query($query); 
                            if ($encmeth = $nodeset->item(0)) { 
                                $x509cert = $encmeth->textContent; 
                                $x509cert = str_replace(array("\r", "\n"), "", $x509cert); 
                                $x509cert = "-----BEGIN CERTIFICATE-----\n".chunk_split($x509cert, 64, "\n")."-----END CERTIFICATE-----\n"; 
                                $this->debugea('va a ejecutar loadKey desde processSignature');
                                $objKey->loadKey($x509cert); 
                                break; 
                            } 
                        } 
                    } 
                } 
                throw new Exception("Error loading key to handle Signature"); 
            } 
        } while(0); 

        if (! $objXMLSecDSig->verify($objKey)) { 
            throw new Exception("Unable to validate Signature"); 
        } 

        return TRUE; 
    } 

private $passwordUsernameValidator = null; 
public function setPasswordValidator(IWsseUsernamePasswordValidator $validator) { 
 $this->passwordUsernameValidator = $validator;
}
 
public function processUsernameToken($node) {
 $Username = null;
 $Password = null;
 $PasswordType = null;
 $this->debugea('entro a usenametoken');
 
 foreach($node->childNodes as $Child)
 {
  switch($Child->localName) {
  case "Nonce":
   $Nonce = $Child->textContent;
   break;
  case "Created":
   $Created = $Child->textContent;
   break;
  case "Username":
   $Username = $Child->textContent;
   break;
  case "Password":
   $Password = $Child->textContent;
   if($Child->attributes && $Child->attributes->length > 0)
    $PasswordType = $Child->attributes->getNamedItem("Type")->textContent;
   break;
  } 
 } 
 $this->debugea('user='.$Username.' password'.$Password.' type='.$PasswordType.' Nonce='.$Nonce.' Created='.$Created);
 
 if($Username == null)
  throw new Exception("Username is missing");
 
 if($Password == null)
  throw new Exception("Password is missing");
 
 if($PasswordType != "http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-username-token-profile-1.0#PasswordText" && $Nonce=="" ) 
  throw new Exception("Nonce is missing"); 

 if($PasswordType != "http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-username-token-profile-1.0#PasswordText" && $Created=="" ) 
  throw new Exception("Created is missing"); 
 
 if($this->passwordUsernameValidator == null || !$this->passwordUsernameValidator->validate($Username, $Password, $Nonce, $Created , $PasswordType))
  throw new Exception("Invalid credentials!"); 
 
 return true;
}

    public function process() { 
        $this->debugea('entro proceso');
        if (empty($this->secNode)) { 
            $this->debugea('esta vacio secNode');
            return; 
        } 
        $node = $this->secNode->firstChild; 
        $this->debugea('no esta vacio secNode');
        while ($node) { 
            $nextNode = $node->nextSibling; 
            $this->debugea('node->localName='.$node->localName);
            switch ($node->localName) { 
                case "Signature": 
                    if ($this->processSignature($node)) { 
                        if ($node->parentNode) { 
                            $node->parentNode->removeChild($node); 
                        } 
                    } else { 
                        /* throw fault */ 
                        return FALSE; 
                    } 
                    break;
                case "UsernameToken": 
                    if ($this->processUsernameToken($node)) { 
                    if ($node->parentNode) { 
                       $node->parentNode->removeChild($node); 
                    } 
                    } else { 
                    /* throw fault */
                    return FALSE; 
                    } 
                    break;
            } 
            $node = $nextNode; 
        } 
        $this->debugea('paso el while');
        $this->secNode->parentNode->removeChild($this->secNode); 
        $this->secNode = NULL; 
        return TRUE; 
    } 
     
    public function saveXML() { 
        return $this->soapDoc->saveXML(); 
    } 

    public function save($file) { 
        return $this->soapDoc->save($file); 
    } 
    function debugea($wlstring)
    {
       $dt = date("Y-m-d H:i:s:u ");
       $dia = date("Ymd");
       //error_log("$dt ".htmlspecialchars($wlstring)." \n",3,"wservice_TC$dia.log");
       error_log("$dt $wlstring \n",3,"wservice_TC$dia.log");
    }
} 
