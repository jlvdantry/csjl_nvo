<?php
session_start(); 
include "conneccion.php";		

function lee_menus($connection,$parametro1)
{
    $sql = " select * from (".
           "select descripcion,php,case when idmenupadre is null then 0 else idmenupadre end as a, idmenu, hijos from ( ".
           " SELECT me.descripcion,me.php,".
           " (select me1.idmenu ".
           "         from forapi.menus as me1, forapi.menus_pg_group as me_pgg1, forapi.cat_usuarios_pg_group as cu_pgg1 ".
           "         where me1.idmenu=me_pgg1.idmenu and me_pgg1.groname=cu_pgg1.groname and cu_pgg1.usename='".$parametro1."'".
           "         and me1.descripcion<>'accesosistema'". 
           "         and me1.idmenu=me.idmenupadre group by 1) as idmenupadre".
           " , me.idmenu ".
		   " ,(select count(*) from forapi.menus mss where me.idmenu=mss.idmenupadre and mss.idmenupadre<>mss.idmenu ". "   and mss.descripcion<>'accesosistema') as hijos ".
           " from forapi.menus as me, forapi.menus_pg_group as me_pgg, forapi.cat_usuarios_pg_group as cu_pgg ".
           " where me.idmenu=me_pgg.idmenu and me_pgg.groname=cu_pgg.groname and cu_pgg.usename='".$parametro1."'".
           "       and me.descripcion<>'accesosistema' ".
           "  group by 1,2,3,4  order by 1) as orale ".
		   "  ) as ssddd ".
		   " where not ((php='' or php is null) ".
		   " and hijos=0) ";
     $sql_result = pg_exec($connection,$sql)
                   or die("No se pudo hacer el query. " );	
     return $sql_result;
}

function arma_arbol0($nivel,$rows)     
{

	for ($i=0; $i<count($rows) ;$i++)	{
		if ($rows[$i]['a']==$nivel)
		{
			if (trim($rows[$i]['php'])!="")
			{			
				echo "<tree ".
				" text=\"".$rows[$i]['descripcion']."\"".
				" target=\"pantallas\"".
				" action=\"".$rows[$i]['php']."?idmenu=".$rows[$i]['idmenu']."\" />";
			}
			else
			{
				echo "<tree text=\"".$rows[$i]['descripcion']."\" >";
				arma_arbol1($rows[$i]['idmenu'],$rows);
				echo "</tree>";
			}
				
		}
	};
		echo "<tree ".
		" text=\"Salir\"".
		" action=\".\"".		
		" target=\"_top\" />";			
}


function arma_arbol1($nivel,$rows)     
{

	for ($i=0; $i<count($rows) ;$i++)	{
		if ($rows[$i]['a']==$nivel)
		{
			if ($rows[$i]['php']!="")
			{			
				echo "<tree ".
				" text=\"".$rows[$i]['descripcion']."\"".
				" target=\"pantallas\"".
				" action=\"".$rows[$i]['php']."?idmenu=".$rows[$i]['idmenu']."\" />";
			}
			else
			{
				echo "<tree text=\"".$rows[$i]['descripcion']."\" >";
				arma_arbol2($rows[$i]['idmenu'],$rows);
				echo "</tree>";
			}
				
		}
	};
	
}


function arma_arbol2($nivel,$rows)     
{

	for ($i=0; $i<count($rows) ;$i++)	{
		if ($rows[$i]['a']==$nivel)
		{
			if ($rows[$i]['php']!="")
			{			
				echo "<tree ".
				" text=\"".$rows[$i]['descripcion']."\"".
				" target=\"pantallas\"".
				" action=\"".$rows[$i]['php']."?idmenu=".$rows[$i]['idmenu']."\" />";
			}
			else
			{
				echo "<tree text=\"".$rows[$i]['descripcion']."\" >";
				arma_arbol3($rows[$i]['idmenu'],$rows);
				echo "</tree>";
			}
				
		}
	};
	
}
 
function arma_arbol3($nivel,$rows)     
{

	for ($i=0; $i<count($rows) ;$i++)	{
		if ($rows[$i]['a']==$nivel)
		{
			if (trim($rows[$i]['php'])!="")
			{			
				echo "<tree ".
				" text=\"".$rows[$i]['descripcion']."\"".
				" target=\"pantallas\"".
				" action=\"".$rows[$i]['php']."?idmenu=".$rows[$i]['idmenu']."\" />";
			}
			else
			{
				echo "<tree text=\"".$rows[$i]['descripcion']."\" >";
##				arma_arbol($rows[$i]['idmenu'],$rows);
				echo "</tree>";
			}
				
		}
	};
}

      
     $sql_result = lee_menus($connection,$_SESSION['parametro1']);
      header("Content-type: text/xml");
      header("Pragma: public");
      header("Cache-Control: must-revalidate, post-check=0, pre-check=0");     
     echo "<?xml version='1.0' encoding='ISO-8859-1'?>\n";
     echo "<tree>\n";
     $rows = pg_fetch_all($sql_result);
     arma_arbol0(0,$rows);     
     echo "</tree>\n"; 
?>

