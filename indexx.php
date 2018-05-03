<?php
    session_unset();
    echo "<head>\n";
    echo " <meta name='viewport' content='width=device-width, initial-scale=1'>\n";
    ##echo "<meta name='mobile-web-app-capable' content='yes' />";
    ##echo "<meta name='mobile-web-app-status-bar-style' content='black' />";
    ##echo "<link rel='manifest' href='manifest.json'>";
    echo "<title>CEJUR</title>";
    echo "</head>\n";

    echo "<body name=principal style='margin:0; height=100% width:100% ' >";
    ini_set('register_globals','1');
    echo "  <link type='text/css' rel='StyleSheet' href='pupan.css'  media='(min-width:1024px)' />\n";
    echo "  <link type='text/css' rel='StyleSheet' href='pupan_s.css' media='(max-width:481px)' />\n";
    echo "  <link type='text/css' rel='StyleSheet' href='pupan_m.css' media='(min-width:482px) and (max-width:1023px)' />\n";
    echo "  <link type='text/css' rel='StyleSheet' href='ddmenu.css'  />\n";
    echo "  <script src='scripts.js' type='text/javascript' language='javascript'/> ";
    echo "<script language='javascript'>        ";
    echo        "       if (self.parent.frames.length != 0)     self.parent.location='index.php';       ";
    echo "</script>     ";
    echo "<div  id='div_titulos'  >";
    echo "<iframe id='titulos' align='center' style='width:100% ; height:27%; margin:0px; border:none; padding:0px; ' class='fmenu' SRC=\"titulos.php\" NAME=\"titulos\" frameborder='0' framespacing='0px' scrolling='no'></iframe>";
    echo "</div>";
    echo "<div id=menus><iframe id='izquierdo' style=' top:300px; width:100%; height:63%; ' frameborder='0' framespacing='0px' class='fmenu' SRC=\"entrada.php\" NAME=\"menu\" frameborder='no' scrolling='no'></iframe></div>";
    echo "</body>";
##    echo "<div><iframe id='derecho' style='position:relative; z-index:3; width:100%; height:100%; ' NAME=\"pantallas\" frameborder='no' scrolling='auto' src='logo.php'></iframe></div>";
?>

