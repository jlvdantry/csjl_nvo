<? session_start() ;
  include("mensajes.php");
  include("bd_gestion.php");
  echo "<HTML>\n" ;
  echo "<head>\n" ;
  echo " <LINK REL=StyleSheet HREF=\"estilo_contra.css\" TYPE=\"text/css\" MEDIA=screen>\n";
function soldatos($connection,$wlid_tipodocto,$wlidpersona_envia,$wlfechadocumento,$wlidpersona_recibe,$wlfecharecibo,$wlfolioconsecutivo,$wlasunto,$wlidpuesto,$wlidpersona)
{
 $sql =" SELECT * from dame_1folio(".$wlfolioconsecutivo.")";
 $sql_result = pg_exec($connection,$sql)
               or die("Couldn't make query. ".$sql);
 $num = pg_numrows($sql_result);

 if ($num==0) {
    menerror('el folio no existe'.$wlfolioconsecutivo);
    die();
 }
 $Row = pg_fetch_array($sql_result, 0);
 echo "<tr><td align=left ><b id=\"excelso\">Folio </b></td>";
 echo "<td align=left >".$wlfolioconsecutivo."</b></td>";
 echo "<tr><td align=left ><b>Tipo de documento</b></td>";
 echo "<td align=left >".$Row[wltipodocto]."</td>";
 echo "</td>";
 echo "</tr>";
 echo "<tr><td align=left ><b>Fecha del Documento AAAA-MM-DD</b></td>";
 echo "<td>".$Row[wlfechadocumento]."</td></tr>";
 echo "<tr><td align=left ><b>Fecha En que se recibio el documento AAAA-MM-DD</b></td>";
 echo "<td align=left>".$Row[wlfecharecibo]."</td></tr>";
 echo "<tr><td align=left ><b>Persona que envia el documento</b></td>";
 echo "<td align=left >".$Row[wlpersona_envia]."</td>";
 echo "<td>";
 echo "</td></tr>";

 echo "<tr><td align=left ><b>Persona que recibe el documento</b></td>";
 echo "<td align=left >".$Row[wlpersona_recibe]."</td>";
 echo "</tr>";
 echo "<tr></tr>";
 echo "<tr>";
 echo "<tr><td align=left ><b>Clave de Asunto</b></td>";
 echo "<td colspan=2>".$Row[wlid_cveasunto]."-".$Row[wlcveasunto]."</td>\n";
 echo "</tr>";
 echo "<tr>";
 echo "<tr><td align=left ><b>Asunto</b></td>";
 echo "<td colspan=2>".$Row[wlasunto]."</td>\n";
 echo "</tr>";
 echo "<tr>";
 echo "<td align=left ><b>Dias de termino</b></td>";
 echo "<td colspan=2>".$Row[diastermino]."</td>\n";
 echo "</tr>";
## echo "<tr>";
## echo "<td><input type=button name=Liberar value=Liberar onclick='liberar(".$wlfolioconsecutivo.")'</input></td>\n";
## echo "</tr>";
 echo "<input type=hidden name=wlfolioconsecutivo value='".$wlfolioconsecutivo."'</input>\n";
 echo "<input type=hidden name=wlopcion ></input>\n";
 echo "<input type=hidden name=wlidpuesto value='".$wlidpuesto."'></input>\n";
 echo "<input type=hidden name=wlidpersona value='".$wlidpersona."'></input>\n";
## echo "<tr>";
## echo "<td>";
## echo "<a href=# onclick=\"alta_referencia()\"  > Alta de una Referencia </a>";
## echo "</td>";
## echo "<td>";
## echo "<a href=# onclick=\"alta_turnara()\"  > Turnar a </a>";
## echo "</td>";
## echo "<td>";
## echo "<a href=# onclick=\"alta_adjuntara()\"  > Adjuntar Archivo </a>";
## echo "</td>";
## echo "</tr>";
## echo "</table>";

 echo "<div  id=div_altaref style='visibility:hidden;'>";
 echo "<table align=center width='100%'>";
 echo "<iframe id='fr_altaref' width='100%' frameborder=0  MARGINWIDTH=0 MARGINHEIGHT=0  >";
 echo "</iframe>"; 
 echo "</table>";
 echo "</div>";

 echo "<div  id=div_altatur style='visibility:hidden;' >";
 echo "<table align=center width='100%' >";
 echo "<iframe id='fr_altatur'  frameborder=0  width='100%' MARGINWIDTH=0 MARGINHEIGHT=0 >";
 echo "</iframe>"; 
 echo "</table>";
 echo "</div>";

 echo "</div>";
 echo "<div  id=div_altaarc style='visibility:hidden;' >";
 echo "<table align=center width='100%' >";
 echo "<iframe id='fr_altaarc'  width='100%' frameborder=0  MARGINWIDTH=0 MARGINHEIGHT=0 >";
 echo "</iframe>"; 
 echo "</table>";
 echo "</div>";
}

function arma_inicio()
{
echo "<title>Secretaria de Finanzas </title>";
echo "</head>";
echo "<BODY onload='inicio()'>";
}

function arma_inicio_close($wlfolioconsecutivo)
{
echo "<title>Secretaria de Finanzas </title>";
echo "</head>";
echo "<script type=\"text/javascript\" src=\"cookies.js\"></script>\n";
echo "<script type=\"text/javascript\" src=\"http.js\"></script>\n";
echo "<BODY onload='setCookie(\"folioconsecutivo\",".$wlfolioconsecutivo.");window.close()'>";
}

function arma_script()
{
echo "<script type=\"text/javascript\" src=\"cookies.js\"></script>\n";
echo "<script type=\"text/javascript\" src=\"valfecha_devo.php\"></script>\n";
echo "<script type=\"text/javascript\" src=\"utility.js\"></script>\n";
##echo "<script type=\"text/javascript\" src=\"http.js\"></script>\n";
echo "<script language=\"JavaScript\" type=\"text/javascript\">\n";
include("http.js");
echo "window.onerror = myOnError;\n";
echo "msgArray = new Array()\n";
echo "urlArray = new Array();\n";
echo "lnoArray = new Array();\n";
echo "function myOnError(msg, url, lno)\n";
echo "{   msgArray[msgArray.length] = msg;\n";
echo "   urlArray[urlArray.length] = url;\n";
echo "   lnoArray[lnoArray.length] = lno;\n";
echo "   alert('error ' + url + ' ' + lno + ' ' + msg);";
echo "   return true;";
echo "}";

echo "function inicio()\n";
echo "{\n";
##echo "    var f3=document.getElementById(\"termina\");\n";
##echo "    f3.style.visibility=0 ? \"visible\" : \"hidden\";\n";
echo "    muestra_referencia();\n";
echo "    muestra_turnara();\n";
echo "    muestra_adjuntara();\n";
echo "}\n";

echo "function otro_tramite()\n";
echo "{\n";
echo "    document.forms[0].wlopcion.value=\"\";\n";
echo "    document.forms[0].submit();\n";
echo "}\n";

echo "function muestrafolio()\n";
echo "{\n";
echo "    var f1=document.getElementById(\"excelso\");\n";
echo "    var f2=document.getElementById(\"registra\");\n";
echo "    var f3=document.getElementById(\"limpiar\");\n";
echo "    var f4=document.getElementById(\"termina\");\n";
echo "    var newtitle=\"Folio dado de alta \"+getCookie('folioconsecutivo');\n";
echo "    f1.firstChild.nodeValue=newtitle;\n";
echo "    f2.style.visibility=0 ? \"visible\" : \"hidden\";\n";
echo "    f3.style.visibility=0 ? \"visible\" : \"hidden\";\n";
echo "    f4.style.visibility=0 ? \"visible\" : \"\";\n";
echo "}\n";

echo "function alta_persona()\n";
echo "{\n";
echo "       window.showModalDialog(\"altapersona.php\",window,'dialogHeight:20');\n";
echo "       if (getCookie('idpersona')!=null) \n";
echo "   var re=/_/g\n";
echo "       {";
echo "       var a= new Option(getCookie(\"despersona\").replace(re,' ')+\" - \"+getCookie(\"idpersona\"),getCookie(\"idpersona\"),true,true);\n";
echo "       document.forms[0].wlidpersona_envia.options[document.forms[0].wlidpersona_envia.length]=a;\n";
echo "       }\n";
echo "}\n";

echo "function alta_personar()\n";
echo "{\n";
echo "       window.showModalDialog(\"altapersona.php\",window,'dialogHeight:20');\n";
echo "       if (getCookie('idpersona')!=null) \n";
echo "   var re=/_/g\n";
echo "       {\n";
echo "       var a= new Option(getCookie(\"despersona\").replace(re,' ')+\" - \"+getCookie(\"idpersona\"),getCookie(\"idpersona\"),true,true);\n";
echo "       document.forms[0].wlidpersona_recibe.options[document.forms[0].wlidpersona_recibe.length]=a;\n";
echo "       }\n";
echo "}\n";

echo "function alta_referencia()\n";
echo "{\n";
echo "   if (document.forms[0].wlfolioconsecutivo.value == '') {\n";
echo "        alert('primero tiene que dar de alta un tramite');\n";
echo "        document.forms[0].wlfolioconsecutivo.focus();\n";
echo "        return;\n";
echo "                         }\n";
echo "       window.showModalDialog(\"altareferencia.php?wlfolioconsecutivo=\"+document.forms[0].wlfolioconsecutivo.value,window,'dialogHeight:15');\n";
echo "       muestra_referencia();\n";
echo "}\n";

echo "function muestra_referencia()\n";
echo "{\n";
echo "       var wlurl='muestra_referencias.php?wlfolioconsecutivo='+document.forms[0].wlfolioconsecutivo.value;\n";
echo "       changeObjectVisibility('div_altaref','visible');";
echo "       var f3=document.getElementById('fr_altaref');";
echo "       f3.src=wlurl;";
echo "}\n";

echo "function alta_turnara()\n";
echo "{\n";
echo "   if (document.forms[0].wlfolioconsecutivo.value == '') {\n";
echo "        alert('primero tiene que dar de alta un tramite');\n";
echo "        document.forms[0].wlfolioconsecutivo.focus();\n";
echo "        return;\n";
echo "                         }\n";
echo "       window.showModalDialog(\"altaturnara.php?wlfolioconsecutivo=\"+document.forms[0].wlfolioconsecutivo.value,window,'dialogWidth:50;dialogHeight:20');\n";
echo "       muestra_turnara()\n";
echo "}\n";

echo "function muestra_turnara()\n";
echo "{\n";
echo "       var wlurl='muestra_turnara.php?wlfolioconsecutivo='+document.forms[0].wlfolioconsecutivo.value;\n";
echo "       changeObjectVisibility('div_altatur','visible');";
echo "       var f3=document.getElementById('fr_altatur');";
echo "       f3.src=wlurl;";
echo "}\n";

echo "function alta_adjuntara()\n";
echo "{\n";
echo "   if (document.forms[0].wlfolioconsecutivo.value == '') {\n";
echo "        alert('primero tiene que dar de alta un tramite');\n";
echo "        document.forms[0].wlfolioconsecutivo.focus();\n";
echo "        return;\n";
echo "                         }\n";
echo "       window.showModalDialog(\"altaadjuntara.php?wlfolioconsecutivo=\"+document.forms[0].wlfolioconsecutivo.value,window,'dialogWidth:60;dialogHeight:20');\n";
echo "       muestra_adjuntara()\n";
echo "}\n";

echo "function muestra_adjuntara()\n";
echo "{\n";
echo "       var wlurl='muestra_adjuntara.php?wlfolioconsecutivo='+document.forms[0].wlfolioconsecutivo.value;\n";
echo "       changeObjectVisibility('div_altaarc','visible');";
echo "       var f3=document.getElementById('fr_altaarc');";
echo "       f3.src=wlurl;";
echo "}\n";


echo "function validacampos()\n";
echo "{\n";
echo "   if (document.forms[0].wlid_tipodocto.selectedIndex == -1 || document.forms[0].wlid_tipodocto.selectedIndex == 0) {\n";
echo "        window.alert('No ha seleccionado un tipo de documento');\n";
echo "        document.forms[0].wlid_tipodocto.focus();\n";
echo "        return;\n";
echo "                         }\n";
echo "   if (valfecha(document.forms[0].wlfechadocumento.value,'Documento')==false) { document.forms[0].wlfechadocumento.focus(); return; }\n";
echo "   if (valfecha(document.forms[0].wlfecharecibo.value,'Recibio')==false) { document.forms[0].wlfecharecibo.focus(); return; }\n";
echo "   if (document.forms[0].wlfecharecibo.value < document.forms[0].wlfechadocumento.value) { \n";
echo "        window.alert('La fecha del documento es mayor que en la que se recibe el documento');\n";
echo "        document.forms[0].wlfechadocumento.focus();\n";
echo "        return;\n";
echo "                         }\n";

echo "   if (document.forms[0].wlidpersona_envia.selectedIndex == -1 || document.forms[0].wlidpersona_envia.selectedIndex == 0) {\n";
echo "        window.alert('No ha seleccionado la persona que envia el documento');\n";
echo "        document.forms[0].wlidpersona_envia.focus();\n";
echo "        return;\n";
echo "                         }\n";

echo "   if (document.forms[0].wlidpersona_recibe.selectedIndex == -1 || document.forms[0].wlidpersona_recibe.selectedIndex == 0) {\n";
echo "        window.alert('No ha seleccionado la persona que recibe el documento');\n";
echo "        document.forms[0].wlidpersona_recibe.focus();\n";
echo "        return;\n";
echo "                         }\n";

echo "   if (document.forms[0].wlasunto.value == '') {\n";
echo "        window.alert('No ha tecleado el asunto');\n";
echo "        document.forms[0].wlasunto.focus();\n";
echo "        return;\n";
echo "                         }\n";

echo "    document.forms[0].wlopcion.value='alta_tramite';\n";
echo "   var wlurl=\"".$_SERVER['PHP_SELF']."?wlid_tipodocto=\"+document.forms[0].wlid_tipodocto.value+\"&wlopcion=alta_tramite&wlidpersona_envia=\"+document.forms[0].wlidpersona_envia.value+\"&wlidpersona_recibe=\"+document.forms[0].wlidpersona_recibe.value+\"&wlfechadocumento=\"+document.forms[0].wlfechadocumento.value+\"&wlfecharecibo=\"+document.forms[0].wlfecharecibo.value+\"&wlasunto=\"+document.forms[0].wlasunto.value+\"&wlidpuesto=\"+document.forms[0].wlidpuesto.value+\"&wlidpersona=\"+document.forms[0].wlidpersona.value;\n";

echo "    window.showModalDialog(wlurl,window);\n";
echo "    if (getCookie('folioconsecutivo')!=null) \n";
echo "    {";
echo "       alert('se dio de alta el tramite con folio: '+getCookie('folioconsecutivo'));\n";
echo "       document.forms[0].wlfolioconsecutivo.value=getCookie('folioconsecutivo');\n";
##echo "       document.forms[0].wlfolioconsecutivo.focus();\n";
echo "       muestrafolio();\n"; 
echo "       var wlurl='muestra_turnara.php?wlfolioconsecutivo='+document.forms[0].wlfolioconsecutivo.value;\n";
echo "       changeObjectVisibility('div_altatur','visible');";
echo "       var f3=document.getElementById('fr_altatur');";
echo "       f3.src=wlurl;";
##echo "       document.writeln('Folio dado de alta'+getCookie('folioconsecutivo'));\n";
echo "       return;";
echo "    }\n";

echo "}\n";
echo "</script>\n";
}

function arma_forma()
{
echo "<H1 align=center>\n";
echo "</H1>\n";
echo "  <form method=post action=".$_SERVER['PHP_SELF'].">"; 
echo "  <table width=100% cellspacing=0 cellpadding=0 border=0 align=center> \n";
echo "  <caption align=center ><big><Strong> Tramitar</strong></big> </caption> \n";
}

function arma_fin()
{
echo "</font>\n";
echo "</table>\n";
echo "</BODY>\n";
echo "</HTML>\n";
}
   $connection = pg_connect("host=$servidor dbname=$bada user=$parametro1 password=$parametro2");
##   echo "wfolio".$;
   switch ($wlopcion)
   {
      case "":
           $Row=esta_ok($connection,$parametro1);
           arma_inicio();
           arma_script();
           arma_forma();
           soldatos($connection,$wlid_tipodocto,$wlidpersona_envia,$wlfechadocumento,$wlidpersona_recibe,$wlfecharecibo,$wlfolioconsecutivo,$wlasunto,$Row[id_puesto],$Row[id_persona]);
           arma_fin();
           break;
      case "alta_tramite":
           $wlfolioconsecutivo=alta_tramite($connection,$wlid_tipodocto,$wlidpersona_envia,$wlfechadocumento,$wlidpersona_recibe,$wlfecharecibo,$wlfolioconsecutivo,$wlasunto,$wlidpuesto,$wlidpersona);
           $nada=altaturnara($connection,1,'inicio',$wlfolioconsecutivo,$wlidpuesto,$wlidpersona,"N");
#dpuesto###           echo "wlfolio".$wlfolioconsecutivo;
           arma_inicio_close($wlfolioconsecutivo);
           break;
    }
?>
