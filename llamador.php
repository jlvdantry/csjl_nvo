<?php
error_reporting(E_ALL);
    ##include("conneccion.php");
    $wltablero=$_REQUEST['tablero'];
    $wlmodulo="01";
    $desmodulo="FIRMADO ELECTRONICO";
    $wlturnos=1;
    if ($argc>0)
    {  
       $wlturnos=$argv[3];
       $wltablero=$argv[2];
       $wlmodulo=$argv[1];
    }
    echo "<?xml version=\"1.0\" standalone=\"no\"?>\n";
    ##echo "<!DOCTYPE svg PUBLIC \"-//W3C//DTD SVG 1.1//EN\" \"http://www.w3.org/Graphics/SVG/1.1/DTD/svg11.dtd\">\n";
    ##echo "<!DOCTYPE svg PUBLIC \"-//W3C//DTD SVG 1.1//EN\" \"http://www.w3.org/2002/04/xhtml-math-svg/xhtml-math-svg.dtd\">\n";
    echo "<head>\n";
    echo "<meta name='viewport' content='width=device-width, initial-scale=1'>\n";
    echo "<meta name='mobile-web-app-capable' content='yes' />";
    echo "<meta name='mobile-web-app-status-bar-style' content='black' />";
    echo "<link rel='manifest' href='manifest.json'>";
    echo "  <link type='text/css' rel='StyleSheet' href='llamador.css' media='(min-width:1024px)' />\n";
    echo "  <link type='text/css' rel='StyleSheet' href='llamador.css' media='(max-width:481px)' />\n";
    echo "  <link type='text/css' rel='StyleSheet' href='llamador.css' media='(min-width:482px) and (max-width:1023px)' />\n";
    echo "<title>CEJUR Llamador de turnos y citas</title>";
    echo "</head>\n";


    if ($wlmodulo=="")
    { echo "No esta definido el modulo"; die(); }
    else
    {
      echo "<BODY onload='empieza(2,2);'>";
echo " <svg id='svg0' style=\"left:8%; top:60%; width:80%; height:30%; rx:30 ; position:fixed ; fill:#DF1874 ;  \" >";
echo "  <script type='text/JavaScript'>\n";
echo "            <![CDATA[\n";
echo "function llamaturno(x)\n";
echo "{\n";
echo "    marcallamado(x); \n";
echo "}\n";
echo "         ]]>";
echo "         </script>";
echo " <g><rect x='0' y='0' width='100%' height='100%'  rx='3%' class='llama_rect1' id='rect0' onClick='llamaturno(this)' ></rect><text onClick='llamaturno(this)' x='5%' y='75%' class='fila'>Dar un click para llamar a turno o cita</text></g></svg>";
      $i=1;
      while ($i<2) {
        echo "<div id='div".($i)."' class='div".($i)."' ></div>";
        $i=$i+1;
      }
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
    echo "<div id='desmodulo' class='desmodulo' >".$desmodulo;
    echo "</div>";
    echo "<div id='log' class='log' >";
    echo "</div>";
    echo "</body>\n";
    echo "</html>";
    echo "<script src='llamador.js'></script>\n";
    echo "<script src='svghelper.js'></script>\n";
    echo "<script src='svg.js'></script>\n";
    }

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
 $sql =" select cv.audio from agenda.cat_tableros ct ".
       " , agenda.cat_audios cv where ct.idtablero=".$wltablero." and ct.idaudio=cv.idaudio ";
 $sql_result = pg_exec($connection,$sql);
 if (strlen(pg_last_error($connection))>0) { die ("Error al ejecutar qry 1 ".$sql." ".pg_last_error($connection)); }
 $num = pg_numrows($sql_result);
 if ( $num >= 1 )
 {   $Row = pg_fetch_array($sql_result, 0);
     return $Row["audio"];
 } else { return ""; }
}

?>
