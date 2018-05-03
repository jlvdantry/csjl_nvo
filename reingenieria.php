<?php
require_once("class_men.php");
class reingenieria
{
   var $tabla="";  /* tabla o sql */
   var $campost=array();  /* arreglo que contiene el tipo de cada uno de los campos de la tabla */
   var $camposl=array();  /* arreglo que contiene la len  de cada uno de los campos de la tabla */
   var $camposs=array();  /* arreglo que contiene si es una secuencia de cada uno de los campos de la tabla */
   var $camposk=array();  /* arreglo que contiene si el campo es un indice */
   var $connection="";  /* tabla o sql */
   function crea_menus()
   {
      $men = new class_men();
      $sql=" insert into menus(descripcion,tabla) ".
		   " select relname,relname".
           " from tablas ".
           " where relname = '".$this->tabla."' ";
      $sql_result = pg_exec($this->connection,$sql)
                    or die("No se pudo ejecutar el sql de insertar en menus".$sql);
      $num = pg_numrows($sql_result);

      $sql=" select currval('menus_idmenu_seq') ";
      $sql_result = pg_exec($this->connection,$sql)
                    or die("Error al querer tomar la secuencia ".$sql);
      $Row = pg_fetch_array($sql_result, 0);
      $wlseq=$Row[0];
      $sql=" insert into menus_campos(idmenu,descripcion,attnum,orden,tabla) ".
		   "select ".$wlseq.",attname,attnum,attnum+10,'".$this->tabla."' "
           " from campos ".
##20070713   se modifico para no incluir los campos de la base de datos           
##20070713           " where relname = '".$this->tabla."' ";
           " where relname = '".$this->tabla."' ".
           " and attnum > 0";  ##20070713
      $sql_result = pg_exec($this->connection,$sql)
                    or die("No se pudo ejecutar el sql de insertar en menus_campos".$sql);      
   }
   
   function crea_sqlarchivo()
   {
      $men = new class_men();
      $sql=" select 'insert into menus(descripcion,tabla) values (' || '''' || ".
		   "  relname || ''',''' || relname || ''');\n'".
           " from tablas ".
           " where relname = '".$this->tabla."' ";
      $sql_result = pg_exec($this->connection,$sql)
                    or die("No se pudo ejecutar el sql de insertar en menus".$sql);
      $num = pg_numrows($sql_result);
      if ( $num == 0 ) {$m->menerror("No Encontro la definicion de un menu "); die();};   
      $wlarchivo1=$this->tabla."m.sql";
      $handlew = fopen($wlarchivo1, "w");
      $Row=pg_fetch_array($sql_result, 0);
      if (!fwrite ($handlew,$Row[0])) {echo "error al grabar salida ".$wlarchivo1;}
      $sql=" select 'insert into menus_campos(idmenu ,descripcion,attnum,orden) values (\n".
		   " (select idmenu from menus where tabla=' || '''' || '$this->tabla' || '''' || ')".
		   ",''' ||  attname || ''',' || ".
		   " ' (select attnum from campos where relname=' || '''' || '$this->tabla' || '''' || ' and attname=' || '''' || attname || '''' || ')'".
		   " || ',' || attnum*10 || ');\n'".
           " from campos ".
           " where relname = '".$this->tabla."' ";
           
      $sql_result = pg_exec($this->connection,$sql)
                    or die("No se pudo ejecutar el sql de insertar en menus_campos".$sql);
      $num = pg_numrows($sql_result);
      if ( $num == 0 ) {$m->menerror("No Encontro campos en la tabla "); die();};                             	   
	  for ($z=0; $z < $num ;$z++)
      {
			$Row=pg_fetch_array($sql_result, $z);
      		if (!fwrite ($handlew,$Row[0])) {echo "error al grabar salida ".$wlarchivo1;}			
	  }			      
      fclose($handlew);	  
   }
   
}
?>
