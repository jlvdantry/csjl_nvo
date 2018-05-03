<?
session_start();
 ?>

<HTML>
<head>
<title>Siscor</title>
</head>
<?
require "mensajes.php";
$connection = @pg_connect("host=$servidor
                          dbname=$bada
                          user=$parametro1p
                          password=$parametro2p");
if ( $connection == "" ) {
     unset($parametro2);
     unset($parametro1);
     unset($parametro2f);
     unset($parametro1f);
     unset($paragrupo);
##   se conecta con el usuario jlv para registrar que usuario esta intentando conectarse y no puede
     $connection = @pg_connect("host=$servidor dbname=$bada user='jlv' password='jlv024'");
     if ($connection != "") {
        $sql="select grababitacora(0, 23, 0,0,current_timestamp,current_timestamp,'usuario="
             .$parametro1p." pwd=".$parametro2p."')";
        $sql_result = pg_exec($connection,$sql);
                            }
     die (menerror("No se pudo conectar a el sistema con el usuario ".$parametro1p)); 
                         }
$sql ="SELECT usesuper, cu.estatus from pg_shadow pgs, cat_usuarios cu where pgs.usename='"
	.$parametro1p."'"." and passwd = '".$parametro2p."'".
      " and pgs.usename =cast(cu.usename as name)";
$sql_result = pg_exec($connection,$sql)
              or die(menerror("Error al ejecutar sql ".$sql));
$num = pg_numrows($sql_result);
if ( $num == 0 ) 
   {
   die (menerror("No existe el usuario ".$parametro1p));
   } else 
     {
       session_register("servidor");
       session_register("bada");
       session_register("parametro1");
       session_register("parametro2");
       session_register("servidorf");
       session_register("badaf");
       session_register("parametro1f");
       session_register("parametro2f");
       session_register("paragrupo");
       $Row = pg_fetch_array($sql_result, 0);
       if ( $Row["estatus"] == 0  )
          die (menerror("Tu usuario no esta autorizado "));
       $parametro1=$parametro1p;
       $parametro2=$parametro2p;
       $sql=" select pgg.groname from cat_usuarios_pg_group as cu_pgg, pg_group as pgg where cu_pgg.usename='".$parametro1."'".
            " and pgg.groname='ciudadano' and pgg.grosysid=cu_pgg.grosysid";
       $sql_result = pg_exec($connection,$sql)
                   or die(menerror("No se pudo hacer el query. ".$sql));
       $num = pg_numrows($sql_result);
       if ( $num == 0 ) { $paragrupo=""; }
       else { $Row = pg_fetch_array($sql_result, 0); $paragrupo=$Row["groname"];}
       $sql="select grababitacora(0, 3, 0,0,current_timestamp,current_timestamp,'internet ".pg_tty()."')";
                $sql_result = pg_exec($connection,$sql)
                               or die(menerror("No se pudo grabar en la bitacora"));
                $num = pg_numrows($sql_result);
                if ( $num == 0 ) {
                   die (menerror("No Genero el serial de la bitacora"));
                } else {
                   $Row = pg_fetch_array($sql_result, 0);
                   session_register("wlserial");
                   $wlserial=$Row[0];
                }
                       }
?>
<FRAMESET COLS="25%,*" >
<FRAME SRC="opciones_ant.php",NAME="menu" >
<FRAME NAME="pantallas">
</frameset>
</HTML>
