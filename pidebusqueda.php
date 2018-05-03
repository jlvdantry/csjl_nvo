<?php
	session_start();
    echo "<html>\n";
    echo "<script src='broseaing.js'></script>\n";
    $title="Busqueda de opciones";
    echo "<head><title>".$title."</title></head>";
    echo "<BODY onload='document.forms[0].texto1.focus();' onunload='window.returnValue=document.forms[0].texto1.value;' >\n";
    echo "<link type=\"text/css\" rel=\"StyleSheet\" MEDIA=screen HREF=\"pupan.css\" />\n";  // 20070328
##    echo "<form method=POST >";
    echo "<form >";
    echo "<table>";
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
    echo "<tr><th>Teclee el texto que desea buscar</th></tr>";
    echo "<tr align=center><td>";
    echo "	<input onChange='this.value=this.value.toUpperCase();' type=text id=texto1 size=100 maxlength=100 value=".$valor." >".$valor."</input>";
    echo "	<input type=image src='img/action_search_20.gif' title='Busca Opciones' name=vas ".
           "onclick='window.close();return false;' ></input></td></tr>";
    echo "</table>";
    echo "</form>";
    echo "</body>";    
    echo "	<script language=\"JavaScript\">";
	echo "	actualizaRelog ();	";
	echo "	</script>	";
    echo "</html>";
?>
