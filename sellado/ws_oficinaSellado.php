<?php
//ini_set('display_errors','On');
//error_reporting(E_ALL);

require('XSLTProc.php');
date_default_timezone_set('America/Chicago');
$parametro1="";
$parametro2="";
/**
* tratando de meter seguridada
*/
interface IWsseUsernamePasswordValidator {
 function validate($username, $password, $nonce, $created, $type);
}
class AuthenticatedUserInformation implements IWsseUsernamePasswordValidator { 
 public function validate($username, $password, $nonce, $created, $type) { 
 if(AuthenticateUser($username, $password, $nonce, $created, $type) !== false) 
 {
 debugea('usuario autentidado');
 $this->User = $Auth;
 return true;
 } 
 
 return false;
 }
}
function AuthenticateUser($username, $password, $nonce, $create, $type)
{
 global $parametro1;
 global $parametro2;
 debugea('entro en AuthenticateUser usuario:'.$username.' password:'.$password.' type=');
 if($type == "http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-username-token-profile-1.0#PasswordText")
 {
      if ($username!="jlv" || $password!="dantry") { 
          return false; }
 }
 if($type == "http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-username-token-profile-1.0#PasswordDigest")
 {
      debugea('password='.$password.' base64_encode='.base64_encode(sha1(base64_decode($nonce).$create.'dantry',true)));
      if ($password!=base64_encode(sha1(base64_decode($nonce).$create.'dantry',true))) {
          return false; }
 }
                                                session_register("parametro1");
                                                session_register("parametro2");
                                                session_register("servidor");
      session_register("bada");
      $parametro1='jlv';
      $parametro2='jlv';
      return true;
}
require('../wse/soap-server-wsse.php');
$Payload = file_get_contents('php://input');
debugea('payload:'.$Payload);
if($Payload != null) {
 $soap = new DOMDocument();
 $soap->loadXML($Payload);
 debugea('cargo xml');
$s = new WSSESoapServer($soap);
try {
   $x = new AuthenticatedUserInformation();
  $s->setPasswordValidator($x);
  if ($s->process()) { 
  }
  else { errorusuario(); exit; }
 } catch (Exception $e) {
  header("Content-Type: text/xml");
  header("Status: 200");
  die("<SOAP-ENV:Envelope xmlns:SOAP-ENV=\"http://schemas.xmlsoap.org/soap/envelope/\">
  <SOAP-ENV:Body>
  <SOAP-ENV:Fault>
  <faultcode>500</faultcode>
  <faultstring>".($e->getMessage())."</faultstring>
  </SOAP-ENV:Fault>
  </SOAP-ENV:Body>
  </SOAP-ENV:Envelope>");
 }
}
function errorusuario()
{
header("Content-Type: text/xml");
header("Status: 200");
die("<SOAP-ENV:Envelope xmlns:SOAP-ENV=\"http://schemas.xmlsoap.org/soap/envelope/\">
<SOAP-ENV:Body>
<SOAP-ENV:Fault>
<faultcode>500</faultcode>
<faultstring>Invalid authentication.</faultstring>
</SOAP-ENV:Fault>
</SOAP-ENV:Body>
</SOAP-ENV:Envelope>");
}


//Define la libreria usada.
require_once("../nusoap-0.9.5/nusoap.php");
//require_once("LC_pagos_objeto.inc.php");

//Define el namedspace
##$ns="http://187.141.34.31/dgjyel/ws_oficinaSellado.php";
$ns="http://10.250.103.116/htdocs/contra/csjl_nvo/csjl_nvo/sellado/ws_oficinaSellado.php";
##$ep="http://187.141.34.31/dgjyel/ws_oficinaSellado.php";
$ep="http://10.250.103.116/htdocs/contra/csjl_nvo/csjl_nvo/sellado/ws_oficinaSellado.php";

//Genera una instancia del soap_server que es capaz de recibir y
// enviar mensajes SOAP
$server = new soap_server();
//Configura el servicio con el nombre 'Services' y el namedspace definido
$server->configureWSDL('Oficina de sellado',$ns,$ep);
//Configura que el esquema del namedspace destino sera el definido
$server->wsdl->schemaTargetNamespace=$ns;

/**
* Definicion de los tipos complejos utilizados para el servicio.
*/
$server->wsdl->addComplexType(
	'respuesta_sellado',
	'complexType',
	'struct',
	'all',
	'',
	array(
        'nocertificado' => array('name' => 'nocertificado', 'type' => 'xsd:string'),
        'certificado' => array('name' => 'certificado', 'type' => 'xsd:string'),
        'sello' => array('name' => 'sello', 'type' => 'xsd:string'),
        'ok' => array('name' => 'ok', 'type' => 'xsd:string')
	)
);

$server->wsdl->addComplexType(
	'respuesta_checarsellado',
	'complexType',
	'struct',
	'all',
	'',
	array(
	'valido'  => array('name' => 'valido', 'type' => 'xsd:bool'),
	)
);

$server->wsdl->addComplexType(
        'respuesta_validafiel',
        'complexType',
        'struct',
        'all',
        '',
        array(
        'valido'  => array('name' => 'valido', 'type' => 'xsd:bool'),
        )
);

$server->wsdl->addComplexType(
        'errores',
        'complexType',
        'array',
        'all',
        'SOAP-ENC:Array',
        array(),
        array(array('ref'=>'SOAP-ENC:arrayType','wsdl:arrayType'=>'xsd:string')),
        'tns:errores'
);

$server->wsdl->addComplexType(
	'respuesta_validaxml',
	'complexType',
	'struct',
	'all',
	'',
	array(
	'valido'  => array('name' => 'valido', 'type' => 'xsd:bool'),
	'errores'  => array('name' => 'errores', 'type' => 'tns:errores'),
	)
);

$server->wsdl->addComplexType(
	'respuesta_generacadena',
	'complexType',
	'struct',
	'all',
	'',
	array(
	'cadena'  => array('name' => 'cadena', 'type' => 'xsd:string'),
	)
);


$server->wsdl->addComplexType(
	'sellado',
	'complexType',
	'struct',
	'all',
	'',
	array(
        'cadena'  => array('name' => 'cadena', 'type' => 'xsd:string'),
        'publica'       => array('name' => 'publica'      , 'type' => 'xsd:string'),
        'privada'       => array('name' => 'privada'      , 'type' => 'xsd:string'),
        'password'      => array('name' => 'password'     , 'type' => 'xsd:string')
	)
);

$server->wsdl->addComplexType(
	'generacadena',
	'complexType',
	'struct',
	'all',
	'',
	array(
        'xml'  => array('name' => 'xml', 'type' => 'xsd:string'),
	)
);

$server->wsdl->addComplexType(
	'validaxml',
	'complexType',
	'struct',
	'all',
	'',
	array(
        'xml'  => array('name' => 'xml', 'type' => 'xsd:string'),
	)
);

$server->wsdl->addComplexType(
	'checarsellado',
	'complexType',
	'struct',
	'all',
	'',
	array(
        'xml'       => array('name' => 'xml'      , 'type' => 'xsd:string'),
        'esarchivo' => array('name' => 'esarchivo', 'type' => 'xsd:string'),
	)
);

$server->wsdl->addComplexType(
        'validafiel',
        'complexType',
        'struct',
        'all',
        '',
        array(
        'publica'       => array('name' => 'publica'      , 'type' => 'xsd:string'),
        'privada'       => array('name' => 'privada'      , 'type' => 'xsd:string'),
        'password'      => array('name' => 'password'     , 'type' => 'xsd:string'),
        )
);



//Registra las funciones implementados en este servicio
$server->register('sellado',
	array('pregunta'=>'tns:sellado'),
	array('respuesta'=>'tns:respuesta_sellado'),
	$ns,
	'rpc',
	false,
	false,
	'Se genera un sello sobre la cadena que se recibe');

$server->register('generacadena',
        array('pregunta'=>'tns:generacadena'),
        array('respuesta'=>'tns:respuesta_generacadena'),
        $ns,
        'rpc',
        false,
        false,
        'Genera la cadena original de un documento electronico');

$server->register('validaxml',
        array('pregunta'=>'tns:validaxml'),
        array('respuesta'=>'tns:respuesta_validaxml'),
        $ns,
        'rpc',
        false,
        false,
        'Valida el formato de un xml de acuerdo a su schema');

$server->register('checarsellado',
	array('pregunta'=>'tns:checarsellado'),
	array('respuesta'=>'tns:respuesta_checarsellado'),
	$ns,
	'rpc',
	false,
	false,
	'Con este metodo se verifica sin un sello es valido');	

$server->register('validafiel',
        array('pregunta'=>'tns:validafiel'),
        array('respuesta'=>'tns:respuesta_validafiel'),
        $ns,
        'rpc',
        false,
        false,
        'Con este metodo se valida que la llave privada cheque con su usuario y llave publica');


	
function generacadena($pregunta)
{
        debugea('entro en generacadena='.$pregunta["xml"]);
        $x = new XSLTProc();
        debugea('paso new');
        $cadena=$x->transformToXML($pregunta["xml"],"vuat_XSL_cadena.xsl");
        debugea('paso transform');
        $respuesta =array(
                "cadena" => trim($cadena)
                );
        return $respuesta;
}

function validaxml($pregunta)
{
        debugea('entro en validaxml='.$pregunta["xml"]);
        $x = new XSLTProc();
        debugea('paso new');
        $valido=$x->validaxml($pregunta["xml"],"certi_xsd.xml");
        debugea('paso validaxml');
        $respuesta =array(
                "cadena" => $cadena
                );
        return $respuesta;
}

function sellado($pregunta) {
        global $connection;
        global $parametro1;
        global $parametro2;
        $va=validafiel($pregunta);
        if ($va["valido"]!="1")
        {
            $respuesta =array(
                "nocertificado" => "1",
                "certificado"   => $certificado,
                "sello"         => $sello,
                "ok"         => "0"
                );
            return $respuesta;
        }
        debugea('entro en sellado cadena_'.$pregunta["cadena"]."_");
        $ruta = "" ;
        debugea('llave privada='.$llavepr);
        $pkeyid = leeprivada($pregunta["privada"],$pregunta["password"]);
        debugea('paso openssl_get_privatekeyid='.$pkeyid);
        openssl_sign($pregunta["cadena"], $crypttext, $pkeyid, OPENSSL_ALGO_SHA1);
        debugea('pasa sign dato encriptado='.$crypttext);
        openssl_free_key($pkeyid);
        debugea('pasa free key');
        $sello = base64_encode($crypttext);      // lo codifica en formato base64
        $datos = leepublica($pregunta["publica"]);
        debugea('paso lectura de certificado='.$datos);
        $certificado = ""; $carga=false;
        $certificado=getRawThumbprint($datos);
        $pubkeyid = openssl_get_publickey($datos);
        debugea("pubkeyid=".$pubkeyid);
        $respuesta =array(
                "nocertificado" => "1",
                "certificado"   => $certificado,
                "sello"         => $sello,
                "ok"            => "1"
                );
        debugea('va a regresa sello='.$respuesta["sello"]);
        return $respuesta;
}
    function getRawThumbprint($cert) {
        $arCert = explode("\n", $cert);
        $data = '';
        $inData = FALSE;
        foreach ($arCert AS $curData) {
            if (! $inData) {
                if (strncmp($curData, '-----BEGIN CERTIFICATE', 22) == 0) {
                    $inData = TRUE;
                }
            } else {
                if (strncmp($curData, '-----END CERTIFICATE', 20) == 0) {
                    $inData = FALSE;
                    break;
                }
                $data .= trim($curData);
            }
        }
        if (! empty($data)) {
        //    return strtolower(sha1(base64_decode($data)));
              return $data;
        }
        return NULL;
    }

function leepublica($xp) {
        if (file_exists($xp)) {
           $llavepu=file_get_contents($xp); }
        else { $llavepu=base64_decode($xp); }
        return "-----BEGIN CERTIFICATE-----\n" . chunk_split(base64_encode($llavepu), 64, "\n") . "-----END CERTIFICATE-----";
}

function leeprivada($xp,$pwd) {
        if (file_exists($xp)) {
           $llavepr=file_get_contents($xp); }
        else { $llavepr=base64_decode($xp); }
        $pri="-----BEGIN ENCRYPTED PRIVATE KEY-----\n" . chunk_split(base64_encode($llavepr), 64, "\n") . "-----END ENCRYPTED PRIVATE KEY-----";
        debugea('paso la lectura de la llave privada='.base64_encode($llavepr));
        return openssl_pkey_get_private($pri,$pwd);
}

function validafiel($pregunta) {
        debugea('entro a validafiel'.$pregunta["privada"]." publica=".$pregunta["publica"]);
        $pkeyid=leeprivada($pregunta["privada"],$pregunta["password"]);
        $pub=leepublica($pregunta["publica"]);
        debugea('leyo la llave privada'.$pkeyid);
        $ok=openssl_x509_check_private_key($pub,$pkeyid);
        debugea('resultado del chequeo'.$ok);
        $respuesta =array(
                "valido" => $ok
                );
        debugea('entro en checarsello');
        return $respuesta;
}

function checarsellado($pregunta) {
        debugea('entro a checarsellado');
        global $connection;
        global $parametro1;
        global $parametro2;
        $ruta = "" ;
        if ($pregunta["esarchivo"]=="1") {
            debugea('entro a checarsellado va a leer');
            $cer=file_get_contents($pregunta["xml"]); 
            debugea('entro a checarsellado despues de leer leido='.$cer);
            $dom = new DomDocument();
            debugea('entro a checarsellado despues de new DomDocument');
            $dom->loadXML($cer);
            debugea('entro a checarsellado despues de loadXML');
            $tag1 = $dom->getElementsByTagName("AvisoDeTestamento")->item(0);
            debugea('entro a checarsellado despues de getElementsByTagName elementos='.$tag1->attributes->length);
            $x509cert = $tag1->attributes->getNamedItem("certificado")->nodeValue;
            $cadena   = $tag1->attributes->getNamedItem("cadenaOriginal")->nodeValue;
            $sello    = $tag1->attributes->getNamedItem("sello")->nodeValue;
            debugea('certificado obtenido='.$x509cert." cadena_".$cadena."_ sello=".$sello);
            $llavepr="-----BEGIN CERTIFICATE-----\n".chunk_split($x509cert, 64, "\n")."-----END CERTIFICATE-----\n";
        }
        else {
            $cer=$pregunta["xml"];
        }
        $pubkeyid = openssl_get_publickey($llavepr);
        debugea('pubkeyid='.$pubkeyid." cadena=_".$cadena."_ sello=".$sello);
        $ok = openssl_verify($cadena, base64_decode($sello), $pubkeyid);
        if ($ok == 1) {
           debugea('good');
        } elseif ($ok == 0) {
        debugea('bad');
        } else {
        debugea('ugly, error checking signature');
        }
        openssl_free_key($pubkeyid);
        $respuesta =array(
		"valido" => $ok
		);
        debugea('entro en checarsello'); 
	return $respuesta;
}

/**
* Activacion del servicio
* La apariencia en el navegador de este servicio
* para consulta de funciones se proporciona mediante nuSOAP
*/
//debugea('row='.$HTTP_RAW_POST_DATA);
//debugea('row='.$HTTP_RAW_POST_DATA);
//Activacion del servicio
if($Payload != null)
{ 
   debugea('row='.$s->saveXML()); 
   ##require("conneccion.php");
   debugea("paso connecion");
   $server->service($s->saveXML()); }
else { $server->service($HTTP_RAW_POST_DATA); }

function debugea($wlstring)
{
    $dt = date("Y-m-d H:i:s:u ");
    $dia = date("Ymd");
    error_log("$dt $wlstring \n",3,"ws_oficinaSellado$dia.log");
}

?>
