<?php
   session_start() ;
   include("mensajes.php");
   include("conneccion.php");

function altaadjuntara($connection,$ficheroin)
{
 $sql =" insert into forapi.menus_archivos (descripcion) values ('".$ficheroin."');";
 $sql_result = pg_exec($connection,$sql);
 if (strlen(pg_last_error($connection))>0) { die ("Error al ejecutar qry 1 ".$sql." ".pg_last_error($connection)); }
 $sql =" select currval(pg_get_serial_sequence('forapi.menus_archivos', 'idarchivo'));";
 $sql_result = pg_exec($connection,$sql);
 if (strlen(pg_last_error($connection))>0) { die ("Error al ejecutar qry 2 ".$sql." ".pg_last_error($connection)); }
 $Row = pg_fetch_array($sql_result, 0);
 $wlopcion="cerrar";
 return $Row[0];
}



function arma_script()
{
echo "<script language=\"JavaScript\" type=\"text/javascript\">\n";
echo "function carga(wlopcion,idarchivo,extencion)\n";
echo "{\n";
echo "   try\n";
echo "   {";
echo "          if (wlopcion==\"\")\n";
echo "          {\n";
echo "                  window.opener.document.getElementsByName('wlfolioconsecutivo')[0].value=idarchivo+'.'+extencion;\n}";
echo "          else\n{\n";
echo "                  window.opener.document.getElementsByName('wlfolioconsecutivo')[0].value=wlopcion;\n";
echo "          }\n";
echo "          window.open('','_parent','');   ";
echo "          window.close();\n";
echo "   }";
echo "   catch(err) { alert('error carga1'+err.description); window.close(); }";
echo "}\n";
echo "</script>\n";
}
echo "<script language='JavaScript' type='text/javascript'>\n";
echo "try {\n";
echo "var t;\n";
echo "var c=0;\n";
##function validacampos()
##{
##echo "   alert('entro a subir archivo');\n";
echo "   var wlurl; \n";
echo "   if (parent.document.getElementById('ficheroin').value == '' ) {\n";
echo "        window.alert('No se ha especificado la ubicación del archivo');\n";
echo "        parent.document.getElementById('ficheroin').focus();\n";
echo "   }\n";
#echo "   alert('despues de ficheroin');\n";
#echo "   parent.document.getElementById('_avance_').value = 'Transmitiendo archivo' ;\n";
#echo "   alert('despues de transmitiendo');\n";
#echo "   checaftp();\n";
echo "   } catch (err) { alert('error al entrar='+err.description); }\n";
##};

echo "function checaftp()\n";
echo "{\n";
echo "try {\n";
echo "   if (parent.document.getElementById('_avance_').value.indexOf('Transmitio')>=0 ||\n";
echo "       parent.document.getElementById('_avance_').value.indexOf('Error')>=0\n";
echo "      ) {\n";
echo "        clearTimeout(t);\n";
echo "        return;\n";
echo "  }\n";
echo "   else\n";
echo "   {   c=c+1;\n";
echo "       parent.document.getElementById('_avance_').value+='.';\n";
echo "   }\n";
echo "   t=setTimeout('checaftp()',1000);\n";
echo "   } catch (err) { alert('error en checaftp ='+err.description); }\n";
echo "}\n";
echo "</script>\n";
function arma_inicio_close($mensaje,$wlid_adjuntara,$wlext,$nombre)
{
echo "<script language=\"JavaScript\" type=\"text/javascript\">\n";
echo " try {\n";
echo " parent.document.getElementById('_avance_').value='".$mensaje."';\n";
##echo " parent.document.getElementById('_idar_').focus();\n";
echo " parent.document.getElementById('_idar_').value='".$wlid_adjuntara.".".$wlext.";".$nombre."';\n";
##echo " parent.document.getElementById('_avance_').focus();\n";
if ($wlid_adjuntara!='')
{  echo " parent.document.getElementById('_idar_').click(); \n"; }
echo " } catch (err) { alert('error en altaadjuntan.php ' + err.description); }\n";
echo "</script>";
}
      if(sizeof($_FILES))
      {
        if($_FILES['ficheroin']['size'] < 1)
        {
                $error="_El tamaño de archivo esta en ceros ".$_FILES['ficheroin']['size'] ;
        }
        if($_FILES['ficheroin']['size'] > 2000000)
        {
                $error="El tamaño del archivo es mayor a 1000000 caracteres ";
        }
        $newname = preg_replace("[^-.~[:alnum:]]", "", $_FILES['ficheroin']['name']);
        $wlext=strtolower(substr($_FILES['ficheroin']['name'], strrpos($_FILES['ficheroin']['name'], '.') + 1));
        if (($wlext!="xls" && $wlext!="xlsx" && $wlext!="doc" && $wlext!="docx" && $wlext!="txt" && $wlext!="jpg" && $wlext!="pdf") && $error=="")
                $wlext=strtolower(substr($_FILES['ficheroin']['name'], strrpos($_FILES['ficheroin']['name'], '.') + 1));
        if (($wlext!="xls" && $wlext!="xlsx" && $wlext!="doc" && $wlext!="docx" && $wlext!="txt" && $wlext!="jpg" && $wlext!="pdf" && $wlext!="bmp" && $wlext!="zip" && $wlext!="gz" && $wlext!="tar"  && $wlext!="rar" && $wlext!="key" && $wlext!="cer") && $error=="")
        {
                $error=" Error, Los archivos con extencion <b>.".$wlext."</b> no se permiten adjuntar ";
        }
        if ($error=="")
        {
                $wlid_adjuntara=altaadjuntara($connection,$_FILES['ficheroin']['name']);
                $dest = dirname(__FILE__)."/upload_ficheros/".$wlid_adjuntara.".".$wlext;
                if(!move_uploaded_file($ficheroin, $dest))
                {
                        $error="Error Hubo problemas al subir el archivo";
                }
                else
                {
                        chmod($dest, 0644);
                        $error="";
                }
        }
      }
      else
      {
                $error="Error No envio el archivo";
      }
      if ($error=="") $error="Transmitio el archivo";
      arma_inicio_close($error,$wlid_adjuntara,$wlext,$_FILES['ficheroin']['name']);
      echo "entro".print_r($_FILES)." no de archivo".$wlid_adjuntara;
?>

