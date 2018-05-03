<? session_start(); 
//	echo print_r($_SESSION);
//  20070403 optimizacion del sql ya que se tarda bastantito
//  20070601  Por alguna razon ya no se conectaba
//20070601  $connection = pg_connect("host=$servidor dbname=$bada user=$parametro1 password=$parametro2")
//20070601        or die("No se pudo establecer la Comunicacion. ");
	include('conneccion.php');
  if ($paragrupo!="ciudadano") {
    $sql = " select * from (".
           "select descripcion,php,case when idmenupadre is null then 0 else idmenupadre end as a, idmenu, hijos from ( ".
           " SELECT me.descripcion,me.php,".
           " (select me1.idmenu ".
           "         from menus as me1, menus_pg_group as me_pgg1, cat_usuarios_pg_group as cu_pgg1 ".
##20070403           "         where me1.idmenu=me_pgg1.idmenu and me_pgg1.grosysid=cu_pgg.grosysid and cu_pgg.usename='".$parametro1."'".
           "         where me1.idmenu=me_pgg1.idmenu and me_pgg1.grosysid=cu_pgg1.grosysid and cu_pgg1.usename='".$parametro1."'".
##20070403           "         and me.descripcion<>'accesosistema'".
           "         and me1.descripcion<>'accesosistema'".           
           "         and me1.idmenu=me.idmenupadre group by 1) as idmenupadre".
           " , me.idmenu ".
		   " ,(select count(*) from menus mss where me.idmenu=mss.idmenupadre and mss.idmenupadre<>mss.idmenu ".
		   "   and mss.descripcion<>'accesosistema') as hijos ".
           " from menus as me, menus_pg_group as me_pgg, cat_usuarios_pg_group as cu_pgg ".
           " where me.idmenu=me_pgg.idmenu and me_pgg.grosysid=cu_pgg.grosysid and cu_pgg.usename='".$parametro1."'".
           "       and me.descripcion<>'accesosistema' ".
           "  group by 1,2,3,4  order by 3,2,1) as orale ".
		   "  ) as ssddd ".
		   " where not ((php='' or php is null) ".
		   " and hijos=0) ";
     $sql_result = pg_exec($connection,$sql)
                   or die("No se pudo hacer el query. " );
     echo "<?xml version='1.0' encoding='ISO-8859-1'?>\n";
     echo "<?xml-stylesheet type=\"text/xsl\" href=\"XSLT/Menun.xsl\"?>\n";
     echo "<MENUS menu='Bienvenido ".$parametro1."'>\n";
     $num = pg_numrows($sql_result);
     for ($i=0; $i < $num ;$i++) {
         $Row = pg_fetch_array($sql_result, $i);
         $a = $Row[0];
	 echo "<MENU id='".$Row[3]."' idpadre='".$Row[2]."' secuencia='".$i.
              "'><FX titulo='".$a."' url='".$Row[1]."' idmenu='".$Row[3]."' target='pantallas' ></FX></MENU>\n";
     };
     echo "<MENU id='375' idpadre='0' secuencia='".$i."'>\n<FX titulo='salir'  target='_top' url='http:index.php' ></FX></MENU>";
     echo "</MENUS>\n";          }
  else {
     echo "<form method=GET action=traecobro.php name=cobros target=pantallas>\n";
     echo "<H1 align=left> OPCIONES  </H1>";
     echo "<input type=hidden name=cuenta size=20 ></input> ";
     echo "<INPUT TYPE='hidden' NAME='pago' >\n";
     $sql ="SELECT ctc.descripcion , ctcu.cuenta from cat_tiposcobrosusuarios ctcu, cat_tiposcobros ctc where ctcu.usename='".$parametro1."' and ctc.idtiposcobros=ctcu.idtiposcobros";
     $sql_result = pg_exec($connection,$sql)
                   or die("No se pudo hacer el query. " );
     $num = pg_numrows($sql_result);
     for ($i=0; $i < $num ;$i++){
         $Row = pg_fetch_array($sql_result, $i);
         $a = $Row[0];
         echo "<small><b><a target='pantallas' href=traecobro.php?cuenta=".$Row[1]."&pago=".$Row[0]."> ".$Row[0]." ".$Row[1]."</a></a></b></small>\n";
                                };
       };
                        ?>

