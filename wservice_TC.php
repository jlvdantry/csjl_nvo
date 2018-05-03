<?php
error_reporting(-1);
echo "que paso";
/**
date_default_timezone_set('America/Chicago');
$parametro1="";
$parametro2="";
*/
echo "antes de interface";
/**
* tratando de meter seguridada
*/
/**
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
*/
echo "paso interface";
/**
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
*/
require('wse/soap-server-wsse.php');
$Payload = file_get_contents('php://input');
debugea('payload:'.$Payload);
echo "paso payload";
if($Payload != null) {
 $soap = new DOMDocument();
 $soap->loadXML($Payload);
 debugea('cargo xml');
 $s = new WSSESoapServer($soap);
 echo "paso WSSE";
/**
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
*/
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
require_once("nusoap-0.9.5/nusoap.php");

//Define el namedspace
$ns="http://10.4.3.220/contra/csjl_nvo/csjl_nvo/wservice_TC.php";

//Genera una instancia del soap_server que es capaz de recibir y
// enviar mensajes SOAP
$server = new soap_server();
//Configura el servicio con el nombre 'Services' y el namedspace definido
$server->configureWSDL('Servicio para citas por internet y control de turnos presenciales ',$ns);
//Configura que el esquema del namedspace destino sera el definido
$server->wsdl->schemaTargetNamespace=$ns;

/**
* Definicion de los tipos complejos utilizados para el servicio.
*/
$server->wsdl->addComplexType(
	'respuesta_cita',
	'complexType',
	'struct',
	'all',
	'',
	array(
	'horadelacita'  => array('name' => 'horadelacita', 'type' => 'xsd:string'),
	'diadelacita'  => array('name' => 'diadelacita', 'type' => 'xsd:string'),
	'filas'  => array('name' => 'filas', 'type' => 'xsd:int'),
        'agrupacion'  => array('name' => 'agrupacion', 'type' => 'xsd:int')
	)
);

$server->wsdl->addComplexType(
	'respuesta_datocita',
	'complexType',
	'struct',
	'all',
	'',
	array(
	'dato'  => array('name' => 'dato', 'type' => 'xsd:string'),
	'valor'  => array('name' => 'valor', 'type' => 'xsd:string')
	)
);

$server->wsdl->addComplexType(
        'respuesta_agrupacion',
        'complexType',
        'struct',
        'all',
        '',
        array(
        'agrupacion'  => array('name' => 'agrupacion', 'type' => 'xsd:string'),
        'descripcion'  => array('name' => 'descripcion', 'type' => 'xsd:string')
        )
);

$server->wsdl->addComplexType(
        'varios_respuesta_agrupaciones',
        'complexType',
        'array',
        'all',
        'SOAP-ENC:Array',
        array(),
        array(array('ref'=>'SOAP-ENC:arrayType','wsdl:arrayType'=>'tns:respuesta_agrupacion[]')),
                'tns:respuesta_agrupacion'
);


$server->wsdl->addComplexType(
	'varios_respuesta_citas',
	'complexType',
	'array',
	'all',
	'SOAP-ENC:Array',
	array(),
        array(array('ref'=>'SOAP-ENC:arrayType','wsdl:arrayType'=>'tns:respuesta_cita[]')),
        'tns:respuesta_cita'
);

$server->wsdl->addComplexType(
	'varios_respuesta_datoscita',
	'complexType',
	'array',
	'all',
	'SOAP-ENC:Array',
	array(),
        array(array('ref'=>'SOAP-ENC:arrayType','wsdl:arrayType'=>'tns:respuesta_datocita[]')),
        'tns:respuesta_datocita'
);

$server->wsdl->addComplexType(
	'citasdisponibles',
	'complexType',
	'struct',
	'all',
	'',
	array(
        'agrupacion'  => array('name' => 'agrupacion', 'type' => 'xsd:int'),
	)
);

$server->wsdl->addComplexType(
	'citasdisponiblespordia',
	'complexType',
	'struct',
	'all',
	'',
	array(
        'agrupacion'  => array('name' => 'agrupacion', 'type' => 'xsd:int'),
        'fecha_cita'  => array('name' => 'fecha_cita', 'type' => 'xsd:string'),
	)
);

$server->wsdl->addComplexType(
        'agrupaciones',
        'complexType',
        'struct',
        'all',
        '',
	array(
        'agrupacion'  => array('name' => 'agrupacion', 'type' => 'xsd:int')
	    )
);


$server->wsdl->addComplexType(
	'apartarcita',
	'complexType',
	'struct',
	'all',
	'',
	array(
        'horadelacita' => array('name' => 'horadelacita', 'type' => 'xsd:string'),
        'diadelacita' => array('name' => 'horadelacita', 'type' => 'xsd:string'),
        'agrupacion' => array('name' => 'agrupacion', 'type' => 'xsd:integer'),
        'email' => array('name' => 'email', 'type' => 'xsd:string')
	)
);

$server->wsdl->addComplexType(
        'crearturno',
        'complexType',
        'struct',
        'all',
        '',
        array(
        'idgrupo' => array('name' => 'idgrupo', 'type' => 'xsd:integer'),
        'fila' => array('name' => 'fila', 'type' => 'xsd:integer'),
        'totaltramites' => array('name' => 'totaltramites', 'type' => 'xsd:integer'),
        'comentario' => array('name' => 'comentario', 'type' => 'xsd:string')
        )
);

$server->wsdl->addComplexType(
        'llamarturno',
        'complexType',
        'struct',
        'all',
        '',
        array(
        'usuario' => array('name' => 'usuario', 'type' => 'xsd:string')
        )
);

$server->wsdl->addComplexType(
        'turnoatendido',
        'complexType',
        'struct',
        'all',
        '',
        array(
        'idturno' => array('name' => 'idturno', 'type' => 'xsd:integer')
        )
);

$server->wsdl->addComplexType(
        'datoscita',
        'complexType',
        'struct',
        'all',
        '',
        array(
        'idcita' => array('name' => 'idcita', 'type' => 'xsd:decimal'),
        )
);

$server->wsdl->addComplexType(
        'confirmapresencia',
        'complexType',
        'struct',
        'all',
        '',
        array(
        'idcita' => array('name' => 'idcita', 'type' => 'xsd:decimal'),
        )
);

$server->wsdl->addComplexType(
	'respuesta_citasdisponibles',
	'complexType',
	'struct',
	'all',
	'',
	array(
	 	'registros'              => array('name' => 'registros', 'type' => 'xsd:integer'),
     	        'totalcitas'             => array('name' => 'totalcitas', 'type' => 'xsd:integer'),
		'citas'                  => array('name' => 'citas', 'type'=>'tns:varios_respuesta_citas')
	)
);

$server->wsdl->addComplexType(
	'respuesta_citasdisponiblespordia',
	'complexType',
	'struct',
	'all',
	'',
	array(
	 	'registros'              => array('name' => 'registros', 'type' => 'xsd:integer'),
     	        'totalcitas'             => array('name' => 'totalcitas', 'type' => 'xsd:integer'),
		'citas'                  => array('name' => 'citas', 'type'=>'tns:varios_respuesta_citas')
	)
);

$server->wsdl->addComplexType(
        'respuesta_apartarcita',
        'complexType',
        'struct',
        'all',
        '',
        array(
                'idcita'              => array('name' => 'idcita', 'type'=>'xsd:integer'),
                'fila'                => array('name' => 'fila', 'type'=>'xsd:integer'),
                'estatus'                => array('name' => 'estatus', 'type'=>'xsd:integer')
        )
);

$server->wsdl->addComplexType(
        'respuesta_crearturno',
        'complexType',
        'struct',
        'all',
        '',
        array(
                'turno'              => array('name' => 'turno', 'type'=>'xsd:integer'),
                'turnogrupo'              => array('name' => 'turnogrupo', 'type'=>'xsd:integer'),
                'estatus'              => array('name' => 'estatus', 'type'=>'xsd:string')
        )
);

$server->wsdl->addComplexType(
        'respuesta_llamarturno',
        'complexType',
        'struct',
        'all',
        '',
        array(
                'turno'              => array('name' => 'idturno', 'type'=>'xsd:integer'),
                'turnogrupo'              => array('name' => 'idturno', 'type'=>'xsd:integer'),
                'agrupacion'              => array('name' => 'idturno', 'type'=>'xsd:string'),
                'idcita'              => array('name' => 'idturno', 'type'=>'xsd:integer'),
                'fila'              => array('name' => 'fila', 'type'=>'xsd:integer'),
                'estatus'              => array('name' => 'estatus', 'type'=>'xsd:string')
        )
);

$server->wsdl->addComplexType(
        'respuesta_turnoatendido',
        'complexType',
        'struct',
        'all',
        '',
        array(
                'estatus'             => array('name' => 'estatus', 'type'=>'xsd:string')
        )
);

$server->wsdl->addComplexType(
        'respuesta_confirmapresencia',
        'complexType',
        'struct',
        'all',
        '',
        array(
                'estatus'             => array('name' => 'estatus', 'type'=>'xsd:string')
        )
);

$server->wsdl->addComplexType(
        'respuesta_datoscita',
        'complexType',
        'struct',
        'all',
        '',
        array(
                'datoscita'              => array('name' => 'datoscita', 'type'=>'tns:varios_respuesta_datoscita')
        )
);

/**
/* Defincion de arreglos.
*/
/*
//Este tipo se llama Arreglo
$server->wsdl->addComplexType(
	'arreglo_pregunta',
	'complexType',
	'array',
	'',
	'SOAP-ENC:Array',
	array(),
	array(array('ref' => 'SOAP-ENC:arrayType', 'wsdl:arrayType' => 'tns:pregunta_pagos[]')),
	'tns:pregunta_pagos'
);

$server->wsdl->addComplexType(
	'arreglo_respuesta',
	'complexType',
	'array',
	'',
	'SOAP-ENC:Array',
	array(),
	array(array('ref' => 'SOAP-ENC:arrayType', 'wsdl:arrayType' => 'tns:respuesta_pagos[]')),
	'tns:respuesta_pagos'
);

*/
/**
* Registro de las funciones implementadas para este servicio
*/

//Registra las funciones implementados en este servicio
$server->register('citasdisponibles',
	array('pregunta'=>'tns:citasdisponibles'),
	array('respuesta'=>'tns:respuesta_citasdisponibles'),
	$ns,
	'rpc',
	false,
	false,
	'Se obtiene las citas disponibles para atende a usuarios');

//Registra las funciones implementados en este servicio
$server->register('agrupaciones',
        array('pregunta'=>'tns:agrupaciones'),
        array('respuesta'=>'tns:varios_respuesta_agrupaciones'),
        $ns,
        'rpc',
        false,
        false,
        'Se obtiene las agrupaciones disponibles para un usuarios');

$server->register('citasdisponiblespordia',
        array('pregunta'=>'tns:citasdisponiblespordia'),
        array('respuesta'=>'tns:respuesta_citasdisponiblespordia'),
        $ns,
        'rpc',
        false,
        false,
        'Se obtiene las citas disponibles de un dia para atende a usuarios');


$server->register('apartarcita',
	array('pregunta'=>'tns:apartarcita'),
	array('respuesta'=>'tns:respuesta_apartarcita'),
	$ns,
	'rpc',
	false,
	false,
	'Con este metodo se aparta una cita');	

$server->register('datoscita',
	array('pregunta'=>'tns:datoscita'),
	array('respuesta'=>'tns:respuesta_datoscita'),
	$ns,
	'rpc',
	false,
	false,
	'Con este metodo se obtiene los datos de una cita apartada por internet');	

$server->register('confirmapresencia',
        array('pregunta'=>'tns:confirmapresencia'),
        array('respuesta'=>'tns:respuesta_confirmapresencia'),
        $ns,
        'rpc',
        false,
        false,
        'Con este metodo se confirma la presencia de un usuario que hizo una cita por internet');

$server->register('crearturno',
        array('pregunta'=>'tns:crearturno'),
        array('respuesta'=>'tns:respuesta_crearturno'),
        $ns,
        'rpc',
        false,
        false,
        'Con este metodo se crea un turno presencial');

$server->register('llamarturno',
        array('pregunta'=>'tns:llamarturno'),
        array('respuesta'=>'tns:respuesta_llamarturno'),
        $ns,
        'rpc',
        false,
        false,
        'Con este metodo se llama a un turno presencial para hacer atendido');

$server->register('turnoatendido',
        array('pregunta'=>'tns:turnoatendido'),
        array('respuesta'=>'tns:respuesta_turnoatendido'),
        $ns,
        'rpc',
        false,
        false,
        'Con este metodo se confirma que un turno fue atendido');

	
function citasdisponibles($pregunta) {
        global $connection;
        global $parametro1;
        global $parametro2;
        debugea('entro en citasdisponibles connecion='.$connection." parametro1=".$parametro1." parametro2=".$parametro2." pregunta=".$pregunta["agrupacion"]);
        $sql="select * from agenda.v_horas_disponibles  where idgrupo=".$pregunta["agrupacion"];
        $sql_result = @pg_exec($connection,$sql);
        if (strlen(pg_last_error($connection))>0)
        { return new soap_fault('SERVER', '900', pg_last_error($connection)); }
        $num = pg_numrows($sql_result);
        $citas=array();
        $totaldecitas=0;
        for ($s=0; $s < $num ;$s++)
        {
           $Row = pg_fetch_array($sql_result, $s);
           $citas[$s]["diadelacita"]=$Row["fecha_cita"];
           $citas[$s]["horadelacita"]=$Row["hora"];
           $citas[$s]["filas"]=$Row["filas"];
           $citas[$s]["agrupacion"]=$Row["idgrupo"];
           $totaldecitas+=$citas[$s]["filas"];
        }
        $respuesta =array(
		"registros" =>$num,
		"totalcitas" =>  $totaldecitas,
		"citas" => $citas
		);
	return $respuesta;
}

function agrupaciones($pregunta) {
        global $connection;
        global $parametro1;
        global $parametro2;
        debugea('entro en agrupaciones connecion='.$connection." parametro1=".$parametro1." parametro2=".$parametro2." pregunta=".$pregunta["agrupacion"]);
        $sql="select * from agenda.grupos_filas  where usuario_admon='".$parametro1."' and idtipo=2";
        $sql_result = @pg_exec($connection,$sql);
        if (strlen(pg_last_error($connection))>0)
        { return new soap_fault('SERVER', '900', pg_last_error($connection)); }
        $num = pg_numrows($sql_result);
        $agrupaciones=array();
        $totaldeagrupaciones=0;
        for ($s=0; $s < $num ;$s++)
        {
           $Row = pg_fetch_array($sql_result, $s);
           $agrupaciones[$s]["agrupacion"]=$Row["idgrupo"];
           $agrupaciones[$s]["descripcion"]=$Row["descripcion"];
           $totaldeagrupaciones=$totaldeagrupaciones+1;
        }
        debugea('total de agrupaciones'. $totaldeagrupaciones);
        $respuesta =array(
                ##"registros" =>$num,
                ##"totalagrupaciones" =>  $totaldeagrupaciones,
                "agrupaciones" => $agrupaciones
                );
        ##return $respuesta;
        return $agrupaciones;
}

function citasdisponiblespordia($pregunta) {
        global $connection;
        global $parametro1;
        global $parametro2;
        debugea('entro en citasdisponibles connecion='.$connection." parametro1=".$parametro1." parametro2=".$parametro2." pregunta=".$pregunta["agrupacion"]);
        $sql="select * from agenda.v_horas_disponibles  where idgrupo=".$pregunta["agrupacion"]." and fecha_cita='".$pregunta["fecha_cita"]."'";
        $sql_result = @pg_exec($connection,$sql);
        if (strlen(pg_last_error($connection))>0)
        { return new soap_fault('SERVER', '900', pg_last_error($connection)); }
        $num = pg_numrows($sql_result);
        $citas=array();
        $totaldecitas=0;
        for ($s=0; $s < $num ;$s++)
        {
           $Row = pg_fetch_array($sql_result, $s);
           $citas[$s]["diadelacita"]=$Row["fecha_cita"];
           $citas[$s]["horadelacita"]=$Row["hora"];
           $citas[$s]["filas"]=$Row["filas"];
           $citas[$s]["agrupacion"]=$Row["idgrupo"];
           $totaldecitas+=$citas[$s]["filas"];
        }
        $respuesta =array(
		"registros" =>$num,
		"totalcitas" =>  $totaldecitas,
		"citas" => $citas
		);
	return $respuesta;
}

function apartarcita($pregunta) {
        global $connection;
        global $parametro1;
        global $parametro2;

        $sql="select count(*) as cuantos from agenda.citas where email='".$pregunta["email"]."'".
             "  and fecha='".$pregunta["diadelacita"]."'".
             "    and idgrupo=".$pregunta["agrupacion"];
        $sql_result = @pg_exec($connection,$sql);

        if (strlen(pg_last_error($connection))>0)
        { return new soap_fault('SERVER', '911', pg_last_error($connection)); }
        $row=pg_fetch_array($sql_result, 0);
        if ($row["cuantos"]>3) 
        { return new soap_fault('SERVER', '912', 'No puede apartar mas de 3 citas en el mismo dia'); }

        $sql="select count(*) as cuantos from agenda.citas where email='".$pregunta["email"]."'".
             "  and fecha>current_date".
             "  and idgrupo=".$pregunta["agrupacion"];

        $sql_result = @pg_exec($connection,$sql);
        if (strlen(pg_last_error($connection))>0)
        { return new soap_fault('SERVER', '913', pg_last_error($connection)); }
        $row=pg_fetch_array($sql_result, 0);
        if ($row["cuantos"]>5)
        { return new soap_fault('SERVER', '914', 'No puede apartar mas de 5 citas en diferentes dias'); }

	$sql="update agenda.citas set idestatus=4 , email='".$pregunta["email"]."'".
             "	where fecha='".$pregunta["diadelacita"]."' and hora='".$pregunta["horadelacita"]."' and idestatus=3".
	     "    and fila=(select min(fila) from agenda.citas where fecha='".$pregunta["diadelacita"]."' and hora='".$pregunta["horadelacita"]."' and idestatus=3)".
	     "    and idgrupo=".$pregunta["agrupacion"].
             "  returning idcita,fila ";
        $sql_result = @pg_exec($connection,$sql);
        if (strlen(pg_last_error($connection))>0)
        { return new soap_fault('SERVER', '910', pg_last_error($connection)); }
        $row=pg_fetch_array($sql_result, 0);
        $respuesta =array(
		"idcita" => $row["idcita"],
                "fila"   => $row["fila"]
		);
        debugea('entro en apartarcita'); 
	return $respuesta;
}

function confirmapresencia($pregunta) {
        global $connection;
        global $parametro1;
        global $parametro2;
        $sql="select *,(select descripcion from agenda.citas_estatus where idestatus=ci.idestatus) estatusdes ".
             //" ,current_date as cuda from agenda.citas as ci where folioconsecutivo=".$pregunta["idcita"];
             " ,current_date as cuda from agenda.citas as ci where idcita=".$pregunta["idcita"];
        $sql_result = @pg_exec($connection,$sql);
        if (strlen(pg_last_error($connection))>0)
        { return new soap_fault('SERVER', '917', pg_last_error($connection)); }
        $num = pg_numrows($sql_result);
        if ($num==0)
        { return new soap_fault('SERVER', '918', 'No existe el numero de cita '.$pregunta["idcita"]); }
        $row=pg_fetch_array($sql_result, 0);
        if ($row["idestatus"]!=4)
        { return new soap_fault('SERVER', '919', 'No se puede confirma presencia ya que la cita tiene estatus de '.$row["estatusdes"]); }
        if ($row["cuda"]!=$row["fecha"])
        { return new soap_fault('SERVER', '920', 'La fecha '.$row["fecha"].' de la cita '.$pregunta["idcita"].' no corresponda a la de hoy '); }

        $sql="update agenda.citas set idestatus=5 ".
             //"  where folioconsecutivo=".$pregunta["idcita"];
             "  where idcita=".$pregunta["idcita"];
        $sql_result = @pg_exec($connection,$sql);
        if (strlen(pg_last_error($connection))>0)
        { return new soap_fault('SERVER', '921', pg_last_error($connection)); }
        $row=pg_fetch_array($sql_result, 0);
        $respuesta =array(
                "estatus"   => "ok"
                );
        return $respuesta;
}

function crearturno($pregunta) {
        global $connection;
        global $parametro1;
        global $parametro2;
        $sql="select * ".
             "  from agenda.grupos_filas  where idgrupo=".$pregunta["idgrupo"];
        $sql_result = @pg_exec($connection,$sql);
        if (strlen(pg_last_error($connection))>0)
        { return new soap_fault('SERVER', '930', pg_last_error($connection)); }
        $num = pg_numrows($sql_result);
        if ($num==0)
        { return new soap_fault('SERVER', '931', 'La agrupacion '.$pregunta["idgrupo"].' no existe '); }
        $sql="select * ".
             "  from agenda.cat_filas  where idgrupo=".$pregunta["idgrupo"]." and fila=".$pregunta["fila"];
        $sql_result = @pg_exec($connection,$sql);
        if (strlen(pg_last_error($connection))>0)
        { return new soap_fault('SERVER', '932', pg_last_error($connection)); }
        $num = pg_numrows($sql_result);
        if ($num==0)
        { return new soap_fault('SERVER', '933', 'La fila '.$pregunta["fila"].' en la agrupacion '.$pregunta["idgrupo"].' no existe '); }
        if ($pregunta["totaltramites"]=="") { $pregunta["totaltramites"]=1; }
        $sql = "insert into agenda.citas (idgrupo,fila,totaldetramites,comentarios,idestatus,fecha) values ".
               "(".$pregunta["idgrupo"].",".$pregunta["fila"].",".$pregunta["totaltramites"].",'".$pregunta["comentario"]."',6,current_date)";
        $sql_result = @pg_exec($connection,$sql);
        if (strlen(pg_last_error($connection))>0)
        { return new soap_fault('SERVER', '934', pg_last_error($connection)); }
        $aff= pg_affected_rows($sql_result);
        if ($aff==0)
        { return new soap_fault('SERVER', '935', 'No se pudo crear el turno'); }      
        $sql = " select lastval() as secuencia";
        $sql_result = @pg_exec($connection,$sql);
        if (strlen(pg_last_error($connection))>0)
        { return new soap_fault('SERVER', '936', pg_last_error($connection)); }
        $row=pg_fetch_array($sql_result, 0);  
        $sec=$row["secuencia"];
        $sql="select * from agenda.citas  where idcita=".$sec; 
        $sql_result = @pg_exec($connection,$sql);
        if (strlen(pg_last_error($connection))>0)
        { return new soap_fault('SERVER', '937', pg_last_error($connection)); }
        $row=pg_fetch_array($sql_result, 0);
        $respuesta =array(
                "turno"   => $row["turno"],
                "turnogrupo"   => $row["turnogrupo"],
                "estatus"   => "ok"
                );
        return $respuesta;
}

function llamarturno($pregunta) {
        global $connection;
        global $parametro1;
        global $parametro2;
        $sql="select  agenda.llamaturno('".$pregunta["usuario"]."') as llama";
        $sql_result = @pg_exec($connection,$sql);
        if (strlen(pg_last_error($connection))>0)
        { return new soap_fault('SERVER', '940', pg_last_error($connection)); }
        $num = pg_numrows($sql_result);
        $Row = pg_fetch_array($sql_result, 0);
         if ($Row["llama"]==0)
        { return new soap_fault('SERVER', '941', 'No se encontraron turnos a ser llamados'); }
              $sql="select turno,turnogrupo,idcita,folioconsecutivo   ".
                   " ,(select descripcion from agenda.grupos_filas as gf where gf.idgrupo=ci.idgrupo) as desgrupo".
                   " ,ci.idgrupo,fila ".
                   " from agenda.citas ci where fecha=current_date and idestatus=7 ".
                   " and usuario_modifico='".$pregunta["usuario"]."' order by fecha_modifico desc limit 1";
              $sql_result = @pg_exec($connection,$sql);
              if (strlen(pg_last_error($connection))>0)
              { return new soap_fault('SERVER', '942', 'Error al buscar el turno llamado'); }
              $num = pg_numrows($sql_result);
              if ($num==0)
              { return new soap_fault('SERVER', '943', 'No se encontro el turno llamado'); }  
          
              $Row = pg_fetch_array($sql_result, 0);
              if ($Row["turno"]!="" && $Row["turno"]!="0" )
              {
                 $respuesta =array(
                    "turno"   => $Row["turno"],
                    "turnogrupo"   => $Row["turnogrupo"],
                    "agrupacion"   => $Row["desgrupo"],
                    "idcita"   => 0,
                    "fila"   => $Row["fila"],
                    "estatus"   => "ok"
                );
                 return $respuesta;
              }

              if ($Row["idcita"]!="" && $Row["idcita"]!="0" )
              {
                 $respuesta =array(
                    "turno"   => 0,
                    "turnogrupo"   => 0,
                    "agrupacion"   => $Row["desgrupo"],
                    "idcita"   => $row["idcita"],
                    "fila"   => $Row["fila"],
                    "estatus"   => "ok"
                );
                 return $respuesta;
              }
}

function datoscita($pregunta) {
        global $connection;
        global $parametro1;
        global $parametro2;
        include("menudata.php");
        $sql="select * ,(select idmenucita from agenda.tramites_tipo as tt where tt.id_tipotramite=t.id_tipotramite) as idmenuaten ".
             " from agenda.tramites t where folioconsecutivo=".$pregunta["idcita"];
        $sql_result = @pg_exec($connection,$sql);
        if (strlen(pg_last_error($connection))>0)
        { return new soap_fault('SERVER', '910', pg_last_error($connection)); }
        $num = pg_numrows($sql_result);
        if ($num==0)
        { return new soap_fault('SERVER', '911', 'No se encuentro la cita'); }
        $row = pg_fetch_array($sql_result, 0);
        $v = new menudata();
        $v->idmenu=$row["idmenuaten"];
        $v->filtro="folioconsecutivo=".$pregunta["idcita"];
        $v->connection=$connection;
        $v->damemetadata();
        $sql=$v->camposm["fuente"];
        //$sql=" select * ,(select attname from campos where campos.attnum=mc.attnum and mc.nspname=campos.nspname and mc.tabla=campos.tabla) as attname ".
        //     " from menus_campos as mc where idmenu=".$row["idmenuaten"];
        $sql_result = @pg_exec($connection,$sql);
        if (strlen(pg_last_error($connection))>0)
        { return new soap_fault('SERVER', '920', pg_last_error($connection)); }
        $row = pg_fetch_array($sql_result, 0);
        $num1 = count($v->camposmc);
        if ($num1==0)
        { return new soap_fault('SERVER', '921', 'No se encuentro la definicion de campos '.$row["idmenuaten"].' para la cita '.$pregunta["idcita"]); }
        $datoscita=array();
        //for ($s=0; $s < $num1 ;$s++)
        //{
        $s=0;
        foreach ($v->camposmc as $key => $val) {
           $s=$s+1;
           $Row1 = $val;
           if ($Row1["descripcion"]!="")
           {
              $datoscita[$s]["dato"]=$Row1["descripcion"];
              $datoscita[$s]["valor"]=$row[$key];
              ##$datoscita[$s]["valor"]=$key;
           }
           ##$datoscita[$s]["valor"]="fijo";
        }
        $respuesta =array(
                "datoscita" => $datoscita
                );
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
   require("conneccion.php");
   $server->service($s->saveXML()); }
else { $server->service($HTTP_RAW_POST_DATA); }

function debugea($wlstring)
{
    $dt = date("Y-m-d H:i:s:u ");
    $dia = date("Ymd");
    error_log("$dt $wlstring \n",3,"wservice_TC$dia.log");
}

?>
