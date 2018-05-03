<?php
require_once("class_men.php");
require_once("xmlhttp_class.php");
class reingenieria extends xmlhttp_class
{
   var $tabla="";  /* tabla o sql */
   var $nspname="";  /* esquema   20070818 */   
   var $campost=array();  /* arreglo que contiene el tipo de cada uno de los campos de la tabla */
   var $camposl=array();  /* arreglo que contiene la len  de cada uno de los campos de la tabla */
   var $camposs=array();  /* arreglo que contiene si es una secuencia de cada uno de los campos de la tabla */
   var $camposk=array();  /* arreglo que contiene si el campo es un indice */
   var $connection="";  /* tabla o sql */
   function copiaopcion()
   {
		if ($this->argumentos["wl_idmenu"]=="")
    	{
    	  echo "<error>No esta definido el numero de menu</error>";
    	  return; 
    	}			   
       $sql=" select copiamenu(".$this->argumentos["wl_idmenu"].");";

       $sql_result = pg_exec($this->connection,$sql);
       if (strlen(pg_last_error($this->connection))>0) { echo "<error>Error al ejecutar qry ".$sql." ".pg_last_error($this->connection)."</error>"; }
                    //or die("No se pudo ejecutar el sql de insertar en menus".$sql);               	
     	echo "<error>Se copio todo el menu ".$wlidmenu."</error>";                                
       	    	
   }   
   function crea_menusbase()
   {

      $men = new class_men();
	  $this->tabla=$this->argumentos['wl_relname'];      
	  $this->nspname=$this->argumentos['wl_nspname'];      	  
      $sql=" insert into menus(descripcion,tabla,reltype,php,nspname) ".
		   " select relname,relname,reltype,'man_menus.php',nspname".
           " from tablas ".
           " where relname = '".$this->tabla."' ".
           " and nspname = '".$this->nspname."'";
      $sql_result = pg_exec($this->connection,$sql)
                    or die("No se pudo ejecutar el sql1 en menus");
                    //or die("No se pudo ejecutar el sql1 en menus: ".$sql." ".pg_last_error($this->connection));
      $sql=" select currval('menus_idmenu_seq');";
      $sql_result = pg_exec($this->connection,$sql)
                    or die("No se pudo ejecutar el sql2 en menus");
                    //or die("No se pudo ejecutar el sql2 en menus: ".$sql." ".pg_last_error($this->connection));
	  $row=pg_fetch_array($sql_result, 0);                    
      $wlidmenu=$row["currval"];
###      echo "<error>idmenu=".print_r($row)."</error>";	  
##      echo "<error>idmenu=".$wlidmenu."</error>";	  
##      return;	  	   	
      $sql= "select * ".
		   " from campos ".           
##20070713  se modifico para que no incluyera los campos del sistema si acaso el oid		   
##20070713           " where relname = '".$this->tabla."' ";
           " where relname = '".$this->tabla."' ".
           " and nspname = '".$this->nspname."'".   //20070818           
           " and attnum > 0 ";                      
      $sql_result = pg_exec($this->connection,$sql)
                    or die("No se pudo ejecutar el sql3 en menus");
                    //or die("No se pudo ejecutar el sql3 en menus: ".$sql." ".pg_last_error($this->connection));
      $num = pg_numrows($sql_result);
      if ( $num == 0 ) {$men->menerror("No tiene campos la tabla  ".$this->tabla); die();};          
	  for ($z=0; $z < $num ;$z++)
      {
			$row=pg_fetch_array($sql_result, $z);      
			$sql="insert into menus_campos(idmenu ,descripcion,attnum,orden,obligatorio,readonly,esindex,tipayuda,size,tabla,nspname,male) values (\n".
                  $wlidmenu.
                  ",'".$row["attname"]."'".
                  ",'".$row["attnum"]."'".                  
                  ",'".($row["attnum"]*10)."'".                                    
                  ",".($row["attnotnull"]=='t' ? 'true' : 'false').
		  ",".($row["atthasdef"]=='t' && $row["valor_default"]!='0' ? 'true' : 'false').
                  ",".($row["indice"]>=1 ? 'true' : 'false').
                  ",'".$row["descripcion"]."'".                  
                  ",".$this->dame_size($row).
                  ",'".$this->tabla."'".                  
                  ",'".$row["nspname"]."'".                  
                  ",'".$row["atttypmod"]."'".                  
                  ");\n";
      		$sql_resulti = pg_exec($this->connection,$sql)
            		        or die("No se pudo ejecutar el sql4 en menus");
            		        //or die("No se pudo ejecutar el sql4 en menus: ".$sql." ".pg_last_error($this->connection));
	  }	
##20070713	  Lo modifique para que toma el group admon por la descripcion
##20070713	  $sql="insert into menus_pg_group(idmenu ,grosysid) values (\n".
##20070713           $wlidmenu.",101".	  
##20070713                  ");\n";
	  $sql="insert into menus_pg_group(idmenu ,grosysid) select ".$wlidmenu.",grosysid from pg_group where groname='admon'";
      $sql_resulti = pg_exec($this->connection,$sql)
      				or die("No se pudo ejecutar el sql5 en menus");
      				//or die("No se pudo ejecutar el sql5 en menus: ".$sql." ".pg_last_error($this->connection));
       		        
      echo "<error>Inserto en la base la tabla ".$this->tabla."</error>";	  
   }
   
   function baja_admon()
   {
	  $this->tabla=$this->argumentos['wl_relname'];
      $men = new class_men();
      $sql=" select 'insert into menus(".
  		   " idmenu ".
  		   ",descripcion ".
  		   ",objeto ".
  		   ", fecha_alta ".
  		   ", usuario_alta ".
  		   ", fecha_modifico ".
  		   ", usuario_modifico ".
  		   ", php ".
  		   ", modoconsulta ".
  		   ", idmenupadre ".
  		   ", idmovtos ".
  		   ", movtos ".
  		   ", fuente ".
  		   ", presentacion ".
  		   ", columnas ".
  		   ", tabla ".
  		   ", reltype ".
  		   ", filtro ".
  		   ", limite ".
  		   ", orden ".
  		   ", menus_campos ".
  		   ", dialogWidth ".
  		   ", dialogHeight ".
  		   ", s_table ".
  		   ", s_table_height ".
      	   " )\n values ( '".
		   " || idmenu \n".
  		   " || ',' || '''' || coalesce(descripcion,'') || '''' \n".
  		   " || ',' || '''' || coalesce(objeto,'')      || '''' \n".
  		   " || ',' || '''' || fecha_alta || '''' \n".
  		   " || ',' || '''' || usuario_alta || ''''  \n".
  		   " || ',' || '''' || fecha_modifico || ''''  \n".
  		   " || ',' || '''' || usuario_modifico || ''''  \n".
  		   " || ',' || '''' || coalesce(php	,'''') || ''''  \n".
  		   " || ',' || coalesce(modoconsulta,0) \n".
  		   " || ',' || coalesce(idmenupadre,0) \n".
  		   " || ',' || coalesce(idmovtos,0) \n".
  		   " || ',' || '''' || coalesce(movtos,'') || ''''  \n".
  		   " || ',' || '''' || coalesce(fuente,'') || ''''  \n".
  		   " || ',' || coalesce(presentacion,0) \n".
  		   " || ',' || coalesce(columnas,0) \n".
  		   " || ',' || '''' || coalesce(tabla,'') || ''''  \n".
##  		   " || ',' || coalesce(reltype,0) \n".
  		   " || '\n,(select t.reltype from tablas as t where t.relname=''' || menus.tabla || ''')' \n".
  		   " || ',' || '''' || coalesce(filtro,'') || ''''  \n".
  		   " || ',' || coalesce(limite,0) \n".
  		   " || ',' || '''' || coalesce(orden,'') || ''''  \n".
  		   " || ',' || coalesce(menus_campos,0) \n".
  		   " || ',' || coalesce(dialogWidth,0) \n".
  		   " || ',' || coalesce(dialogHeight,0) \n".
  		   " || ',' || coalesce(s_table,0) \n".
  		   " || ',' || coalesce(s_table_height,0) \n".
      	   " || ');\n'".		   
           " from menus where idmenu>998";
      $sql_result = pg_exec($this->connection,$sql)
                    or die("No se pudo ejecutar el sql de insertar en menus".$sql);
      $num = pg_numrows($sql_result);
      if ( $num == 0 ) {$men->menerror("No Encontro la definicion de un menu ".$sql); die();};   
      $wlarchivo1=$this->tabla."m.sql";
      $handlew = fopen($wlarchivo1, "w");
	  for ($z=0; $z < $num ;$z++)
      {      
      		$Row=pg_fetch_array($sql_result, $z);
      		if (!fwrite ($handlew,$Row[0])) {echo "error al grabar salida ".$wlarchivo1;}
	  }      		


##  		$sql="select * from menus_campos ";

      $sql=" select 'insert into menus_campos(".
##  		   "  idcampo 		     ".
  		   " idmenu 		      ".
  		   ", reltype  		    ".
  		   ", attnum 		      ".
  		   ", descripcion 	  ".
  		   ", size 			       ".
  		   ", male 			       ".
  		   ", fuente 		      ".
  		   ", fuente_campodes".
  		   ", fuente_campodep".
  		   ", fuente_campofil".
  		   ", fuente_where 			".
  		   ", fuente_evento 			".
  		   ", orden 			        ".
  		   ", idsubvista 		    ".
  		   ", dialogwidth 		   ".
  		   ", dialogheight  	  ".
  		   ", obligatorio 		   ".
  		   ", busqueda 			     ".
  		   ", altaautomatico 	 ".
  		   ", tcase 			        ".
  		   ", checaduplicidad 	".
  		   ", readonly 			     ".
  		   ", valordefault 			".
  		   ", esindex 	".
  		   ", tipayuda 	 ".
##  		   ", fecha_alta 			        ".  		   
##  		   ", usuario_alta 	".  		   
##  		   ", fecha_modifico 			     ".  		   
##  		   ", usuario_modifico 			".   		   
  		   ", espassword 	".          		     		   
      	   " )\n values (' ".
##		   " || 'coalesce((select c.attnum from campos c where c.reltype = (select t.reltype from tablas as t where t.relname=''' || me.tabla || ''')'".
##		   " || ' and c.attname = ''' || (select t.attname from campos as t where t.relname=me.tabla and t.attnum=mc.attnum) || '''),-2)'".
		   " || '\n(select idmenu from menus c where idmenu>998 and c.descripcion = ''' || me.descripcion || ''')'".
##		   " || '\n,(select c.attnum from campos c where c.reltype = (select t.reltype from tablas as t where t.relname=''' || me.tabla || '''))'".
  		   " || '\n,(select t.reltype from tablas as t where t.relname=''' || me.tabla || ''')' \n".
		   " || '\n,coalesce((select c.attnum from campos c where c.reltype = (select t.reltype from tablas as t where t.relname=''' || me.tabla || ''')'".
		   " || ' and c.attname = ''' || (select t.attname from campos as t where t.relname=me.tabla and t.attnum=mc.attnum) || '''),-2)'".
  		   " || '\n,' || '''' || coalesce(mc.descripcion,'') || '''' \n".
  		   " || ',' || coalesce(size,0) \n".
  		   " || ',' || coalesce(male,0) \n".
  		   " || ',' || '''' || coalesce(mc.fuente,'') || '''' \n".
  		   " || ',' || '''' || coalesce(mc.fuente_campodes,'') || '''' \n".
  		   " || ',' || '''' || coalesce(mc.fuente_campodep,'') || '''' \n".
  		   " || ',' || '''' || coalesce(mc.fuente_campofil,'') || '''' \n".
  		   " || ',' || '''' || coalesce(mc.fuente_where,'') || '''' \n".
  		   " || ',' || coalesce(mc.fuente_evento,0) \n".
  		   " || ',' || coalesce(mc.orden,0) \n".
  		   " || ',' || coalesce(mc.idsubvista,0) \n".
  		   " || ',' || coalesce(mc.dialogwidth,0) \n".
  		   " || ',' || coalesce(mc.dialogheight,0) \n".
  		   " || ',' || case when mc.obligatorio then '''t''' else '''f''' end \n".
  		   " || ',' || case when mc.busqueda then '''t''' else '''f''' end \n".
  		   " || ',' || case when mc.altaautomatico then '''t''' else '''f''' end \n".
  		   " || ',' || coalesce(mc.tcase,0) \n".
  		   " || ',' || case when mc.checaduplicidad then '''t''' else '''f''' end \n".
  		   " || ',' || case when mc.readonly then '''t''' else '''f''' end \n".
  		   " || ',' || '''' || coalesce(mc.valordefault,'') || '''' \n".
  		   " || ',' || case when mc.esindex then '''t''' else '''f''' end \n".
  		   " || ',' || '''' || coalesce(mc.tipayuda,'') || '''' \n".
##  		   " || ',' || '''' || mc.fecha_alta || '''' \n".
##  		   " || ',' || '''' || coalesce(mc.usuario_alta,'') || '''' \n".
##  		   " || ',' || '''' || mc.fecha_modifico || '''' \n".
##  		   " || ',' || '''' || coalesce(mc.usuario_modifico,'') || '''' \n".
  		   " || ',' || coalesce(mc.espassword,0) \n".
      	   " || ');\n'".		   
           " from menus_campos mc, menus me ".
           " where mc.idmenu=me.idmenu  and mc.idmenu>998 order by me.descripcion ";
           
           
      $sql_result = pg_exec($this->connection,$sql)
                    or die("No se pudo ejecutar el sql de insertar en menus".$sql);
      $num = pg_numrows($sql_result);
      if ( $num == 0 ) {$m->menerror("No tiene campos la tabla  "); die();};          
	  for ($z=0; $z < $num ;$z++)
      {
			$Row=pg_fetch_array($sql_result, $z);      
      		if (!fwrite ($handlew,$Row[0])) {echo "error al grabar salida ".$wlarchivo1;}			
	  }			      

      fclose($handlew);	                          
      echo "<error>creo el archivo ".$wlarchivo1."</error>";
   }
   function dame_size($row)
   {
	       $wlsize="0";
               switch ($row["attlen"])
                      {
	                      case -1:
	                      	$wlsize=(($row["atttypmod"]>30) ? 30 : $row["atttypmod"]);
	                      	break;
	                      case 1:
	                      	$wlsize="1";
	                      	break;
	                      case 2:
	                        $wlsize="4";
	                      	break;	                            
	                      case 4:
	                        $wlsize="8";
	                      	break;	                            
	                      case 8:
	                        $wlsize="18";
	                      	break;
	                      default:
	                        $wlsize="8";
              	    }
	   return $wlsize;
   }
}
	if (isset($opcion))
	{
		session_start();
		include("conneccion.php");
		require_once("reingenieria_class.php");
		$va = new reingenieria();
		$va->connection = $connection;
##20071105      se cambio a post
##20071105		$va->argumentos = $_GET;
		$va->argumentos = $_POST; ##20071105
		$va->funcion = $opcion;		
		$va->procesa();		
	}
?>
