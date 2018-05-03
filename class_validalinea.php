<?php
ini_set('display_errors','On');
error_reporting(E_ALL);
include_once("nusoap-0.9.5/nusoap.php");
class class_validalinea
{
        var $lc="";
       // function class_validalinea()
       // {
       // }
        function validapago()
        {
              $URL ="http://10.1.65.9/formato_lc/utilerias/comprabadorLineas/com_ws_secure_server_duplicadas.php?wsdl";
              ##$URL ="http://www.finanzas.df.gob.mx/formato_lc/utilerias/comprabadorLineas/com_ws_secure_server_duplicadas.php?wsdl";
              $proxyhost = isset($_POST['proxyhost']) ? $_POST['proxyhost'] : '';
              $proxyport = isset($_POST['proxyport']) ? $_POST['proxyport'] : '';
              $proxyusername = isset($_POST['proxyusername']) ?  $_POST['proxyusername'] : '';
              $proxypassword = isset($_POST['proxypassword']) ?  $_POST['proxypassword'] : '';
              $client = new nusoap_client($URL,true,$proxyhost, $proxyport,$proxyusername, $proxypassword);
              $err = $client->getError();
              if ($err) {
                 return "-1";
              }
              $call='consultar_pago';
              $pregunta = array('lineacaptura' => $this->lc,
                     'usuario' => 'setravi_ap',
                     'password' => 'fe83b3c184b8ee1df5034314b542a94e');
              $result = $client->call($call, array('pregunta' => $pregunta));
              if ($client->fault) {
                 return "-1";
              }
              $err = $client->getError();
              if ($err) {
                 return "-1";
              }
              return $result;
        }

        function marcapago($pos,$folioconsecutivo,$connection,$idpago=0)
        {
          ##print_r($pos);
          if ($idpago==0) {
             if (count($pos)==1 && $pos[0]['lineacaptura']!='')
             {
               $sqlu="update contra.gestion set val_lc=2, intentosval=intentosval+1,fechapago='".$pos[0]['fechapago']."',importe=".$pos[0]['importe'].
                         ",banco=".$pos[0]['banco'].",sucursal=".$pos[0]['sucursal'].",lc='".$this->lc."'".
                         " where folioconsecutivo=".$folioconsecutivo;
             }
             if (count($pos)>1)
             {
               $sqlu="update contra.gestion set val_lc=5, intentosval=intentosval+1,fechapago='".$pos[1]['fechapago']."',importe=".$pos[1]['importe'].
                         ",banco=".$pos[1]['banco'].",sucursal=".$pos[1]['sucursal'].
                         " where folioconsecutivo=".$folioconsecutivo;
             }
             if (count($pos)==1 && $pos[0]['lineacaptura']=='')
             {
               $sqlu="update contra.gestion set intentosval=intentosval+1,importe=0,banco=0,sucursal=0,fechapago=null,lc='".$this->lc."',val_lc=4 where folioconsecutivo=".$folioconsecutivo;
             }
             ##echo $sqlu;
             pg_exec($connection,$sqlu);
          } else {
             if (count($pos)==1 && $pos[0]['lineacaptura']!='')
             {
               $sqlu="update contra.pagos set val_lc=2, intentosval=intentosval+1,fechapago='".$pos[0]['fechapago']."',importe=".$pos[0]['importe'].
                         ",banco=".$pos[0]['banco'].",sucursal=".$pos[0]['sucursal'].
                         " where folioconsecutivo=".$folioconsecutivo." and id=".$idpago;
             }
             if (count($pos)>1)
             {
               $sqlu="update contra.pagos set val_lc=5, intentosval=intentosval+1,fechapago='".$pos[1]['fechapago']."',importe=".$pos[1]['importe'].
                         ",banco=".$pos[1]['banco'].",sucursal=".$pos[1]['sucursal'].
                         " where folioconsecutivo=".$folioconsecutivo." and id=".$idpago;
             }
             if (count($pos)==1 && $pos[0]['lineacaptura']=='')
             {
               $sqlu="update contra.pagos set intentosval=intentosval+1,importe=0,banco=0,sucursal=0,fechapago=null,lc='".$this->lc."'val_lc=4 where folioconsecutivo=".$folioconsecutivo." and id=".$idpago;
             }
             ##echo $sqlu;
             pg_exec($connection,$sqlu);
          }
             $sqli="insert into contra.busquedas_lc (folioconsecutivo,idpago,pagos,resultado,lc) values (".$folioconsecutivo.",".$idpago.",".count($pos).",'".json_encode($pos)."','".$this->lc."');";
             pg_exec($connection,$sqli);
        }

}
