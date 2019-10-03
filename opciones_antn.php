<?php
error_reporting(0);
echo "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\"";
echo "\"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">";
echo "<head>\n";
echo " <meta name='viewport' content='width=device-width'>\n";
echo "</head>\n";
echo "  <link type='text/css' rel='StyleSheet' href='pupan.css' media='(min-width:1024px)' />\n";
echo "  <link type='text/css' rel='StyleSheet' href='pupan_s.css' media='(max-width:481px)' />\n";
echo "  <link type='text/css' rel='StyleSheet' href='pupan_m.css' media='(min-width:481px) and (max-width:768px)' />\n";
echo "	<input type=hidden name=wlopcion value=\"$wlopcion\"></input>\n";
echo "	<script language=JavaScript>\n";
echo "	function salir()\n";
echo "	{	";
echo "	mensaje= window.confirm('Desea salir del sistema?' );\n";
echo "	if (mensaje){ 	";
##echo "		location.href='index.php';	";
##echo "		window.open ('index.php');	";
echo "                  parent.window.location.replace('index.php');";
echo "	}";
echo " else { return;}";
echo "}	\n";
echo "/**\n";
echo " * Resizes the given iFrame width so it fits its content\n";
echo " * @param e The iframe to resize\n";
echo " */\n";
echo "function resizeIframeWidth(e){\n";
echo "    // Set width of iframe according to its content\n";
echo "    if (e.Document && e.Document.body.scrollHeight) //ie5+ syntax\n";
echo "        e.style.height = e.contentWindow.document.body.scrollHeight;\n";
echo "    else if (e.contentDocument && e.contentDocument.body.scrollHeight) //ns6+ & opera syntax\n";
echo "        e.style.height = e.contentDocument.body.scrollHeight ;\n";
echo "    else if (e.contentDocument && e.contentDocument.body.offsetHeight) //standards compliant syntax . ie8\n";
echo "        e.style.height = e.contentDocument.body.offsetHeight ;\n";
echo "}\n";
echo "	</script>	\n";

class opciones_antn 
{
/**
    * Coneccion a la base de datos 
      */
   var $connection="";  
   function muestra_menus()
   {
            $sql="select id_tipomenu,current_user,menu from forapi.cat_usuarios where usename=current_user";
            $sql_result = @pg_exec($this->connection,$sql);
        	if (strlen(pg_last_error($this->connection))>0)
        	{
        				echo "<error>Error muestra_menu</error>";	        		
        				return;
        	}            
	  		$row=pg_fetch_array($sql_result, 0);                                          
			if ($row['id_tipomenu']==0)
			{   $this->menu0(); }
			if ($row['id_tipomenu']==1)
			{   $this->menu1($row['current_user']); }			
			if ($row['id_tipomenu']==2)
			{   $this->menu2($row['current_user']); }
			if ($row['id_tipomenu']==3)
			{   $this->menu3($row['current_user']); }
			if ($row['id_tipomenu']==4)
			{   $this->menu4($row['current_user']); }
			
			// se agrega para cargar la pantalla default del usuario
			$wlidmenu=$row['menu'];
			if ($wlidmenu>0)
			{
				echo "<script language='JavaScript' type='text/javascript'>\n";
				echo "	window.open ('man_menus.php?idmenu=".$wlidmenu."','pantallas');	";
				echo "</script>";
			}
			
			
   }
   function leemenus()
   {	 
		   $sql=
			"	select distinct m.descripcion, m.php, m.idmenupadre as a, m.idmenu".
                        "       ,(select count (*) from forapi.menus as mm where mm.idmenupadre=m.idmenu) as hijos	".
                        "       ,case when m.idmenupadre=0 then m.descripcion else (select descripcion from forapi.menus as mm where mm.idmenu=m.idmenupadre) end as orden ".
                        "       ,usename ".
			"	from	forapi.cat_usuarios_pg_group as cupg	".
			"	left join forapi.menus_pg_group as mpg on (mpg.groname=cupg.groname or mpg.grosysid=cupg.grosysid)	".
			"	left join forapi.menus as m on m.idmenu=mpg.idmenu	".
			"	where	usename=current_user	".
			"	and	m.descripcion<>'accesosistema'	".
			"	and not ((php='' or php is null) and (select count (*) from forapi.menus as mm where mm.idmenupadre=m.idmenu)=0) 	".
			"	order by 6,5 desc";
			//echo $sql."<br>";
    	   $sql_result = pg_exec($this->connection,$sql);
    	   if (strlen(pg_last_error($this->connection))>0)
    	   {	return   pg_last_error($this->connection);}
    	   else
    	   {	return   $sql_result;}
	}                   
	
	function menu0()
	{
     echo "<?xml version='1.0' encoding='ISO-8859-1'?>\n";
     echo "<?xml-stylesheet type=\"text/xsl\" href=\"XSLT/Menun.xsl\"?>\n";
     echo "<MENUS menu='Bienvenido ".$parametro1."'>\n";
     $sql_result=$this->leemenus();
     $num = pg_numrows($sql_result);
     for ($i=0; $i < $num ;$i++) {
         $Row = pg_fetch_array($sql_result, $i);
         $a = $Row[0];
	 echo "<MENU id='".$Row[3]."' idpadre='".$Row[2]."' secuencia='".$i.
              "'><FX titulo='".$a."' url='".$Row[1]."' idmenu='".$Row[3]."' target='pantallas' ></FX></MENU>\n";
     };
     echo "<MENU id='375' idpadre='0' secuencia='".$i."'>\n<FX titulo='salir'  target='_top' url='http:index.php' ></FX></MENU>";
     echo "</MENUS>\n";          
   	}
   	
	function menu1($wlusuario)
	{
    	echo "<html>\n";
    	echo "<BODY >\n";
	echo "<link type='text/css' rel='stylesheet' href='xtree.css' />\n";    
	echo "<link type='text/css' rel='stylesheet' href='print_xtree.css' MEDIA=print />\n";    		
    	echo "<script src='xtree.js'></script>\n";
    	echo "<script src='xloadtree.js'></script>\n";
        echo "<script>";  
        echo "  top.document.getElementById(\"izquierdo\").style.height=\"80%\";\n";
        echo "  top.document.getElementById(\"izquierdo\").style.width=\"25%\";\n";

        echo "  var div=top.document.getElementById(\"menus\");\n";
        echo "  var ifr=top.document.createElement(\"iframe\");\n";
        ##echo "  ifr.innerHTML=\" name='pantallas' \";\n";
        echo "  ifr.setAttribute('name','pantallas');\n";
        echo "  ifr.style.width=\"75%\";\n";
        echo "  ifr.style.height=\"100%\";\n";
        echo "  ifr.style.position=\"absolute\";\n";
        echo "  ifr.style.padding=\"0px\";\n";
        echo "  ifr.style.margin=\"0px\";\n";
        echo "  ifr.src='logo.php';\n";
        echo "  ifr.frameborder='0';\n";
        echo "  ifr.id='derecho';\n";
        echo "  div.appendChild(ifr);\n";
##        echo "  console.log('ifr.name='+ifr.name);\n";

/*
        echo "  var div=top.document.createElement(\"div\");\n";
        echo "  div.innerHTML=\"<iframe id='derecho' style='position:absolute; z-index:3; width:80%; height:100%; ' NAME='pantallas' frameborder='no' scrolling='auto' src='logo.php'></iframe>\";\n";
        echo "  top.document.body.appendChild(div);\n";
*/
/*
        echo "  var ifr=top.document.createElement(\"iframe\");\n";
        echo "  ifr.style.width=\"80%\";\n";
        echo "  ifr.style.height=\"100%\";\n";
        echo "  ifr.style.position=\"relative\";\n";
        echo "  ifr.src=\"logo.php\";\n";
        echo "  ifr.frameborder=\"0\";\n";
        echo "  ifr.id=\"derecho\";\n";
        echo "  ifr.name=\"pantallas\";\n";
        echo "  top.document.body.appendChild(ifr);\n";
*/
        echo "  resizeIframeWidth(top.document.getElementById(\"titulos\"));\n";

       ## echo "top.document.getElementById('fs').rows='';\n";	
##	echo "top.document.getElementById('fs').cols='20%,*';\n";	
	echo "webFXTreeConfig.rootIcon		= \"images/xp/folder.png\";\n";
	echo "webFXTreeConfig.openRootIcon	= \"images/xp/openfolder.png\";\n";
	echo "webFXTreeConfig.folderIcon		= \"images/xp/folder.png\";\n";
	echo "webFXTreeConfig.openFolderIcon	= \"images/xp/openfolder.png\";\n";
	echo "webFXTreeConfig.fileIcon		= \"images/xp/file.png\";\n";
	echo "webFXTreeConfig.lMinusIcon		= \"images/xp/Lminus.png\";\n";
	echo "webFXTreeConfig.lPlusIcon		= \"images/xp/Lplus.png\";\n";
	echo "webFXTreeConfig.tMinusIcon		= \"images/xp/Tminus.png\";\n";
	echo "webFXTreeConfig.tPlusIcon		= \"images/xp/Tplus.png\";\n";
	echo "webFXTreeConfig.iIcon			= \"images/xp/I.png\";\n";
	echo "webFXTreeConfig.lIcon			= \"images/xp/L.png\";\n";
	echo "webFXTreeConfig.tIcon			= \"images/xp/T.png\";\n" ;  
	echo "var tree = new WebFXLoadTree(\"Bienvenido ".$wlusuario."\", \"nuevo_menus.php\");\n";
	echo "document.write(tree);\n";
    echo "</script>\n";
    
    echo "<form id=forma></form>\n";
    echo "</body>";
    echo "</html>";
        		
	}   	
	
	function menu2($wlusuario)
	{
    	echo "<html>\n";
    	echo "<link type='text/css' rel='StyleSheet' href='winclassic.css' />";
    	echo "<BODY style=\"margin:0px;\" >\n";

    	echo "<script src='poslib.js'></script>\n";
    	echo "<script src='ieemu.js'></script>\n";
    	echo "<script src='scrollbutton.js'></script>\n";
		echo "<script src='menu4.js'></script>\n";
		
		echo "<script>";
		
    	echo "var menuBar = new MenuBar();\n";
    	echo "var sm = new Array();\n";
    	echo "var posicion='';\n";

    
     $sql_result=$this->leemenus();
     
     $num = pg_numrows($sql_result);
     if ($num=='')
     {	echo " window.alert ('".$sql_result."'); "; }
     $t=0;
     for ($i=0; $i < $num ;$i++) {
         $Row = pg_fetch_array($sql_result, $i);
         $a = $Row[0];
		if ($Row[2]==0)
		{
			$t=$t+1;
			$t=$Row[3];
			if (trim($Row[1])=="")
			{
    	    	            echo "sm[".$t."] = new Menu();";							
    	    	            echo "var testButton = new MenuButton('".$a."', sm[".$t."]);\n";
    			    echo "menuBar.add(testButton);\n";	
    			    echo "posicion+='|".$Row[3].",".$t."';\n";
			}
			else
			{
    	    	            echo "sm[".$t."] = new Menu();";							
    	    	            echo "var testButton = new MenuButton('".$a."', sm[".$t."]);\n";
    			    echo "menuBar.add(testButton);\n";	
	   		    echo " x = new MenuItem('".$a."' , '".$Row[1]."?idmenu=".$Row[3]."', null, null);\n";
	   		    echo " x.target='pantallas';\n";
	   		    echo "sm[".$t."].add(x);\n"; 	   			
                            
			}			
		}
	}

     for ($i=0; $i < $num ;$i++) {
         $Row = pg_fetch_array($sql_result, $i);
         $a = $Row[0];
		if ($Row[2]!=0)
		{
		    echo "tmp = new Menu;";
		    if(trim($Row[1])=="")
   		    { echo "sm[".$Row[2]."].add( new MenuItem('".$a."' , null, null, tmp) );"; }
   		    else
   		    { 
	   			echo " x = new MenuItem('".$a."' , '".$Row[1]."?idmenu=".$Row[3]."', null, null);\n";
	   			echo " x.target='pantallas';\n";
	   			echo "sm[".$Row[2]."].add(x);\n"; 	   			
	   	    }
		}
	} 
        echo "sm[sm.length] = new Menu();";
  	echo "var testButton = new MenuButton('Salir', sm[sm.length-1]);\n";
	echo "menuBar.add(testButton);\n";	    
	echo " x = new MenuItem('Salir' , 'index.php', null, null);\n";
	   	echo "sm[sm.length-1].add(x);\n"; 	   					
	 	echo " x.target='_top';\n";
    	echo "menuBar.write();\n";
        echo "  var nh=document.getElementById(\"-m-c-5\").scrollHeight;\n";
        echo "  top.document.getElementById(\"izquierdo\").style.height= (nh+10) + \"px\";\n";
        echo "  var div=top.document.createElement(\"div\");\n";
        echo "  div.innerHTML=\"<iframe id='derecho' style='position:relative; z-index:3; width:100%; height:100%; ' NAME='pantallas' frameborder='no' scrolling='auto' src='logo.php'></iframe>\";\n";
        echo "  top.document.body.appendChild(div);\n";
        echo "  resizeIframeWidth(top.document.getElementById(\"titulos\"));\n";
    	echo "</script>";
    	echo "</body>";
    	echo "</html>";
    	
    	
    	
    }
    
    function menu3($wlusuario)
	{
	        echo "	<script src='scripts.js' type='text/javascript' language='javascript'/>	</script>";
		echo "	<body onLoad='menu_init();' id='bodymenu' class='bodymenu'>\n";
		$sql_result=$this->leemenus();
		$num = pg_numrows($sql_result);
		echo "<table id='topmenu' class='topmenu'>\n";
		echo "	<tr>	";
		for ($i=0; $i < $num ; $i++)
		{
			$row = pg_fetch_array($sql_result, $i);
			if ($row['a']==0 && $row['php']=='')
			{
			$descripcion=$row['descripcion'];
			echo "<th id='".$row['idmenu']."' onclick='this.className=\"tableon\";menu_over(this,\"cat".$row['idmenu']."\");' onmouseover='this.className=\"tableon\";menu_over(this,\"cat".$row['idmenu']."\");' onmouseout='this.className=\"\";'>\n";
			echo "<a>&nbsp;".$descripcion."</a></th>\n";
			}
			if ($row['a']==0 && trim($row['php'])!='')
			{
			   $descripcion=$row['descripcion'];
			   echo "<th id='0' onmouseover='this.className=\"tableon\";menu_over(this,\"cat".$row['idmenu']."\");' onmouseout='this.className=\"\";'>\n";
			   echo "<a>&nbsp;".$descripcion."</a></th>\n";
		        }	

		}
		echo "	</tr>	";
		echo "</table>\n";
		$num = pg_numrows($sql_result);
		echo "<script language='javascript'>\n";
		echo "	 var menu_data = {\n";
			for ($i=0; $i < $num ; $i++)
			{
				$row = pg_fetch_array($sql_result, $i);
				if ($row['a']==0 && $row['php']=='')
				{
					echo "	cat".$row['idmenu']." : { items: [	";
					$num2 = pg_numrows($sql_result);
					for ($o=0; $o < $num2 ; $o++)
					{
						$row2 = pg_fetch_array($sql_result, $o);
						if ($row['idmenu']==$row2['a'])
						{
							echo "		{ title: '".$row2['descripcion']."', url: '".$row2['php']."?idmenu=".$row2['idmenu']."' },	\n";	
						}
					}
					echo "		{ title: 'Salir', url: 'index.php'  }\n";	
					echo "	] },\n";
				} elseif ($row['a']==0 && $row['php']!='')
				{
					echo "	cat".$row['idmenu']." : { items: [	";
					$num2 = pg_numrows($sql_result);
					for ($o=0; $o < $num2 ; $o++)
					{
						$row2 = pg_fetch_array($sql_result, $o);
						if ($row['idmenu']==$row2['idmenu'])
						{
							echo "		{ title: '".$row2['descripcion']."', url: '".$row2['php']."?idmenu=".$row2['idmenu']."' },	\n";
						}
					}
					echo "		{ title: 'Salir', url: 'index.php'  }\n";	
					echo "	] },\n";
					
				}
			}
				echo "	salir : { items: [ { title: 'Salir', url: 'index.php'  } ] }\n";
		echo "	} ;\n";
                echo "  var nh=document.getElementById(\"topmenu\").scrollHeight;\n";
                echo "  top.document.getElementById(\"izquierdo\").style.height= (nh+10) + \"px\";\n";
                echo "  var divf=top.document.createElement(\"div\");\n";
                echo "  divf.innerHTML=\"<iframe id='derecho' style='position:relative; z-index:3; width:100%; height:100%; ' NAME='pantallas' frameborder='no' scrolling='auto' src='logo.php'></iframe>\";\n";
                echo "  top.document.body.appendChild(divf);\n";
		echo "</script>	";
	}

    function menu4($wlusuario)
	{
	        ##echo "	<script src='ddmenu.js' type='text/javascript' language='javascript'/>	</script>";
                ##echo "  <link href='ddmenu.css' rel='stylesheet' type='text/css' />";
		echo "	<body >\n";
		$sql_result=$this->leemenus();
		$num = pg_numrows($sql_result);
                echo '<nav id="ddmenu">';
                echo '    <div class="menu-icon"></div>';
                echo '    <ul>';
		for ($i=0; $i < $num ; $i++)
		{
			$row = pg_fetch_array($sql_result, $i);
                        if ($row['orden']!=$wlorden)  // detecta cambio de orden o de columna
                        { 
                           $wlorden=$row['orden'];
                           if ($fli!=0)
                           {  
                              echo '</div></div></div>';
                              echo '</li>';
                           }
                           if ($row['hijos']!=0 && $row['php']=='')
                           {
                              $descripcion=$row['descripcion'];
                              echo '        <li class="full-width">';
                              echo '<span class="top-heading">'.$descripcion.'</span>';
                              echo '<i class="caret"></i>';
                              echo '<div class="dropdown">';
                              echo '<div class="dd-inner">';
                              echo '<div class="column">';
                              $fli=1;
                              continue;
                           }
                           if ($row['hijos']==0 && trim($row['php'])!='')
                           {
                              $descripcion=$row['descripcion'];
                              echo '<li class="no-sub"><a class="top-heading" href="http://www.google.com">'.$descripcion.'</a></li>';
                              $fli=0;
                              continue;
                           }
                        }
			if (trim($row['php'])!='' && $row['hijos']==0)
			{
			   $descripcion=$row['descripcion'];
                           echo '<li class="no-sub"><a target="pantallas" href="man_menus.php?menu='.$row['idmenu'].'">'.$descripcion.'</a></li>';
			}
		}
                        echo '</ul>';
		echo "</nav>\n";
                echo "<script>";
                ##echo "  var nh=document.getElementById(\"ddmenu\").scrollHeight;\n";
                ##echo "  top.document.getElementById(\"izquierdo\").style.height= (nh+10) + \"px\";\n";
                echo "  var divf=top.document.createElement(\"div\");\n";
                echo "  divf.setAttribute(\"class\",\"div_pantallas\")\n";
                echo "  divf.innerHTML=\"<iframe id='derecho' allowtransparency='yes' style=' width:100%; height:100%; ' NAME='pantallas' frameborder='no' scrolling='auto' src='logo.php'></iframe>\";\n";
                echo "  top.document.body.appendChild(divf);\n";
		echo "</script>	";
    }
    
}
		session_start();
		include("conneccion.php");
		$va = new opciones_antn();
		$va->connection = $connection;
		$va->muestra_menus();
?>
