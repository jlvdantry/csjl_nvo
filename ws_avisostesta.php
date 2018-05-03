<?php
error_reporting(-1);
require_once("debugea.php"); 
debugea("Paso debugea ");
##require_once("nusoap-0.7.3/lib/nusoap.php"); 
require_once("nusoap-0.6.7/lib/nusoap.php"); 
##require_once("nusoap-0.9.5/nusoap.php"); 
require_once "constantes.php"; 
require_once("conneccionws.php"); 
$parametro1="";
$parametro2=""; 
$server = new soap_server();
##echo "paso new";
 
//$server->debug_flag = true;
//$ns="http://csjl.dnsalias.net/contra/csjl_nvo/csjl_nvo/ws_avisostesta.php";
$ns="ws_avisostesta.php";
$server->configureWSDL('ws_avisostesta.php', $ns);
//$server->wsdl->schemaTargetNamespace = $ns;
 
//first simple function
$server->register('insertaaviso',
			array('username' => 'xsd:string'
                              ,'password' => 'xsd:string'
                              ,'nombre' => 'xsd:string'
                              ,'paterno' => 'xsd:string'
                              ,'materno' => 'xsd:string'
                              ,'escritura' => 'xsd:string'
                              ,'notario' => 'xsd:string'
                              ,'folioaviso' => 'xsd:string'
                              ,'monto' => 'xsd:string'
                              ,'lc' => 'xsd:string'
                              ,'estatus_lc' => 'xsd:string'
                              ,'fcobro' => 'xsd:string'
                              ,'caja' => 'xsd:string'
                              ,'partida' => 'xsd:string'
                              ,'folioventanilla' => 'xsd:string'
                        ),  //parameter
			array('estatus' => 'xsd:string', 'folio' => 'xsd:string' ),  //output
			$ns,   //namespace
			$ns.'#insertaaviso',  //soapaction
			'rpc', // style
			'encoded', // use
			'Metodo que da de alta un tramite en vetanilla de avisos de testamento ');  //description

function checausuario($username, $password)
{
     global $parametro1;
     global $parametro2;
             debugea('AuthenticateUser entro usuario:'.$username.' password:'.$password.' type=');
             $connection = conneccion_tmp();
             debugea('AuthenticateUser paso coneccion');
             if ( $connection == "") { debugea("Error No se pudo conectar a la base de datos"); return false;}
             $sql="select passwd from pg_shadow where usename='".$username."'";
             $sql_result = @pg_exec($connection,$sql) ;
             if (strlen(pg_last_error($connection))>0)
             {
               debugea("No Pudo ejecutar el query para validar el usuario ".$username); return false;
             }
             $num = pg_numrows($sql_result);
             if ( $num == 0 )
             { debugea("No existe el usuario ".$username); return false; }
             else
             {
                     $Row = pg_fetch_array($sql_result, 0);
                     $wlpassword= $Row["passwd"];
             }
      debugea("pwd obtenido".$wlpassword);
      if ($wlpassword!="md5".md5($password.$username))
      { return false; }
      $parametro1=$username;
      $parametro2=$password;
      return true;

}

function regresa($codigo, $folio)
{
  return array("estatus" => $codigo, "folio" => $folio);
}
 
//second function implementation 
function insertaaviso($username, $password, $nombre, $paterno, $materno, $escritura, $notario, $folioaviso, $monto, $lc, $estatus_lc, $fcobro, $caja, $partida,$folioventanilla) {
        if ($username=="")
        { return regresa("w001", "");  }

        if ($password=="")
        { return regresa("w002", "");  }

        if (!checausuario($username, $password))
        { return regresa("w003", ""); }
             $connection = conneccion();
             debugea('AuthenticateUser paso coneccion');
             if ( $connection == "") { debugea("Error No se pudo conectar a la base de datos"); return regresa("w004", "");}
        $sql="insert into contra.gestion (fecharecibo,id_cveasunto,nombre,apepat,apemat,escr,nota,estatus,referencia,monto,lc,val_lc,folio) values (".
              "current_date,24,'".$nombre."','".$paterno."','".$materno."','".$escritura."','".$notario."',1,'".$folioaviso."',".$monto.",'".$lc."',".$estatus_lc.
              ",'".$folioventanilla."'".");";
        debugea('strsql='.$sql);
        $sql_result = @pg_exec($connection,$sql);
        if (strlen(pg_last_error($connection))>0)
        { return regresa("w005", pg_last_error($connection)); }
        $sql="select currval('contra.gestion_folioconsecutivo_seq') as id;";
        $sql_result = @pg_exec($connection,$sql);
        if (strlen(pg_last_error($connection))>0)
        { return regresa("w006", pg_last_error($connection)); }
        $Row = pg_fetch_array($sql_result, 0);
        $id=$Row["id"];
        $sql="select folio from contra.gestion where folioconsecutivo=".$id." and id_cveasunto=24"; 
        debugea('strsql2='.$sql);
        $sql_result = @pg_exec($connection,$sql);
        if (strlen(pg_last_error($connection))>0)
        { return regresa("w007", pg_last_error($connection)); }
        $num = pg_numrows($sql_result);
        if ($num==0) { return regresa("w007", ""); }
        $Row = pg_fetch_array($sql_result, 0);       
        return regresa("0000", $Row["folio"]);
}
 
$HTTP_RAW_POST_DATA = isset($HTTP_RAW_POST_DATA) ? $HTTP_RAW_POST_DATA : '';
 
$server->service($HTTP_RAW_POST_DATA);
##echo "debug:".$server->getDebug();
?>
