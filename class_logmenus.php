<?php
class logmenus
{
   var $connection="";  /* coneccion */
   function logmenus($connection)
   {
           if ($connection=="") {
                   echo "No ha definido una conneccion a la bd";
           }
           $this->connection=$connection;
   }

   function registra($idmenu,$sqlenviado,$mensaje = "")
   {
        if ($mensaje=="") { $mensaje=0; }
        $sql=" select idmenu from forapi.menus_seguimiento where idmenu=".$idmenu;
        $sql_result = pg_exec($this->connection,$sql)
                    or die("No se pudo hacer el sql en registra".$sql);
        $num = pg_numrows($sql_result);
        if ( $num != 0 )
        {
                     $quebro=$this->getBrowser();
                        $sql="insert into forapi.menus_log (idmenu,sql,ip,esmovil,browser,idmensaje) values ".
                     " (".$idmenu.",'".str_replace("'","''",$sqlenviado)."',' ".$this->getRealUserIp()."',".$this->isMobile().",".$quebro['name'].",$mensaje)";
                    $sql_result = pg_exec($this->connection,$sql)
                    or die("No se pudo hacer el sql en registra menu_log".$sql);
        }
   }

function getRealUserIp(){
    switch(true){
      case (!empty($_SERVER['HTTP_X_REAL_IP'])) : return $_SERVER['HTTP_X_REAL_IP'];
      case (!empty($_SERVER['HTTP_CLIENT_IP'])) : return $_SERVER['HTTP_CLIENT_IP'];
      case (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) : return $_SERVER['HTTP_X_FORWARDED_FOR'];
      default : return $_SERVER['REMOTE_ADDR'];
    }
 }

   function isMobile()
   {
         return preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $_SERVER["HTTP_USER_AGENT"]);
   }
   function getBrowser()
   {
    $u_agent = $_SERVER['HTTP_USER_AGENT'];
    $bname = '0';
    $platform = 'Unknown';
    $version= "";

    //First get the platform?
    if (preg_match('/linux/i', $u_agent)) {
        $platform = 'linux';
    }
    elseif (preg_match('/macintosh|mac os x/i', $u_agent)) {
        $platform = 'mac';
    }
    elseif (preg_match('/windows|win32/i', $u_agent)) {
        $platform = 'windows';
    }

    // Next get the name of the useragent yes seperately and for good reason
    if(preg_match('/MSIE/i',$u_agent) && !preg_match('/Opera/i',$u_agent))
    {
        $bname = '1';
        $ub = "MSIE";
    }
    elseif(preg_match('/Firefox/i',$u_agent))
    {
        $bname = '2';
        $ub = "Firefox";
    }
    elseif(preg_match('/Chrome/i',$u_agent))
    {
        $bname = '3';
        $ub = "Chrome";
    }
    elseif(preg_match('/Safari/i',$u_agent))
    {
        $bname = '4';
        $ub = "Safari";
    }
    elseif(preg_match('/Opera/i',$u_agent))
    {
        $bname = '5';
        $ub = "Opera";
    }
    elseif(preg_match('/Netscape/i',$u_agent))
    {
        $bname = '6';
        $ub = "Netscape";
    }

    // finally get the correct version number
    $known = array('Version', $ub, 'other');
    $pattern = '#(?<browser>' . join('|', $known) .
    ')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';
    if (!preg_match_all($pattern, $u_agent, $matches)) {
        // we have no matching number just continue
    }

    // see how many we have
    $i = count($matches['browser']);
    if ($i != 1) {
        //we will have two since we are not using 'other' argument yet
        //see if version is before or after the name
        if (strripos($u_agent,"Version") < strripos($u_agent,$ub)){
            $version= $matches['version'][0];
        }
        else {
            $version= $matches['version'][1];
        }
    }
    else {
        $version= $matches['version'][0];
    }
    if ($version==null || $version=="") {$version="?";}

    return array(
        'userAgent' => $u_agent,
        'name'      => $bname,
        'version'   => $version,
        'platform'  => $platform,
        'pattern'    => $pattern
    );
   }
}
?>
