<?php
require_once("class_men.php");
class metadata
{
   var $tabla="";  /* tabla o sql */
   var $campost=array();  /* arreglo que contiene el tipo de cada uno de los campos de la tabla */
   var $camposl=array();  /* arreglo que contiene la len  de cada uno de los campos de la tabla */
   var $camposs=array();  /* arreglo que contiene si es una secuencia de cada uno de los campos de la tabla */
   var $camposk=array();  /* arreglo que contiene si el campo es un indice */
   var $connection="";  /* tabla o sql */
   function damemetadata()
   {
      $mensajes = new class_men();
      $sql="select pg_attribute.attname, pg_type.typname, pg_type.typlen, pg_type.typbyval".
##           " ,(select pgi.indkey from pg_index pgi where pg_class.oid = pgi.indrelid and indisprimary=true) as indice".
           " ,(select 't' from pg_index pgi where pg_class.oid = pgi.indrelid ".
           "   and indisunique=true and (indkey[0]=attnum or indkey[1]=attnum or indkey[2]=attnum or indkey[3]=attnum)) as indice".
           " from pg_class ".
           " , pg_attribute, pg_type ".
           " where pg_class.relname = '".$this->tabla."' ".
           " and pg_class.oid = pg_attribute.attrelid and pg_type.oid = pg_attribute.atttypid having attnum > 0 ";
      $sql_result = pg_exec($this->connection,$sql)
                    or die("Couldn't make query. ".$sql );
      $num = pg_numrows($sql_result);
      if ( $num == 0 ) {menerror("No hay rangos "); };
      for ($i=0; $i < $num ;$i++)
      {
         $Row = pg_fetch_array($sql_result, $i);
         $this->campost[$Row[0]]=$Row[1];
         $this->camposl[$Row[0]]=$Row[2];
         $this->camposs[$Row[0]]=$Row[3];
         $this->camposk[$Row[0]]=$Row[4];
      }

   }
}
?>
