<? session_start() ?>
<HTML>
<head>
<? 
   echo " <LINK REL=StyleSheet HREF=\"estilo_siscor_gre.css\" TYPE=\"text/css\" MEDIA=screen>\n";
   include("reinicia.php"); 
   include("envia_mail_cp.php");
?>
<title>Secretaria de Finanzas </title>
</head>
<?
include ("mensajes.php");

function solusuario($wlusuario,$wlgrupo,$wlpassword)
{

if ($wlusuario=="")
   { echo "<BODY onload=\"document.forms[0].wlpasswordant.focus()\">\n"; }
else
   { echo "<BODY>\n"; }
       echo "  <table> \n";
       echo "<caption>Cambio de Password</caption>";
       echo "<tr><th colspan=3><hr></th></tr>	";
       echo " <form method=POST action=\"seg_cambiopass.php\">";
       echo " <tr><th width=70%>Teclee el Password anterior</th>	";
       echo "	  <td width=10%><input type=password name=wlpasswordant size=30 maxlength=30 value=".$wlpasswordant."></input></td>	";
       echo "	  <th></th></tr>\n";
       echo " <tr><th>Teclee el nuevo Password   </th><td><input type=password name=wlpasswordnvo size=30 maxlength=30 value=".$wlpasswordnvo."></input></td></tr>\n";
       echo " <tr><th>Teclee de nuevo el nuevo Password </th><td><input type=password name=wlpassworddos size=30 maxlength=30 value=".$wlpassworddos."></input></td></tr>\n";
       echo " <input type=hidden name=wlopcion ></input>\n";
       echo "<tr><th colspan=3><hr></th></tr>	";
       echo "<tr><th align=center><input type=submit value='Cambia password' name=matriz ".
            "onclick='validausuario(\"cambiapwd\");return false'></input></th></tr>\n";
       echo " </table>\n ";
}

?>
<script language="JavaScript" type="text/javascript">;
<?


echo "function validausuario(wlque)\n";
echo "{\n";
echo "if (document.forms[0].wlpasswordant.value == \"\") {\n";
echo "     window.alert(\"Primero debe de teclear el password anterior\");\n";
echo "     document.forms[0].wlpasswordant.focus();\n";
echo "     return;\n";
echo "                      }\n";
echo "if (document.forms[0].wlpasswordnvo.value == \"\") {\n";
echo "     window.alert(\"Primero debe de teclear el nuevo password \");\n";
echo "     document.forms[0].wlpasswordnvo.focus();\n";
echo "     return;\n";
echo "                      }\n";
echo "if (document.forms[0].wlpassworddos.value == \"\") {\n";
echo "     window.alert(\"Primero debe de teclear dos veces el nuevo password \");\n";
echo "     document.forms[0].wlpassworddos.focus();\n";
echo "     return;\n";
echo "                      }\n";
echo "if (document.forms[0].wlpassworddos.value != document.forms[0].wlpasswordnvo.value) {\n";
echo "     window.alert(\"El nuevo password es diferente \");\n";
echo "     document.forms[0].wlpasswordnvo.focus();\n";
echo "     return;\n";
echo "                      }\n";
echo "if (document.forms[0].wlpasswordant.value == document.forms[0].wlpasswordnvo.value) {\n";
echo "     window.alert(\"El nuevo password es igual que el anterior \");\n";
echo "     document.forms[0].wlpasswordnvo.focus();\n";
echo "     return;\n";
echo "                      }\n";
echo "    document.forms[0].wlopcion.value = wlque;\n";
echo "    document.forms[0].submit();\n";
echo "}\n";

echo "</script>\n";
//include("enca.php");
?>
  <?
   $connection = pg_connect("host=$servidor dbname=$bada user=$parametro1 password=$parametro2"); 

   switch ($wlopcion)
      {
      case "":
         solusuario($wlusuario,$wlgrupo,$wlpassword);
         break;
      case "cambiapwd":
         if ($parametro2!=$wlpasswordant) {
            solusuario($wlusuario,$wlgrupo,$wlpassword);
            menerror("El password anterior es Diferente");
            die(); }
         $connection = pg_connect("host=$servidor dbname=$bada user=$parametro1 password=$parametro2");
         $sql="select cambia_password('".$parametro1."','".$wlpasswordnvo."','".$wlpasswordant."');";
         $sql_result = pg_exec($connection,$sql) or die(menerror("Couldn't make query".$sql."opcion".$wlopcion));
         $Row = pg_fetch_array($sql_result, 0);
         if (strpos($Row[0], "ERROR")==0) {
            $wlmensaje=$Row[0];
            $sql="select correoe from cat_usuarios where usename='".$parametro1."';";
            $sql_result = pg_exec($connection,$sql) or die(menerror("Couldn't make query".$sql."opcion cambia password"));
            $Row = pg_fetch_array($sql_result, 0);
            if ($Row[0] != "") {
               envia_mail_cp($Row[0],"Su password fue cambiado exitosamente");
                            }
            reinicia($wlmensaje);}
         else {
            solusuario($wlusuario,$wlgrupo,$wlpassword);
            menerror($Row[0]);
              }
         break;
       }
   ?>
		</font>
                </table>
</BODY>
</HTML>
