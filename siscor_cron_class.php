<?php
class siscor_cron
{
   var $connection="";
   function que_proceso()
   {
        $sql=" select * from soldatos where estado_proceso=0 order by fecha_alta limit 1 ";
	$sql_result = pg_exec($this->connection,$sql);
        $num = pg_numrows($sql_result);
        if ( $num != 0 )
	{
	   $Row = pg_fetch_array($sql_result, 0);
	   if ($Row["idproceso"]=1)
	   {
	       $this->descuadres($Row["fecha_ini"], $Row["fecha_fin"], $Row["atl_ini"], $Row["alt_fin"], $Row["idranpr"], $Row["id_soldatos"]);
	   }
        }
	else
	{
	   echo "no encontro procesos a procesar";
	}
   }

   function descuadres($wlfechaini,$wlfechafin,$wlatlini,$wlatlfin,$wlidranpr,$wlid_soldatos)
   {
        if ($wlidranpr!="")
	{
	    $this->empezando($this->connection,$wlid_soldatos);
	    $sql=" select rango from cat_ranpr where idranpr=".$wlidranpr;
	    $sql_result = @pg_exec($this->connection,$sql);
	    $this->hayerrorsql($this->connection,'Buscar Rango',$wlid_soldatos);
	    $Row = pg_fetch_array($sql_result, 0);
	    $this->borra_rangopr($this->connection,$wlfechaini,$wlfechafin,$Row["rango"],$wlid_soldatos);
	    echo "entro a descuadrar=".$Row["rango"];
	}
   }

   // checa si en el ultimo sql ejecutado de la coneecion hubo error
   function hayerrorsql($connection,$mensaje,$soldatos)
   {
        if (strlen(pg_last_error($connection))>0)
        {
	   $sql=" update soldatos set estado_proceso=4, observacion='".$mensaje." ".str_replace("\"","_",pg_last_error($connection))."'".
	        " where id_soldatos=".$soldatos;
           echo "sql armado hayerrorsql ".$sql;
	   $sql_result = @pg_exec($connection,$sql);
           return true ;
        }
	else
	{
	   return false;
        }
   }

   function borra_rangopr($connection,$wlfechaini,$wlfechafin,$rango,$wlid_soldatos)
   {
           $this->procesando($connection,$wlid_soldatos);
	   $sql=" delete from cobros_enca where atl  ".$rango.
	        " and fcobro between '".$wlfechaini."' and '".$wlfechafin."'".
		" and estado=4 ";
           echo "sql armado borra_rangopr ".$sql;
	    $sql_result = @pg_exec($connection,$sql);
           if ($this->hayerrorsql($connection,'Descuadrando dias',$wlid_soldatos)==false)
	   {
	       $wlreg=pg_affected_rows($sql_result);
               $this->terminook($connection,$wlid_soldatos,$wlreg);
	   }
   }

   function empezando($connection,$soldatos)
   {
	    $sql=" update soldatos set estado_proceso=5,fecha_empezo=current_timestamp where id_soldatos=".$soldatos;
	    $sql_result = @pg_exec($this->connection,$sql);
   }

   function procesando($connection,$soldatos)
   {
	    $sql=" update soldatos set estado_proceso=1 where id_soldatos=".$soldatos;
	    $sql_result = @pg_exec($connection,$sql);
   }
   function terminook($connection,$soldatos,$wlreg)
   {
	    $sql=" update soldatos set estado_proceso=3,fecha_termino=current_timestamp,registros=".$wlreg.
	         " where id_soldatos=".$soldatos;
	    $sql_result = @pg_exec($connection,$sql);
   }

}

