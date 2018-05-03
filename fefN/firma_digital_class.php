<?php
session_start();
date_default_timezone_set('America/Chicago');
require_once("xmlhttp_class.php");
require_once("sellado/cliente_soa_class.php");
require_once("sellado/vuat_XML_class.php");
class firma_digital_class extends xmlhttp_class
{
    private $_username;
    private $_password;
    private $_digest;
    private $Row;
    private $xml;
    function pidefiel()
    {
                echo "<abresubvista></abresubvista>";
                echo "<wlhoja>man_menus.php</wlhoja>";
                echo "<wlcampos>idmenu=2526</wlcampos>";
                echo "<wldialogWidth>50</wldialogWidth>";
                echo "<wldialogHeight>30</wldialogHeight>";
    }
    function verenhtml() {
                echo "<abresubvista></abresubvista>";
                echo "<wlhoja>sellado/XMLaHTML.php</wlhoja>";
                echo "<wlcampos>xml=../upload_ficheros/".$this->argumentos["wl_mifirma"]." </wlcampos>";
                echo "<wldialogWidth>500</wldialogWidth>";
                echo "<wldialogHeight>600</wldialogHeight>";
    }

    function validafiel()
    {
       $wsdl = 'http://10.250.103.116/htdocs/contra/csjl_nvo/csjl_nvo/sellado/ws_oficinaSellado.php?wsdl';
       $this->debugea('antes de new mysoap xml=');
       $sClient = new mySoap($wsdl,array('trace' => true, 'cache_wsdl' => WSDL_CACHE_MEMORY));
       $this->debugea('cliente paso mysoap');
       $sClient->addUserToken('jlv', 'dantry', true);
       $this->debugea('cliente paso addUserToken');
       //$xml="/var/www/htdocs/contra/csjl_nvo/csjl_nvo/upload_ficheros/".$this->argumentos["wl_mifirma"];
       $xmlpu="/var/www/htdocs/contra/csjl_nvo/csjl_nvo/upload_ficheros/".$this->argumentos["wl_llavepublica"];
       $xmlpr="/var/www/htdocs/contra/csjl_nvo/csjl_nvo/upload_ficheros/".$this->argumentos["wl_llaveprivada"];
       $outc = $sClient->validafiel(array("publica" => $xmlpu,"privada" => $xmlpr,"password" => $this->argumentos["wl_password"] ));
       if ($outc->valido==1) {
          session_register("wl_llavepublica");
          session_register("wl_llaveprivada");
          $_SESSION["wl_llavepublica"]=base64_encode(file_get_contents($xmlpu));
          $_SESSION["wl_llaveprivada"]=base64_encode(file_get_contents($xmlpr));
          //$_SESSION["wl_llavepublica"]=file_get_contents($xmlpu);
          //$_SESSION["wl_llaveprivada"]=file_get_contents($xmlpr);
          //echo "<error>Es valida la llave privada y el certificado</error>";
          echo "<error>Es valida la llave privada y el certificado llaveprivada=".$_SESSION["wl_llaveprivada"]."</error>";
          unlink($xmlpu);
          unlink($xmlpr);
          return ;
       }
       if ($outc->valido==0) {
          echo "<error>No es valido el documento</error>";
          return ;
       }
       echo "<error>Error al checar la firma</error>";
    }

    function clientesellado()
    {
       $wsdl = 'http://10.250.103.116/htdocs/contra/csjl_nvo/csjl_nvo/sellado/ws_oficinaSellado.php?wsdl';
       $this->debugea('antes de new mysoap xml=');
       $sClient = new mySoap($wsdl,array('trace' => true, 'cache_wsdl' => WSDL_CACHE_MEMORY));
       $this->debugea('cliente paso mysoap');
       $sClient->addUserToken('jlv', 'dantry', true);
       $this->debugea('cliente paso addUserToken');
       return $sClient;
    }

    function checarfirmadigital()
    {
       $wsdl = 'http://10.250.103.116/htdocs/contra/csjl_nvo/csjl_nvo/sellado/ws_oficinaSellado.php?wsdl';
       $this->debugea('antes de new mysoap xml=');
       $sClient = new mySoap($wsdl,array('trace' => true, 'cache_wsdl' => WSDL_CACHE_MEMORY));
       $this->debugea('cliente paso mysoap');
       $sClient->addUserToken('jlv', 'dantry', true);
       $this->debugea('cliente paso addUserToken');
       $xml="/var/www/htdocs/contra/csjl_nvo/csjl_nvo/upload_ficheros/".$this->argumentos["wl_mifirma"];
       $xml="../upload_ficheros/".$this->argumentos["wl_mifirma"];
       $outc = $sClient->checarsellado(array("xml" => $xml,"esarchivo" => "1" ));
       if ($outc->valido==1) {
          echo "<error>Es valido el documento</error>";
          return ;
       }
       if ($outc->valido==0) {
          echo "<error>No es valido el documento</error>";
          return ;
       }
       echo "<error>Error al checar la firma</error>"; 
    }

    function gendocto()
    {
       $this->Row=$this->avisosdetestamento();
       $this->Row["sello"]="local";
       $this->Row["certificado"]="local";
       $this->Row["cadenaoriginal"]="cadena";
       $this->Row["folioCertificado"]="local";
       $xml=$this->dame_xml();
/*
       $wsdl = 'http://10.250.103.116/htdocs/contra/csjl_nvo/csjl_nvo/sellado/ws_oficinaSellado.php?wsdl';
       $this->debugea('antes de new mysoap xml='.$xml);
       $sClient = new mySoap($wsdl,array('trace' => true, 'cache_wsdl' => WSDL_CACHE_MEMORY));
       $this->debugea('cliente paso mysoap');
       $sClient->addUserToken('jlv', 'dantry', true);
       $this->debugea('cliente paso addUserToken');
       $outc = $sClient->generacadena(array("xml" => $xml ));
       $this->Row["cadenaoriginal"]=$outc->cadena;
       $this->debugea('Cadena generada='.$this->Row["cadenaoriginal"]);
       $xml=$this->dame_xml();
       $this->debugea('va a generar xml con sello'.$xml);
*/
       echo "<docto>".htmlspecialchars($xml)."</docto>";
    }

    function firmardigitalmente()
    {
       if (!isset($_SESSION['wl_llaveprivada']) || !isset($_SESSION['wl_llavepublica'])) 
       { echo "<error>No ha carga su llave privada y publica</error>";  return false;}
       if ($this->argumentos["wl_password"]=="")
       { echo "<error>El password esta vacio</error>"; return; }
       $this->Row=$this->avisosdetestamento();
       $this->Row["sello"]="";
       $this->Row["certificado"]="";
       $this->Row["cadenaoriginal"]="";
       $this->Row["folioCertificado"]="";
       $xml=$this->dame_xml();
       $wsdl = 'http://10.250.103.116/htdocs/contra/csjl_nvo/csjl_nvo/sellado/ws_oficinaSellado.php?wsdl';
       $this->debugea('antes de new mysoap xml='.$xml);
       $sClient = new mySoap($wsdl,array('trace' => true, 'cache_wsdl' => WSDL_CACHE_MEMORY));
       $this->debugea('cliente paso mysoap');
       $sClient->addUserToken('jlv', 'dantry', true);
       $this->debugea('cliente paso addUserToken');
       $outc = $sClient->generacadena(array("xml" => $xml ));
       $this->Row["cadenaoriginal"]=$outc->cadena;
       $this->debugea('Cadena generada='.$this->Row["cadenaoriginal"]);
       $out = $sClient->sellado(array("cadena" => $outc->cadena,"publica" => $_SESSION['wl_llavepublica'], "privada" => $_SESSION['wl_llaveprivada'],"password"=>$this->argumentos["wl_password"] ));
       if ($out->ok!="1")
       { echo "<error>No pudor firmar</error>"; return false;}
       $this->Row["sello"]=$out->sello;
       $this->Row["certificado"]=$out->certificado;
       $this->Row["folioCertificado"]=$this->altaadjuntaravisos();
       $xml=$this->dame_xml();
       $this->debugea('va a generar xml con sello');
       file_put_contents("upload_ficheros/".$this->Row["folioCertificado"].".xml",$xml);
       echo "<error>Firma electronica efectuada satisfactoriamente</error>";
    }

    function altaadjuntaravisos()
    {
       $sql =" insert into menus_archivos (descripcion) values ('".$this->Row["id_escritura"]."');";
       $sql_result = pg_exec($this->connection,$sql);
       if (strlen(pg_last_error($this->connection))>0) { die ("Error al ejecutar qry 1 ".$sql." ".pg_last_error($this->connection)); }
       $sql =" select currval(pg_get_serial_sequence('menus_archivos', 'idarchivo'));";
       $sql_result = pg_exec($this->connection,$sql);
       if (strlen(pg_last_error($this->connection))>0) { die ("Error al ejecutar qry 2 ".$sql." ".pg_last_error($this->connection)); }
       $Row = pg_fetch_array($sql_result, 0);
       $sql =" insert into avitesta.escritura_firma(id_escritura,idarchivo) values (".$this->Row["id_escritura"].",".$Row[0].");";
       $sql_result = pg_exec($this->connection,$sql);
       if (strlen(pg_last_error($this->connection))>0) { die ("Error al ejecutar qry 1 ".$sql." ".pg_last_error($this->connection)); }
       return $Row[0];
    }

    function avisosdetestamento()
    {
          $num=0;
          $id=$this->argumentos["wl_folioaviso"];
          $sql=" select * from avitesta.v_escritura where folioaviso ='".$id."'";
          $sql_result = @pg_exec($this->connection,$sql);
          if (strlen(pg_last_error($this->connection))>0)
          {
                        echo "<error>Error buscacalificacion".pg_last_error($this->connection)."</error>";
                        return false;
          }
          $num = pg_numrows($sql_result);
          if ($num==0)
          { echo "<error>No encontro el aviso de testamento</error>"; return;  }
          return pg_fetch_array($sql_result, 0);
    }

    function dame_xml()
    {
          $x = new vuat_XML_class;
          $x->arr=array(
                       "noFirma"=>"",
                       "sello"=>$this->Row["sello"],
                       "certificado"=>$this->Row["certificado"],
                       "folioCertificado"=>$this->Row["folioCertificado"],
                       "tramite"=>"Aviso de testamento",
                       "lineaDeCaptura"=>"temporal",
                       "fechaEmision"=>gmdate("Y-m-d\TH:i:s"),
                       "cadenaOriginal"=>$this->Row["cadenaoriginal"],
                       "numeroNotario"=>$this->Row["numero"],
                       "nombreNotario"=>$this->Row["not_nombrecompleto"],
                       "notasDelAviso"=>$this->Row["notas"],
                       "folioAviso"=>$this->Row["folioaviso"],
                       "testador"=>array(
                          "nombre"=>html_entity_decode($this->Row["nombre"]),
                          "apellidoPaterno"=>html_entity_decode($this->Row["ap_paterno"]),
                          "apellidoMaterno"=>html_entity_decode($this->Row["ap_materno"]),
                          "nacionalidad"=>"mexicana",
                          "lugarDeNacimiento"=>html_entity_decode($this->Row["lugar_nacimiento"]),
                          "fechaDeNacimiento"=>$this->Row["fecha_nacimiento"],
                          "estadoCivil"=>$this->Row["nombre_estado_civil"],
                          "apellidoConyuge"=>html_entity_decode($this->Row["ap_conyuge"]),
                          "tambienConocidoComo"=>$this->Row["alias"]
                                         ),
                       "Instrumento"=>array(
                          ##"tipodeTestamento"=>html_entity_decode($this->Row["nombre_tipo_testamento"]),
                          "tipodeTestamento"=>utf8_decode($this->Row["nombre_tipo_testamento"]),
                          "escritura"=>$this->Row["num_escritura"],
                          "volumen"=>$this->Row["tomo"],
                          "fechaDeEscritura"=>$this->Row["fecha_escritura"],
                          "fechaDeOtorgamiento"=>$this->Row["fecha_otorgamiento"]
                                           ),
                       "Padres"=>array(
                          "nombrePadre"=>html_entity_decode($this->Row["nombre_padre"]),
                          "paternoPadre"=>html_entity_decode($this->Row["ap_paterno_padre"]),
                          "maternoPadre"=>html_entity_decode($this->Row["ap_materno_padre"]),
                          "nombreMadre"=>html_entity_decode($this->Row["nombre_madre"]),
                          "paternoMadre"=>html_entity_decode($this->Row["ap_paterno_madre"]),
                          "maternoMadre"=>html_entity_decode($this->Row["ap_materno_madre"]),
                          "apellidoConyugeMadre"=>html_entity_decode($this->Row["ap_conyuge_madre"]),
                                           ),
                       "Domicilio"=>array(
                          ##"calleYNumero"=>utf8_encode(html_entity_decode($this->Row["calle_num"])),
                          "calleYNumero"=>html_entity_decode($this->Row["calle_num"]),
                          "colonia"=>html_entity_decode($this->Row["colonia"]),
                          "delegacionMunicipio"=>html_entity_decode($this->Row["tes_nombremunicipio"]),
                          "entidad"=>html_entity_decode($this->Row["nombre_estado"]),
                          "codigoPostal"=>$this->Row["cp"],
                                           ),
           );
           return $x->genera_xml();
    }

    function debugea($wlstring)
    {
        $dt = date("Y-m-d H:i:s:u ");
        $dia = date("Ymd");
        error_log("$dt $wlstring \n",3,"wservice_TC$dia.log");
        chmod("wservice_TC$dia.log", 0677);
    }
}
?>
