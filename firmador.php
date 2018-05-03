<?php
    error_reporting(E_ALL);
    $wltablero=$_REQUEST['tablero'];
    $wlmodulo="01";
    $modulo="01";
    $desmodulo="FIRMADO ELECTRONICO DE DOCUMENTOS DE AGN";
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
    ##echo "<meta name='viewport' content='width=device-width, initial-scale=1' />\n";
    echo "<meta name='mobile-web-app-capable' content='yes' />\n";
    echo "<meta name='mobile-web-app-status-bar-style' content='black' />\n";
    echo "<link rel='manifest' href='manifest.json' />\n";
    echo "  <link type='text/css' rel='StyleSheet' href='firmador.css' media='(min-width:1024px)' />\n";
    echo "  <link type='text/css' rel='StyleSheet' href='firmador.css' media='(max-width:481px)' />\n";
    echo "  <link type='text/css' rel='StyleSheet' href='firmador.css' media='(min-width:482px) and (max-width:1023px)' />\n";
    echo "<title>CEJUR Llamador de turnos y citas</title>";
    echo "</head>\n";


    if ($wlmodulo=="")
    { echo "No esta definido el modulo"; die(); }
    else
    {
      echo "<BODY onload='empieza(2,2);'>";
echo " <svg id='svg0'  >";
echo "  <script type='text/JavaScript'>\n";
echo "            <![CDATA[\n";
echo "function cargarfiel(x)\n";
echo "{\n";
echo "    x = new eve_particulares(); \n";
echo "    x.cargafiellocal(); \n";
echo "}\n";
echo "         ]]>";
echo "         </script>";
echo " <g><rect x='0' y='0' width='100%' height='100%'  rx='3%' class='llama_rect1' id='rect0' onClick='llamaturno(this)' ></rect><text onClick='firmadocumento(this)' x='5%' y='75%' class='fila'>Dar un click para firmar documento</text></g></svg>";
echo " <div id='div1'   >";
echo "  <script type='application/ecmascript'>\n";
echo "            <![CDATA[\n";
echo "function cargarfiel(x)\n";
echo "{\n";
echo "    x = new eve_particulares(); \n";
echo "    x.cargafiellocal(); \n";
echo "}\n";
echo "         ]]>";
echo "         </script>";
echo " <g onclick='cargarfiel(this);'><rect x='0' y='0' width='100%' height='100%'  rx='3%' class='llama_rect1' id='rect1' onclick='cargarfiel(this)' ></rect><text  x='5%' y='75%' onclick='cargarfiel(this)' class='fila'>Dar un click para cargar fiel</text></g></div>";
echo " <svg id='svg2'    \" >";
echo " <g><rect x='0' y='0' width='100%' height='100%'  rx='3%' class='llama_rect1' id='rect2' onClick='llamaturno(this)' ></rect><text onClick='conectaragn(this)' x='5%' y='75%' class='fila'>Dar un click para conectarse con AGN</text></g></svg>";
      $i=1;
      while ($i<3) {
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
    echo "<script src='firmador.js'></script>\n";
    echo "<script src='eve_particulares.js'></script>\n";
    echo "<script src='jsrsasign-latest-all-min.js'></script>\n";
    echo "<script src='leearchivo.js'></script>\n";
}
?>
