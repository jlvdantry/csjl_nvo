<?php
   session_start() ;
   include("mensajes.php");
   include("conneccion.php");
function soldatos($connection,$wlidtipoarc,$wlobservacion,$wlfolioconsecutivo,$ficheroin)
{
 echo "<input type=hidden name=wlopcion ></input>\n";
 echo "<tr align=center><th>Ubicación del archivo</th></tr>	";
 echo "<tr align=center><td><input type=file size=100 name=ficheroin value=".$ficheroin."></input></td></tr>";
 echo "<input type=hidden name=wlfolioconsecutivo></input>";
##  echo "<tr><td colspan=2 align='center' ><input class='registrar' type=submit value=\"Alta de Adjuntar archivo\" name=registra onclick='validacampos();return false;'></input></td></tr>";
 echo "<tr><td align='center'><input type='image' src='img/add.gif' class='registrar' type=submit title=\"Adjuntar archivo\" name=registra onclick='if (validacampos()==false){return false;};'></input></td></tr>";
 echo "<tr><td align=center id=mensaje name=mensaje></td></tr>\n";
}
function altaadjuntara($connection,$ficheroin)
{

 $sql =" insert into menus_archivos (descripcion) values ('".$ficheroin."');";
 $sql_result = pg_exec($connection,$sql);
 if (strlen(pg_last_error($connection))>0) { die ("Error al ejecutar qry 1 ".$sql." ".pg_last_error($connection)); }
 
 $sql =" select currval(pg_get_serial_sequence('menus_archivos', 'idarchivo'));";
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
//echo "      alert('opener'+window.opener.document.forms[0].name);";
echo "   	if (wlopcion==\"\")\n";
echo "   	{\n";
echo "			window.opener.document.forms[0].wlfolioconsecutivo.value=idarchivo+'.'+extencion;\n}";
echo "	 	else\n{\n";
echo "			window.opener.document.forms[0].wlfolioconsecutivo.value=wlopcion;\n";
echo "   	}\n";
// agregue esta linea para forzar a cerrar la ventana sin solicitar confimacion para cerrarla 20101111
echo "		window.open('','_parent','');	";
echo "	 	window.close();\n";
echo "   }";
echo "   catch(err) { alert('error carga'+err.description); window.close(); }";
echo "}\n";
echo "</script>\n";
}
function arma_script_ini()
{
echo "<script language=\"JavaScript\" type=\"text/javascript\">\n";	
echo "  var t;\n";
echo "  var c=0;\n";
echo "function validacampos()\n";
echo "{\n";
echo "   var wlurl; \n";
echo "   if (document.forms[0].ficheroin.value == \"\" ) {\n";
echo "        window.alert('No se ha especificado la ubicación del archivo');\n";
echo "        document.forms[0].ficheroin.focus();\n";
echo "        return false;\n";
echo "                         }";
echo "   document.forms[0].wlfolioconsecutivo.value = \"\";\n";
echo "   document.forms[0].wlopcion.value = \"altaadjuntara\" ;\n";
echo "   document.getElementById(\"mensaje\").innerHTML=\"Transmitiendo archivo \";\n";
echo "   checaftp();\n";
##echo "   document.forms[0].submit();\n";

##echo "   alert('Despues del submit');\n";
##echo "   window.returnValue=document.forms[0].wlfolioconsecutivo.value;\n";
##echo "   self.close();\n";
echo "}\n";
echo "function checaftp()\n";
echo "{\n";
##echo "   alert('entro en checaftp');\n";
##echo "   windows.status='contador'+c;\n";
echo "   if (document.forms[0].wlfolioconsecutivo.value != \"\" ) {\n";
echo "        clearTimeout(t);";
##echo "         alert('sipi'+document.forms[0].wlfolioconsecutivo.value);\n";
echo "        if (document.forms[0].wlfolioconsecutivo.value.indexOf('Error')>=0)\n ";
echo "            { document.getElementById(\"mensaje\").innerHTML+=document.forms[0].wlfolioconsecutivo.value; \n";
echo "            } \n";
echo "        else { window.returnValue=document.forms[0].wlfolioconsecutivo.value;\n";
echo "               self.close();\n";
echo "             } \n";
echo "        return;\n";
echo "		}\n";
echo "   else\n";
echo "   {   c=c+1; ";
echo "  	document.getElementById(\"mensaje\").innerHTML+=\" . \";\n";
echo "   }";
echo "   t=setTimeout(\"checaftp()\",1000);\n";
echo "}\n";
echo "</script>\n";
}

function arma_fin()
{
echo "</font>";
echo "</table>";
echo "</form>";

echo "</BODY>";
echo "</HTML>";
}

function arma_inicio($wlcierra,$wlid_adjuntara,$wlnombre,$wlapepat,$title)
{
echo "<HTML>\n";
echo "<head>\n";
//echo "<LINK rel='styleSheet' HREF='estilo_contra.css' TYPE='text/css' />\n";
echo "<LINK rel='styleSheet' HREF='pupan.css' TYPE='text/css' />\n";
echo "<script src='broseaing.js'></script>\n";
echo "<title>".$title."</title>\n";
echo "</head>\n";
## echo "<BODY onLoad=carga('".$wlcierra."')>\n";
echo "<BODY >\n";
}

function arma_inicio_close($wlcierra,$wlid_adjuntara,$wlext)
{
echo "<HTML>\n";
echo "<head>\n";
//echo "<LINK rel='styleSheet' HREF='estilo_contra.css' TYPE='text/css' />\n";
echo "<LINK rel='styleSheet' HREF='pupan.css' TYPE='text/css' />\n";
echo "<title>Adjuntar archivo</title>\n";
echo "</head>\n";
echo "<BODY onLoad='carga(\"".$wlcierra."\",\"".$wlid_adjuntara."\",\"".$wlext."\");'>\n";
##echo "<BODY onLoad=\"alert('onload');\">\n";
}

function arma_forma($wlcierra,$wlid_adjuntara,$title)
{
echo "<br>";
echo "<H1 align=center>";
echo "</H1>";
echo "  <form name='carga' method=post action=".$_SERVER['PHP_SELF']." target='_self' enctype=\"multipart/form-data\" >";
echo "  <table width style=\"width:80%\" cellspacing=0 cellpadding=0 border=0 align=center> \n";
echo "  <caption align=center ><div class=titulo>".$title."</div>";
    echo "<div class=fecha>	";
    if ($_SESSION["parametro1"]!="")
    	{
    		echo "<input class=enca readonly size=30% align=center typr=text Id=\"wl_encausr\" value=\"Usuario: ".$_SESSION["parametro1"]."\"></input>";
		}
		echo "<input class=enca readonly size=40% align=center typr=text Id=\"wl_encafecha\"></input>";
    	echo "<input class=enca readonly size=20% align=center typr=text Id=\"wl_encahora\"></input>";
	echo "</div>";
echo "</caption>";
}
switch ($wlopcion)
{
case "":
   $title="Adjuntar archivo";
   arma_inicio("","1","1","1",$title);
   arma_script_ini();
   arma_forma("",0,$title);
   soldatos($connection,$wlidtipoarc,$wlobservacion,$wlfolioconsecutivo,$ficheroin);
   arma_fin();
   echo "	<script language=\"JavaScript\">";
   echo "	actualizaRelog ();	";
   echo "	</script>	";
   break;
case "altaadjuntara":

   if ($_FILES['ficheroin']['name']!=="")   
   {
##	   echo "entro";
      if(sizeof($_FILES)) 
      {
	      //echo "entro en size";
		if($_FILES['ficheroin']['size'] < 1) 
		{
			$error="Error El tamaño de archivo esta en ceros ";
		}
		$newname = ereg_replace("[^-.~[:alnum:]]", "",
			   $_FILES['ficheroin']['name']);

		$wlext=strtolower(substr($_FILES['ficheroin']['name'], strrpos($_FILES['ficheroin']['name'], '.') + 1)); 
	      //echo "entro en newname";		
		if (($wlext!="xls" && $wlext!="xlsx" && $wlext!="doc" && $wlext!="docx" && $wlext!="txt" && $wlext!="jpg" && $wlext!="pdf") && $error=="")

		$wlext=strtolower(substr($_FILES['ficheroin']['name'], strrpos($_FILES['ficheroin']['name'], '.') + 1)); 
	      //echo "entro en newname";		
		if (($wlext!="xls" && $wlext!="xlsx" && $wlext!="doc" && $wlext!="docx" && $wlext!="txt" && $wlext!="jpg" && $wlext!="pdf" && $wlext!="bmp" && $wlext!="zip" && $wlext!="gz" && $wlext!="tar"  && $wlext!="rar") && $error=="")

		{
			$error=" Error, Los archivos con extencion <b>.".$wlext."</b> no se permiten adjuntar";
		}
		if ($error=="")
		{ 
	      //echo "no error";				
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
      arma_inicio_close($error,$wlid_adjuntara,$wlext);
      arma_script();
      arma_fin();
      
      ##break;
##      echo "<script>window.close();</script>";
  } else
  {
  print_r($_FILES);
  menerror("no esta definida la variable ficheroin_name");
}
}
?>

