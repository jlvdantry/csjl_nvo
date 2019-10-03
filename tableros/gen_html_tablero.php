<?php
    include("conneccion.php");
    $wltablero=$_REQUEST['tablero'];
    $wlmodulo=$_REQUEST['modulo'];
    $wlturnos=$_REQUEST['turnos'];
    if ($argc>0)
    {  
       $wlturnos=$argv[3];
       $wltablero=$argv[2];
       $wlmodulo=$argv[1];
    }
              ob_start();
    echo "<?xml version=\"1.0\" standalone=\"no\"?>\n";
    echo "<!DOCTYPE svg PUBLIC \"-//W3C//DTD SVG 1.1//EN\" \"http://www.w3.org/Graphics/SVG/1.1/DTD/svg11.dtd\">\n";
    echo "<html>\n";
    echo "<head>\n";
    echo "<meta name='viewport' content='width=device-width, initial-scale=1'>\n";
    echo "<meta name='mobile-web-app-capable' content='yes' />\n";
    echo "<meta name='mobile-web-app-status-bar-style' content='black' />\n";
    echo "<link rel='manifest' href='tablero.json'>";
    echo "  <link type='text/css' rel='StyleSheet' href='styles/filas_rppcNuevo".$wltablero.".css' media='(min-width:1024px)' />\n";
    echo "  <link type='text/css' rel='StyleSheet' href='styles/filas_rppcNuevo".$wltablero.".css' media='(max-width:481px)' />\n";
    echo "  <link type='text/css' rel='StyleSheet' href='styles/filas_rppcNuevo".$wltablero.".css' media='(min-width:482px) and (max-width:1023px)' />\n";
    echo "<link rel='manifest' href='tablero.json' >\n";
    echo "<title>CEJUR tablero de llamado de turno y citas</title>";
    echo "</head>\n";


    if ($wlmodulo=="")
    { echo "No esta definido el modulo"; die(); }
    else
    {
      echo "<BODY onload=\"empieza(2,2);\">\n";
      $i=0;
      while ($i<$wlturnos) {
        echo "<div id='div".($i+1)."' class='div".($i+1)."' ></div>";
        $i=$i+1;
      }
      $sql_videos=damevideos($connection,$wltablero);
      $audio=dameaudios($connection,$wltablero);
      echo '<div id="wlvideo1">';
      echo '<video id="wlvideo" class="wlvideo" loop autoplay >';
      $num = pg_numrows($sql_videos);
      if ( $num >= 1 ) {
       for ($i=0; $i < $num ;$i++)
       {   $row = pg_fetch_array($sql_videos, $i);
           echo '<source src="upload_ficheros/'.$row["video"].'"  type="video/mp4" >';
       } 
      }
      echo '</video>';
      echo '</div>';

    echo '<div id="wlaudio1">';
    echo '<audio id="wlaudio" class="wlaudio" width="100%" height="100%"  >';
    echo '<source src="upload_ficheros/'.$audio.'"   >';
    echo '</audio>';
    echo '</div>';

    echo "<div id='centro' class='centro' >";
    echo '         <img id="centrologo" class="centrologo" src="img/acc17.png" ></img>';
    echo " </div>";
    echo "<div id='mensaje' class='mensaje' >";
    echo '         <img id="mensajelogo" class="mensajelogo" src="img/barra_abajo.png" ></img>';
    echo "</div>";
    echo "<div id='llamado' class='llamado' >";
    echo "</div>";
    echo "<div id='modulo' class='modulo' >".$wlmodulo."</div>";
    echo "<div id='turnos' class='turnos' >".$wlturnos."</div>";
    echo "<div id='conexion' class='conexion' >";
    echo '         <img id="conexionimg" class="conexionimg" onclick="muestralog()" src="img/disconnect.png" ></img>';
    echo "</div>";
    echo "<div id='desmodulo' class='desmodulo' >".$_REQUEST['desmodulo'];
    echo "</div>";
    echo "<div id='log' class='log' >";
    echo "</div>";
    echo "</body>\n";
    echo "</html>\n";
    echo "<script src='js/filas_wsNuevo.js'></script>\n";
    echo "<script src='js/svghelper.js'></script>\n";
    echo "<script src='js/svg.js'></script>\n";
    echo "<script src='js/app.js' async></script>\n";
    echo "<script src='js/db.js'></script>\n";
    }
 
     $fileName= "htab_".$wlmodulo."_".$wltablero."_".$wlturnos.".html";
     $htmlStr = ob_get_contents();
     ob_end_clean();
     file_put_contents($fileName, $htmlStr);

function damevideos($connection,$wltablero)
{
 $sql =" select cv.video from agenda.rel_tableros_videos rtv ".
       " , agenda.cat_videos cv where rtv.idtablero=".$wltablero." and rtv.idvideo=cv.idvideo ";
 $sql_result = pg_exec($connection,$sql);
 if (strlen(pg_last_error($connection))>0) { die ("Error al ejecutar qry 1 ".$sql." ".pg_last_error($connection)); }
 return $sql_result;
}

function dameaudios($connection,$wltablero)
{
 $sql =" select * from agenda.cat_tableros ct ".
       " , agenda.cat_audios cv where ct.idtablero=".$wltablero." and ct.idaudio=cv.idaudio ";
 $sql_result = pg_exec($connection,$sql);
 if (strlen(pg_last_error($connection))>0) { die ("Error al ejecutar qry 1 ".$sql." ".pg_last_error($connection)); }
 $num = pg_numrows($sql_result);
 if ( $num >= 1 )
 {   $Row = pg_fetch_array($sql_result, 0);
     return ($Row["audio"]!="" ? $Row["audio"] : $Row["descripcion"]) ;
 } else { return ""; }
}

?>
