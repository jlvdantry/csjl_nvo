<?php
echo "	<link type='text/css' rel='StyleSheet' href='pupan.css' />";    	
echo "	<input type=hidden name=wlopcion value=\"$wlopcion\"></input>	";
echo "	<script language=JavaScript>	";
echo "	function salir()";
echo "	{	";
echo "	mensaje= window.confirm('Desea salir del sistema?' );\n";
echo "	if (mensaje){ 	";
echo "		location.href='index.php';	";
echo "	}";
echo " else { return;}";
echo "}	";
echo "	</script>	";

class opciones_antn 
{
	/**
    * Coneccion a la base de datos 
    */
   var $connection="";  
   function muestra_menus()
   {
//	   		echo "entro en muestra_menus";
//	   		die();
            $sql="select id_tipomenu,current_user,menu from cat_usuarios where usename=current_user";
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
    /*$sql =
    		" select * from (".
           "select descripcion,php,case when idmenupadre is null then 0 else idmenupadre end as a, idmenu, hijos from ( ".
           " SELECT me.descripcion,me.php,".
           " (select me1.idmenu ".
           "         from menus as me1, menus_pg_group as me_pgg1, cat_usuarios_pg_group as cu_pgg1 ".
           "         where me1.idmenu=me_pgg1.idmenu and me_pgg1.grosysid=cu_pgg1.grosysid and cu_pgg1.usename=current_user".
           "         and me1.descripcion<>'accesosistema'".           
           "         and me1.idmenu=me.idmenupadre group by 1) as idmenupadre".
           " , me.idmenu ".
		   " ,(select count(*) from menus mss where me.idmenu=mss.idmenupadre and mss.idmenupadre<>mss.idmenu ".
		   "   and mss.descripcion<>'accesosistema') as hijos ".
           " from menus as me, menus_pg_group as me_pgg, cat_usuarios_pg_group as cu_pgg ".
           " where me.idmenu=me_pgg.idmenu and me_pgg.grosysid=cu_pgg.grosysid and cu_pgg.usename=current_user".
           "       and me.descripcion<>'accesosistema' ".
           "  group by 1,2,3,4  order by 1) as orale ".
		   "  ) as ssddd ".
		   " where not ((php='' or php is null) ".
		   " and hijos=0) order by 3,1";*/
		   //echo $sql."<br>";
		   // grecar 20101123 el qry anterior estaba un poco rebuscado
		   $sql=
			"	select distinct m.descripcion, m.php, m.idmenupadre as a, m.idmenu,(select count (*) from menus as mm where mm.idmenupadre=m.idmenu) as hijos	".
			"	from	cat_usuarios_pg_group as cupg	".
			"	left join menus_pg_group as mpg on mpg.grosysid=cupg.grosysid	".
			"	left join menus as m on m.idmenu=mpg.idmenu	".
			"	where	usename=current_user	".
			"	and	m.descripcion<>'accesosistema'	".
			"	and not ((php='' or php is null) and (select count (*) from menus as mm where mm.idmenupadre=m.idmenu)=0) 	".
			"	order by 3,1	";
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
##		echo "<link type='text/css' rel='stylesheet' href='pupan.css' />\n";    
    	echo "<script src='xtree.js'></script>\n";
    	echo "<script src='xloadtree.js'></script>\n";
		echo "<script src='xtree.js'></script>\n";  //20070703    		
		
    echo "<script >";  
    echo "top.document.getElementById('fs').rows='';\n";	
	echo "top.document.getElementById('fs').cols='20%,*';\n";	
	echo "webFXTreeConfig.rootIcon		= \"images/xp/folder.png\";";
	echo "webFXTreeConfig.openRootIcon	= \"images/xp/openfolder.png\";";
	echo "webFXTreeConfig.folderIcon		= \"images/xp/folder.png\";";
	echo "webFXTreeConfig.openFolderIcon	= \"images/xp/openfolder.png\";";
	echo "webFXTreeConfig.fileIcon		= \"images/xp/file.png\";";
	echo "webFXTreeConfig.lMinusIcon		= \"images/xp/Lminus.png\";";
	echo "webFXTreeConfig.lPlusIcon		= \"images/xp/Lplus.png\";";
	echo "webFXTreeConfig.tMinusIcon		= \"images/xp/Tminus.png\";";
	echo "webFXTreeConfig.tPlusIcon		= \"images/xp/Tplus.png\";";
	echo "webFXTreeConfig.iIcon			= \"images/xp/I.png\";";
	echo "webFXTreeConfig.lIcon			= \"images/xp/L.png\";";
	echo "webFXTreeConfig.tIcon			= \"images/xp/T.png\"; " ;  
	echo "var tree = new WebFXLoadTree(\"Bienvenido ".$wlusuario."\", \"nuevo_menus.php\");";
	echo "document.write(tree);";
    echo "</script>";
    
    echo "<form id=forma></form>";
    echo "</body>";
    echo "</html>";
        		
	}   	
	
	function menu2($wlusuario)
	{
    	echo "<html>\n";
##    	echo "<link type='text/css' rel='StyleSheet' href='pupan.css' />";
    	echo "<link type='text/css' rel='StyleSheet' href='winclassic.css' />";
    	echo "<BODY >\n";

    	echo "<script src='poslib.js'></script>\n";
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
			if ($Row[1]=="")
			{
    	    	echo "sm[".$t."] = new Menu();";							
    	    	echo "var testButton = new MenuButton('".$a."', sm[".$t."]);\n";
#	    		echo "testButton.mnemonic = 't';\n";
    			echo "menuBar.add(testButton);\n";	
    			echo "posicion+='|".$Row[3].",".$t."';\n";
			}
			else
			{
    	    	echo "sm[".$t."] = new Menu();";							
##    	    	echo "alert('showtimeout='+sm[".$t."].showTimeout);";							
    	    	echo "var testButton = new MenuButton('".$a."', sm[".$t."]);\n";
##	    		echo "testButton.mnemonic = 't';\n";
    			echo "menuBar.add(testButton);\n";	
    							
	   			echo " x = new MenuItem('".$a."' , '".$Row[1]."?idmenu=".$Row[3]."', null, null);\n";
	   			echo " x.target='pantallas';\n";
	   			echo "sm[".$t."].add(x);\n"; 	   			
			}			
		}
	}
//	echo "alert('longitud arreglo'+sm.length+' posicion='+posicion);\n";
	//echo "alert('cols de fs'+top.document.getElementById('fs').cols);";
//	echo "top.document.getElementById('fs').rows=top.document.getElementById('fs').cols;\n";
	echo "top.document.getElementById('fs').rows='6%,*';\n";	
	echo "top.document.getElementById('fs').cols='';\n";	
//    echo "alert('rows de fs'+top.document.getElementById('fs').rows);\n";	
//    echo "alert('cols de fs'+top.document.getElementById('fs').cols);\n";	    
     for ($i=0; $i < $num ;$i++) {
         $Row = pg_fetch_array($sql_result, $i);
         $a = $Row[0];
		if ($Row[2]!=0)
		{
##			echo "if (posicion.indexOf('| ".$Row[2]."')==0)\n";
##			echo "    alert('No encontro ".$Row[2]."');\n";
		    echo "tmp = new Menu;";
		    if($Row[1]=="")
   			{ echo "sm[".$Row[2]."].add( new MenuItem('".$a."' , null, null, tmp) );"; }
   			else
   			{ 
##	   			echo "sm[".$Row[2]."].add( new MenuItem('".$a."' , '".$Row[1]."?idmenu=".$Row[3]."', null, null) );"; 
	   			echo " x = new MenuItem('".$a."' , '".$Row[1]."?idmenu=".$Row[3]."', null, null);\n";
	   			echo " x.target='pantallas';\n";
	   			echo "sm[".$Row[2]."].add(x);\n"; 	   			
	   		}
		}
	} 
//	 	echo "alert('antes len'+sm.length);\n";
    echo "sm[sm.length] = new Menu();";
  	echo "var testButton = new MenuButton('Salir', sm[sm.length-1]);\n";
	echo "menuBar.add(testButton);\n";	    
//	echo "alert('despues'+sm.length);\n";								
	echo " x = new MenuItem('Salir' , 'index.php', null, null);\n";
//	 	echo " x.target='_top';\n";
//	 	echo "alert('antes len'+sm.length);
	   	echo "sm[sm.length-1].add(x);\n"; 	   					
	 	echo " x.target='_top';\n";
    	echo "menuBar.write();\n";
//    	echo "alert('height='+top.frames('derecho').document.body.clientHeight);\n";
//    	echo "alert('height='+document.body.clientHeight);\n";    	
    	echo "</script>";
    	echo "</body>";
    	echo "</html>";
    	
    	
    	
    }
    
    function menu3($wlusuario)
	{
		//echo "entro ".$wlusuario;	
		
		echo "	<LINK REL=StyleSheet HREF=\"style.css\" TYPE=\"text/css\" MEDIA=screen>";
		echo "	<link type='text/css' rel='StyleSheet' href='pupan.css' />";    	
		echo "	<script src='scripts.js' type='text/javascript' language='javascript'/>	";
		echo "	</script>	";
		
		echo "	<body onLoad='menu_init();'>	";
				
		$sql_result=$this->leemenus();
		// Menu con despliegue suave
		$num = pg_numrows($sql_result);
		echo "<table class='topmenu'>	";
		echo "	<tr>	";
		for ($i=0; $i < $num ; $i++)
		{
			$row = pg_fetch_array($sql_result, $i);
			if ($row['idmenupadre']==0 && $row['php']=='')
			{
			$descripcion=$row['descripcion'];
			echo "<th id='".$row['idmenu']."' onmouseover='this.className=\"tableon\";menu_over(this,\"cat".$row['idmenu']."\");' onmouseout='this.className=\"tableoff\";menu_out(this,\"cat".$row['idmenu']."\");'>	";
			echo "<a>&nbsp;".$descripcion."</a></th>	";
			}
			if ($row['a']==0 && trim($row['php'])!='')
			{
			$descripcion=$row['descripcion'];
			echo "<th id='0' onmouseover='this.className=\"tableon\";menu_over(this,\"cat".$row['idmenu']."\");' onmouseout='this.className=\"tableoff\";menu_out(this,\"cat".$row['idmenu']."\");'>	";
			echo "<a>&nbsp;".$descripcion."</a></th>	";
			}

		}
		//echo "<th onmouseover='this.className=\"tableon\";menu_over(this,\"salir\");' onmouseout='this.className=\"tableoff\";menu_out(this,\"salir\");'>	";
		//echo "<a>&nbsp;Salir</a></th>	";
		echo "	</tr>	";
		echo "</table>	";
		
		$num = pg_numrows($sql_result);
		//echo $num;

		// define el contenido del menu
		echo "<script language='javascript'>	";
		echo "	var menu_data = {	";

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
							echo "		{ title: '".$row2['descripcion']."', url: '".$row2['php']."?idmenu=".$row2['idmenu']."' },	";	
						}
					}
					echo "		{ title: 'Salir', url: 'index.php'  }	";	
					echo "	] },	 ";
				} elseif ($row['a']==0 && $row['php']!='')
				{
					echo "	cat".$row['idmenu']." : { items: [	";
					$num2 = pg_numrows($sql_result);
					for ($o=0; $o < $num2 ; $o++)
					{
						$row2 = pg_fetch_array($sql_result, $o);
						if ($row['idmenu']==$row2['idmenu'])
						{
							echo "		{ title: '".$row2['descripcion']."', url: '".$row2['php']."?idmenu=".$row2['idmenu']."' },	";	
						}
					}
					echo "		{ title: 'Salir', url: 'index.php'  }	";	
					echo "	] },	 ";
					
				}
			}
				echo "	salir : { items: [ { title: 'Salir', url: 'index.php'  } ] }	 ";
			
		echo "	} ;	";
		
		echo "</script>	";
		
		//frames ejemplo 1
		/*	echo "<script language='javascript'>	";
			echo "top.document.getElementById('fs').rows='6%,*';\n";	
			echo "top.document.getElementById('fs').cols='';\n";	
			echo "</script>	";*/
		
		//frames ejemplo 2
			// defino un iframe con el nombre de pantallas, para cargar los menus
			echo "	<br>";
			echo "	<iframe name=pantallas scrollbars=auto frameborder=no align=center width=100% height=94% src=\"logo.php\">	";
			echo "	</iframe>	";
			echo "</body>	";
			
			//defino para utilizar el frame top al 100%, ya que con el ejemplo 1, el contenido del menu desplegable se mostraba abajo del segundo frame
			echo "<script language='javascript'>	";
			echo "top.document.getElementById('fs').rows='';\n";	
			echo "top.document.getElementById('fs').cols='';\n";	
			echo "</script>	";
	}
    
}
		//session_start();
		include("conneccion.php");
		$va = new opciones_antn();
		$va->connection = $connection;
		$va->muestra_menus();
?>
