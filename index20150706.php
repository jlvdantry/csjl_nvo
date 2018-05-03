<?php
    session_unset();
    ini_set('register_globals','1');
    echo "<script language='javascript'>	";
    echo	"	if (self.parent.frames.length != 0)	self.parent.location='index.php';	";
    echo "</script>	";	
    echo "<FRAMESET id='pr' rows=\"15%,*\" NAME=\"algo\" >";
    echo "<FRAME id='titulos' class='fmenu' SRC=\"titulos.php\" NAME=\"titulos\" frameborder='no' scrolling='auto'>";
    echo "<FRAMESET id='fs' rows=\"100%,*\" NAME=\"algo\" >";
    echo "<FRAME id='izquierdo' class='fmenu' SRC=\"entrada.php\" NAME=\"menu\" frameborder='no' scrolling='auto'>";
    echo "<FRAME id='derecho' NAME=\"pantallas\" frameborder='no' scrolling='auto' src='logo.php'>";
    echo "</frameset>";
    echo "</frameset>";
?>
