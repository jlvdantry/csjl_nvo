<?php
	session_start();
    echo "<html>\n";
    echo "<script src='broseaing.js'></script>\n";
    $title="Captura de texto";
    echo "<head><title>".$title."</title></head>";
    echo "<BODY onload='document.forms[0].texto1.focus();' onunload='window.returnValue=document.forms[0].texto1.value;' >\n";
##    20070328   se incluyo el css del sistema    
##    echo "<link type=\"text/css\" rel=\"StyleSheet\" MEDIA=screen />\n";
    echo "<link type=\"text/css\" rel=\"StyleSheet\" MEDIA=screen HREF=\"pupan.css\" />\n";  // 20070328
    echo "<form method=POST >";
    echo "<table>";
    echo "<caption><div class=titulo>".$title."</div>";
    echo "<div class=fecha>	";
    if ($_SESSION["parametro1"]!="")
    	{
    		echo "<input class=enca readonly size=30% align=center typr=text Id=\"wl_encausr\" value=\"Usuario: ".$_SESSION["parametro1"]."\"></input>";
		}
		echo "<input class=enca readonly size=40% align=center typr=text Id=\"wl_encafecha\"></input>";
    	echo "<input class=enca readonly size=20% align=center typr=text Id=\"wl_encahora\"></input>";
	echo "</div>";

    echo "</caption>";
    echo "<tr><th>Teclee el texto que desea registrar</th></tr>	";
	echo "<tr><td><textarea id=texto1  rows=15 cols=145 value=\"".$valor."\" onBlur='this.value=this.value.toUpperCase()'>".$valor."</textarea>";
	echo " <input type=image src='img/add.gif' title='Regitra' name=vas onclick='window.close();return false;' ></input></td></tr>";
	echo "</table>";
	echo "</form>";
    echo "</body>";    
    echo "	<script language=\"JavaScript\">";
	echo "	actualizaRelog ();	";
	echo "	</script>	";
    echo "</html>";
?>