<?php
header("Access-Control-Allow-Origin: *");
session_start();
$_SESSION = array();
    echo "<head>\n";
    echo " <meta name='viewport' content='width=device-width, initial-scale=1'>\n";
    echo " <link rel='icon' type='image/png' href='favicon.png'>\n";
    ##echo "<meta name='mobile-web-app-capable' content='yes' />";
    ##echo "<meta name='mobile-web-app-status-bar-style' content='black' />";
    ##echo "<link rel='manifest' href='manifest.json'>";
    echo "<title>STyFE</title>";
    echo "</head>\n";
    echo "<body name=principal style='margin:0; height=100% width:100% '>\n";
    echo "  <link type='text/css' rel='StyleSheet' href='pupan.css'  media='(min-width:1024px)' />\n";
    echo "  <link type='text/css' rel='StyleSheet' href='pupan_s.css' media='(max-width:481px)' />\n";
    echo "  <link type='text/css' rel='StyleSheet' href='pupan_m.css' media='(min-width:482px) and (max-width:1023px)' />\n";
    ##echo "  <link type='text/css' rel='StyleSheet' href='ddmenu.css'  />\n";
    echo "  <script src='scripts.js' type='text/javascript' language='javascript'></script> ";
    ##echo "  <script src='ddmenu.js' type='text/javascript' language='javascript'></script> ";
    echo "<script language='javascript'>\n";
    echo        "       if (self.parent.frames.length != 0)     self.parent.location='index.php';\n";
    echo "</script>\n";
    echo "<div  id='div_titulos'  >\n";
    echo "<iframe id='titulos' align='center' style='width:100% ; height:15%; margin:0px; border:none; padding:0px; ' class='fmenu' SRC=\"titulos.php\" NAME=\"titulos\" frameborder='0' framespacing='0px' scrolling='no' resize='both' ></iframe>\n";
   echo "</div>\n";
    echo "<div id=menus class='div_menus' >\n<iframe id='izquierdo' allowtransparency='yes' style='   width:100%; height:75%; resize:both; ' frameborder='0' framespacing='0px' class='fmenu' SRC=\"entrada.php\" NAME=\"menu\" frameborder='no' scrolling='auto'  ></iframe></div>\n";
    echo "</body>\n";
?>
